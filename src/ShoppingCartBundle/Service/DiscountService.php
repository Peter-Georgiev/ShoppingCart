<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManager;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Repository\DiscountRepository;
use ShoppingCartBundle\Repository\ProductRepository;

class DiscountService implements DiscountServiceInterface
{
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var EntityManager */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /** @var DiscountRepository */
    private $discountRepository;

    /**
     * HomeService constructor.
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     * @param DiscountRepository $discountRepository
     */
    public function __construct(EntityManager $entityManager,
                                ProductRepository $productRepository,
                                DiscountRepository $discountRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
    }


    /**
     * @param Product[] $products
     * @param User $currentUser
     * @return array
     */
    public function biggestPeriodDiscounts($products, $currentUser)
    {
        $arrDiscount = [];

        /** @var Discount $discountUser */
        $discountUser = null;
        if ($currentUser !== null) {
            $discounts = $this->discountRepository->findUserDiscount($currentUser);
            if (count($discounts) > 0) {
                $discountUser = $discounts[0];
            }
        }

        /** @var Product $p */
        foreach ($products as $p) {

            $currentData = ((new \DateTime('now'))->format(self::$DATE_FORMAT));
            $percent = 0;

            /** @var Discount $discount */
            foreach ($p->getDiscounts() as $discount) {
                if (!($discount->getStartDate()->format(self::$DATE_FORMAT) <= $currentData &&
                    $currentData <= $discount->getEndDate()->format(self::$DATE_FORMAT))) {
                    continue;
                }

                if ($discount->getPercent() > $percent) {
                    $percent = floatval($discount->getPercent());
                }
            }

            foreach ($p->getCategory()->getDiscounts() as $discount) {
                if (!($discount->getStartDate()->format(self::$DATE_FORMAT) <= $currentData &&
                    $currentData <= $discount->getEndDate()->format(self::$DATE_FORMAT))) {
                    continue;
                }

                if ($discount->getPercent() > $percent) {
                    $percent = floatval($discount->getPercent());
                }
            }

            if ($discountUser !== null && $discountUser->getPercent() > $percent) {
                $percent = floatval($discountUser->getPercent());
            }

            if ($percent > 0) {
                $newPrice = round($p->getPrice() - (($percent / 100) * $p->getPrice()), 2);
                $arrDiscount[$p->getId()] = array('percent' => $percent, 'newPrice' => $newPrice);
            }
        }

        return  $arrDiscount;
    }
}