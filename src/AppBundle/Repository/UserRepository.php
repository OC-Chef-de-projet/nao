<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\User;

class UserRepository extends EntityRepository
{
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
