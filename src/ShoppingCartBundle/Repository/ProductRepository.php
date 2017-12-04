<?php

namespace ShoppingCartBundle\Repository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllProducts()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            //->where('p.delProduct = :del')
            ->where('p.qtty > 0')
            ->where('p.isDelete = :isDelete')
            ->orderBy('p.name')
            ->orderBy('p.id')
            ->setParameter('isDelete', false)
            ->getQuery()
            ->getResult();
    }


    public function findAllProductsInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->orderBy('p.name')
            ->orderBy('p.id')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->getQuery()
            ->getResult();
    }

    public function deleteProduct($productId)
    {
        return $this->createQueryBuilder('p')
            ->update()
            ->set('p.isDelete', '?1')
            ->setParameter(1, true)
            ->where('p.id = ?2')
            ->setParameter(2, $productId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}