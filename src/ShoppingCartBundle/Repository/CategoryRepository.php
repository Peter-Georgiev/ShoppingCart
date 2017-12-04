<?php

namespace ShoppingCartBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllCategories()
    {
        return $this->createQueryBuilder('c')
            ->where('c.isDelete = :isDelete')
            ->orderBy('c.name')
            ->orderBy('c.id')
            ->setParameter('isDelete', false)
            ->getQuery()
            ->getResult();
    }

    public function deleteCategory($categoryId)
    {
        return $this->createQueryBuilder('c')
            ->update()
            ->set('c.isDelete', '?1')
            ->setParameter(1, true)
            ->where('c.id = ?2')
            ->setParameter(2, $categoryId)
            ->getQuery()
            ->getSingleScalarResult();

    }
}