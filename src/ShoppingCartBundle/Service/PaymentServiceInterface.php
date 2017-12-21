<?php

namespace ShoppingCartBundle\Service;



use ShoppingCartBundle\Entity\Payment;

interface PaymentServiceInterface
{
    /**
     * @param array|Payment $payments
     * @return mixed
     */
    public function checkout($payments);
}