<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\User;

/**
 * Class UserRepository
 *
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * get users filtered by role
     *
     * @param $currentPage  Current page
     * @param $role         Role (User::ROLE_ADMIN, User::ROLE_NATURALIST, User::ROLE_OBSERVER)
     * @param int $limit    Max record by pages
     *
     * @return Paginator
     */
    public function getUsersByRole($currentPage,$role,$limit = 50)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role',$role)
            ->orderBy('u.name', 'ASC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }


    /**
     * get roles list ordering by user role first
     *
     * @param User $user    User
     *
     * @return mixed
     */
    public function getRolesForSelect(User $user)
    {
        // get user role
        $userRole = $user->getRole();
        $roleName = 'profil_'.$userRole;
        $roles[$roleName] = $userRole;

        // Other roles
        if($userRole != 'ROLE_OBSERVER') {
            $roles['ROLE_OBSERVER'] = 'ROLE_OBSERVER';
        }
        if($userRole != 'ROLE_NATURALIST') {
            $roles['ROLE_NATURALIST'] = 'ROLE_NATURALIST';
        }
        if($userRole != 'ROLE_ADMIN') {
            $roles['ROLE_ADMIN'] = 'ROLE_ADMIN';
        }
        return $roles;
    }

    /**
     * get users with search name and role filter
     *
     * @param $currentPage      Current page
     * @param $role             Role (User::ROLE_ADMIN, User::ROLE_NATURALIST, User::ROLE_OBSERVER)
     * @param int $limit        Max record by pages
     * @param string $pattern   Search pattern for user name
     *
     * @return Paginator
     */
    public function searchUsersByRole($currentPage,$role,$limit = 50,$pattern = '')
    {
        $query = $this->createQueryBuilder('u');
        $query->where('u.role = :role')->setParameter('role',$role);

        if($pattern){
            $query->andWhere('u.name LIKE :pattern')->setParameter('pattern','%'.$pattern.'%');
        }
        $query->orderBy('u.name', 'ASC')->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);
        return $paginator;
    }

    /**
     * Paginator Helper
     *
     * @param $dql       DQL Query Object
     * @param int $page  Current page (defaults to 1)
     * @param int $limit The total number per page (defaults to 50)
     *
     * @return Paginator Object
     */
    public function paginate($dql, $page = 1, $limit = 50)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
