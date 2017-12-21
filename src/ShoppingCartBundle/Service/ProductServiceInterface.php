<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;

interface ProductServiceInterface
{
    /**
     * @param $sort
     * @return array
     */
    public function sortedProducts($sort): array;

    /**
     * @param $sort
     * @param int $productId
     * @return array
     */
    public function sortedProductsInCategory($sort, int $productId): array;

    /**
     * @param $currentUser
     * @param Product $product
     * @param Payment $payment
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function sellProduct($currentUser, Product $product, Payment $payment);
}