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
     * @return array
     */
    public function getUserInfo()
    {

        $response = [];
        $user = $this->ts->getToken()->getUser();

        if (!$user) {
            return [];
        }

        if ($user) {
            $response = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'aboutme' => $user->getAboutme(),
                'image' => $user->getImagePath(),
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
        $user->setToken($this->generateToken());

        // save the User
        $this->em->persist($user);
        $this->em->flush();

        // Subscribe to newsletter
        if($subsribeToNewsletter){
           $this->newsletter->subscribe($user->getEmail());
        }

        // Send mail with activation link
        $this->mailer->sendActivationAccount($user);
    }

    /**
     * Activating user account
     *
     * @param User $user
     * @return bool
     */
    public function activateAccount(User $user){

        if($user->getInactive()){

            // remove token and activate user account
            $user->setInactive(false);
            $user->setToken(null);

            // save the User
            $this->em->persist($user);
            $this->em->flush();
            return true;
        }

        return false;
    }

    /**
     * Recovery account
     *
     * @param $email
     * @return bool
     */
    public function recoveryAccount($email){
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $email));

        if($user){
            $user->setToken($this->generateToken());

            // save the User
            $this->em->persist($user);
            $this->em->flush();

            // Send mail with reset password link
            $this->mailer->sendPasswordResetingAccount($user);
            return true;
        }

        return false;
    }

    /**
     * Update user password
     *
     * @param User $user
     */
    public function updatePassword(User $user){

        // Encode the password
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setToken(null);

        // save the User
        $this->em->persist($user);
        $this->em->flush();
    }


    /**
     * Generate random and secure token
     *
     * @return string
     */
    private function generateToken(){
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}

