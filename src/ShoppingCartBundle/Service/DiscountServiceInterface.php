<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;

interface DiscountServiceInterface
{
    /**
     * @param Discount $discount
     * @param int $categoryId
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function categoryDiscount(Discount $discount, int $categoryId);

    /**
     * @param array|Product $products
     * @param Discount $discount
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function allProductsDiscount($products, Discount $discount);

    /**
     * @param Discount $discount
     * @param int $productId
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function productDiscount(Discount $discount, int $productId);

    /**
     * @param array|Product $products
     * @param User $currentUser
     * @return mixed
     */
    public function biggestPeriodDiscounts($products, $currentUser);
}