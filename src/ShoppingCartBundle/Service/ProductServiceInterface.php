<?php

namespace ShoppingCartBundle\Service;


interface ProductServiceInterface
{
    public function sortedProducts($sort): array;

    public function sortedProductsInCategory($sort, int $productId): array;
}