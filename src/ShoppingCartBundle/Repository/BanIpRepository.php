<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use ShoppingCartBundle\Entity\BanIp;

/**
 * BanIpRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BanIpRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManager $em, Mapping\ClassMetadata $class = null)
    {
        parent::__construct($em,
            $class == null ? new Mapping\ClassMetadata(BanIp::class) : $class
        );
    }
}
