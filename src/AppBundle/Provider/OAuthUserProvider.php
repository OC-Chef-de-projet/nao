<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 14/10/17
 * Time: 12:40
 */

namespace AppBundle\Provider;

use Doctrine\Common\Persistence\ManagerRegistry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use AppBundle\Security\UserChecker;
use AppBundle\Service\UserService;


class OAuthUserProvider extends EntityUserProvider
{

    protected $repository;
    protected $class;
    private $userService;

    public function __construct(ManagerRegistry $registry, UserService $userService)
    {

        $this->userService = $userService;
        $class = "AppBundle\Entity\User";
        $managerName = null;

        parent::__construct($registry, $class, [], $managerName);
        $this->repository = $this->em->getRepository($this->class);
    }

    /**
     * @param UserResponseInterface $response
     * @return object
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {


        $checker = new UserChecker();

        $service = $response->getResourceOwner()->getName();
        $propertyName = $service . 'Id';
        $methodName = 'set' . ucfirst($propertyName);

        $socialId = $response->getUsername();
        $email = $response->getEmail();

        error_log("propertyName $propertyName");
        error_log("socialId $socialId");

        if (($user = $this->repository->findOneBy([$propertyName => $socialId])) !== null) {
            $checker->checkPreAuth($user);
        } elseif (($user = $this->repository->findOneByEmail($email)) !== null) {
            $checker->checkPreAuth($user);
            $user->$methodName($socialId);
        } else {
            // Create user
            $user = new $this->class();
            $user->setName($response->getNickname());
            $user->setEmail($email);
            $user->setPlainPassword(uniqid());
            $user->setInactive(false);
            $user->$methodName($socialId);
            $user->setRole('ROLE_OBSERVER');
            $user->setImagePath($response->getProfilePicture());
            $this->userService->create($user, false);
        }

        // Update values if needed
        if (!$user->getName()) {
            $user->setName($response->getLastName());
        }
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}