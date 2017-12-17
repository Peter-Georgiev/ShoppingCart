<?php

namespace ShoppingCartBundle\Service;


interface DiscountServiceInterface
{
    public function biggestPeriodDiscounts($products, $currentUser);
}