<?php

namespace ShoppingCartBundle\EventListener;


use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Product;

class DiscountBiggestListen
{
    /**
     * @param $products
     * @return array
     */
    public function biggestPeriodDiscounts($products)
    {
        $arrDiscount = [];

        /** @var Product $product */
        foreach ($products as $product) {

            if (count($product->getDiscounts()) === 0) {
                continue;
            }

            /** @var Product $product */
            $product = $this->getDoctrine()->getRepository(Product::class)
                ->findPaymentProduct($product->getId())[0];

            $dateDiscProduct = $product->getDiscounts()[0]->getPercent();
            $percent = $product->getDiscounts()[0]->getPercent();

            /** @var Discount $discount */
            foreach ($product->getCategory()->getDiscounts() as $discount) {
                if ($discount->getEndDate() >= ((new \DateTime('now'))
                        ->modify("1 hour")
                        ->format('Y-m-d H:m:s')) &&
                    $discount->getEndDate() > $dateDiscProduct) {
                    $percent = $discount->getPercent();
                    $dateDiscProduct = $discount->getEndDate();
                }
            }

            $arrDiscount[$product->getId()] = $percent;
        }

        return $arrDiscount;
    }
}