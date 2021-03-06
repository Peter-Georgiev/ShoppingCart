<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use ShoppingCartBundle\Entity\Role;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManager $em, Mapping\ClassMetadata $class = null)
    {
        parent::__construct($em,
            $class == null ? new Mapping\ClassMetadata(Role::class) : $class
        );
    }

    public function changeRole($roleId, $userId)
    {
        $query = 'UPDATE users_roles SET role_id = :roleId WHERE user_id = :userId';
        $params = array('roleId' => $roleId, 'userId' => $userId);

        return $this->getEntityManager()->getConnection()
            ->executeQuery($query, $params)->execute();
    }
}
