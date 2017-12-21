<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Repository\CategoryRepository;
use ShoppingCartBundle\Repository\DiscountRepository;
use ShoppingCartBundle\Repository\ProductRepository;

class DiscountService implements DiscountServiceInterface
{
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /** @var DiscountRepository */
    private $discountRepository;

    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * DiscountService constructor.
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     * @param DiscountRepository $discountRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ProductRepository $productRepository,
                                DiscountRepository $discountRepository,
                                CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Discount $discount
     * @param int $categoryId
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function categoryDiscount(Discount $discount, int $categoryId)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $this->entityManager->persist($discount);
            $this->entityManager->flush();

            $category = $this->categoryRepository->find($categoryId);

            $category->addDiscount($discount);

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
        }
    }

    /**
     * @param array|Product $products
     * @param Discount $discount
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function allProductsDiscount($products, Discount $discount)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $this->entityManager->persist($discount);
            $this->entityManager->flush();

            foreach ($products as $product) {
                $product->addDiscount($discount);
                $this->entityManager->persist($product);
                $this->entityManager->flush();
            }

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
        }
    }

    /**
     * @param Discount $discount
     * @param int $productId
     * @return mixed|void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function productDiscount(Discount $discount, int $productId)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $this->entityManager->persist($discount);
            $this->entityManager->flush();

            $product = $this->productRepository->find($productId);

            $product->addDiscount($discount);
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
        }
    }

    /**
     * @param array|Product $products
     * @param User $currentUser
     * @return array|mixed
     */
    public function biggestPeriodDiscounts($products, $currentUser)
    {
        /** @var Discount $discountUser */
        $discountUser = null;
        $arrDiscount = [];

        if ($currentUser !== null) {
            $discounts = $this->discountRepository->findUserDiscount($currentUser);
            if (count($discounts) > 0) {
                $discountUser = $discounts[0];
            }
        }

        //var_dump($currentUser);
        //var_dump($discountUser); exit();

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

        return $arrDiscount;
    }
}