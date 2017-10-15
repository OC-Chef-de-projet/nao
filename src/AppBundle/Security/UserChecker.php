<?php
/**
 * Created by PhpStorm.
 * User: psa
 * Date: 15/10/17
 * Time: 13:13
 */

namespace AppBundle\Security;

use AppBundle\Exception\AccountDeletedException;
use AppBundle\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\User;


class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new AccountDeletedException('unknown_account');
        }

        error_log(print_r($user,true));
        if ($user->getInactive() == 1) {
            throw new AccountExpiredException('disabled_account');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException('expired_account');
       }
    }
}