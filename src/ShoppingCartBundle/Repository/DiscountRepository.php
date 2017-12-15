<?php

namespace ShoppingCartBundle\Repository;
use Doctrine\ORM\Query\Expr\Join;
use ShoppingCartBundle\Entity\User;

/**
 * DiscountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DiscountRepository extends \Doctrine\ORM\EntityRepository
{
    protected static function DateNowStr()
    {
        return (new  \DateTime)->format('Y-m-d H:i:s');
    }

    public function findAllDateDisc()
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.products', 'p', Join::WITH, 'd.product_id = p.id')
            ->innerJoin('d.categories', 'c', Join::WITH, 'd.category_id = c.id')
            ->orderBy('d.endDate')
            //->addOrderBy('d.percent', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllUserDiscount()
    {
        return $this->createQueryBuilder('d')
            ->where('d.isUser = 1')
            ->andWhere('d.percent > 0')
            ->orderBy('d.percent','DESC')
            ->addOrderBy('d.endDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findUserDiscount(User $user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.isUser = 1')
            ->andWhere('d.percent > 0')
            ->andWhere('d.startDate <= ?1')
            ->andWhere('d.endDate >= ?2')
            ->andWhere('d.userDays <= ?3')
            ->orWhere('d.userCash <= ?4')
            ->setParameter(1, self::DateNowStr())
            ->setParameter(2, self::DateNowStr())
            ->setParameter(3, ($user->getRegTime()->diff(new \DateTime(self::DateNowStr())))->days)
            ->setParameter(4, $user->getCash())
            ->orderBy('d.percent', 'DESC')
            ->addOrderBy('d.endDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findCashByUser(User $user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.isUser = 1')
            ->andWhere('d.percent > 0')
            ->andWhere('d.startDate <= ?1')
            ->andWhere('d.endDate >= ?2')
            ->andWhere('d.userCash <= ?3')
            ->setParameter(1, self::DateNowStr())
            ->setParameter(2, self::DateNowStr())
            ->setParameter(3, $user->getCash())
            ->orderBy('d.percent', 'DESC')
            ->addOrderBy('d.endDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
