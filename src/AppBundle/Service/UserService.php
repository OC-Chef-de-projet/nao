<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Mailer\Mailer;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class User
 *
 * @package AppBundle\Service
 */
class UserService
{
    private $em;
    private $ts;
    private $list_limit;
    private $newsletter;
    private $passwordEncoder;
    private $mailer;

    /**
     * UserService constructor.
     *
     * @param TokenStorage $ts
     * @param $list_limit
     */
    public function __construct(EntityManager $em, TokenStorage $ts,$list_limit, NewsletterService $newsletter, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        $this->em               = $em;
        $this->ts               = $ts;
        $this->list_limit       = $list_limit;
        $this->newsletter       = $newsletter;
        $this->passwordEncoder  = $passwordEncoder;
        $this->mailer           = $mailer;
    }

    /**
     * Get pagination parameters
     *
     * @param Paginator $users
     * @param $page
     * @return array Current pagination
     */
    public function getPagination(Paginator $users,$page)
    {

        $totalUsers = $users->count();
        $totalDisplayed = $users->getIterator()->count();
        $maxPages = ceil($users->count() / $this->list_limit);

        return [
            'totalUsers' => $totalUsers,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => (int)$maxPages,
        ];
    }

    /**
     * Get user informations
     *
     * @param $url
     * @return array
     */
    public function getUserInfo($url)
    {

        $response = [];
        $user = $this->ts->getToken()->getUser();

        if (!$user) {
            return [];
        }

        if ($user) {
            $image = $user->getImagePath();
            if($image) {
                $image = $url . '/images/users/' . $image;
            }

            $response = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'aboutme' => $user->getAboutme(),
                'image' => $image,
                'created' => $user->getCreated(),
                'roleString' => $user->getRoleString(),
                'name' => $user->getName()
            ];
        }
        return $response;
    }

    /**
     * Create new user
     *
     * @param User $user
     * @param bool $subsribeToNewsletter
     */
    public function create(User $user, $subsribeToNewsletter = false){

        // Encode the password
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // save the User
        //$this->em->persist($user);
        //$this->em->flush();

        // Subscribe to newsletter
        if($subsribeToNewsletter){
           // $this->newsletter->subscribe($user->getEmail());
        }

        // Send mail with activation link
        $this->mailer->sendActivationAccount($user);
    }
}

