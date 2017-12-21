<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\Expr\Join;
use ShoppingCartBundle\Entity\Product;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManager $em, Mapping\ClassMetadata $class = null)
    {
        parent::__construct($em,
            $class == null ? new Mapping\ClassMetadata(Product::class) : $class
        );
    }

    private static function DateNow()
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

    public function findAllProducts()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->setParameter('isDelete', false)
            ->orderBy('p.id', 'DESC')
            ->addOrderBy('p.name')
            ->getQuery()
            ->getResult();
    }

    public function findUserByProducts($userId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->andWhere('o.id = :userId')
            ->setParameter('isDelete', false)
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->addOrderBy('p.qtty')
            ->addOrderBy('p.name')
            ->getQuery()
            ->getResult();
    }

    public function findAllProductsInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.id', 'DESC')
            ->addOrderBy('p.name')
            ->getQuery()
            ->getResult();
    }

    public function findAllPriceAscProductInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.price')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllPriceAscProduct()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->setParameter('isDelete', false)
            ->orderBy('p.price')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllPriceDescProductInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.price', 'DESC')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllPriceDescProduct()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->setParameter('isDelete', false)
            ->orderBy('p.price', 'DESC')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllDateAscProductInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.dateAdded')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllDateAscProduct()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->setParameter('isDelete', false)
            ->orderBy('p.dateAdded')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllDateDescProductInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.dateAdded', 'DESC')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllDateDescProduct()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->orderBy('p.name')
            ->orderBy('p.id')
            ->setParameter('isDelete', false)
            ->orderBy('p.dateAdded', 'DESC')
            ->addOrderBy('p.name')
            ->addOrderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllMostWantedProductsInCategories($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andwhere('c.id = :categoryId')
            ->andWhere("p.isDelete = :isDelete")
            ->setParameter('categoryId', $categoryId)
            ->setParameter('isDelete', false)
            ->orderBy('p.mostWanted')
            ->addOrderBy('p.price')
            ->getQuery()
            ->getResult();
    }

    public function findAllMostWantedProducts()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c', Join::WITH, 'c.id = p.categoryId')
            ->innerJoin('p.owner', 'o', Join::WITH, 'o.id = p.ownerId')
            ->where('p.qtty > 0')
            ->andWhere('p.isDelete = :isDelete')
            ->setParameter('isDelete', false)
            ->orderBy('p.mostWanted')
            ->addOrderBy('p.price')
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

    public function updateCatIdInProduct($productId, $categoryId)
    {
        return $this->createQueryBuilder('p')
            ->update()
            ->set('p.categoryId', '?1')
            ->setParameter(1, $categoryId)
            ->where('p.id = ?2')
            ->setParameter(2, $productId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllDateDisc()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.discounts', 'd')
            ->innerJoin('p.category', 'c')
            ->innerJoin('p.owner', 'o')
            ->orderBy('d.endDate', 'DESC')
            ->addOrderBy('d.percent', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllNowDisc()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.discounts', 'd')
            ->innerJoin('p.category', 'c')
            ->innerJoin('p.owner', 'o')
            ->where('d.endDate >= ?1')
            ->setParameter(1, self::DateNow())
            ->orderBy('d.endDate', 'DESC')
            ->addOrderBy('d.percent', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPaymentProduct($productId)
    {
        return $this->createQueryBuilder('p')
            ->select(array('p', 'd', 'c'))
            ->innerJoin('p.discounts', 'd')
            ->innerJoin('p.category', 'c')
            ->where('p.qtty > 0')
            ->andwhere('p.id = ?1')
            ->andWhere('d.startDate <= ?2')
            ->andWhere('d.endDate >= ?3')
            ->setParameter(1, $productId)
            ->setParameter(2, self::DateNow())
            ->setParameter(3, self::DateNow())
            ->orderBy('d.endDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}