<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Repository\PaymentRepository;
use ShoppingCartBundle\Repository\ProductRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductService implements ProductServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var ProductRepository */
    private $productRepository;

    /** @var DiscountService */
    private $discountService;

    /** @var PaymentRepository */
    private $paymentRepository;

    /**
     * ProductService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param ProductRepository $productRepository
     * @param DiscountService $discountService
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(EntityManagerInterface $entityManager,
                                TokenStorageInterface $tokenStorage,
                                ProductRepository $productRepository,
                                DiscountService $discountService,
                                PaymentRepository $paymentRepository)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->productRepository = $productRepository;
        $this->discountService = $discountService;
        $this->paymentRepository = $paymentRepository;

    }

    /**
     * @param $sort
     * @return array
     */
    public function sortedProducts($sort): array
    {
        $products = array();

        if ($sort == 'price_asc') {
            $products = $this->productRepository->findAllPriceAscProduct();
        } elseif ($sort == 'price_desc') {
            $products = $this->productRepository->findAllPriceDescProduct();
        } elseif ($sort == 'date_asc') {
            $products = $this->productRepository->findAllDateAscProduct();
        } elseif ($sort == 'date_desc') {
            $products = $this->productRepository->findAllDateDescProduct();
        } elseif ($sort == 'promo') {
            $productsTemp = $this->productRepository->findAllProducts();
            $currentUser = $this->tokenStorage->getToken()->getUser();

            if (count($productsTemp) == 0) {
                return $products;
            }

            if (!is_object($currentUser) || $currentUser == null) {
                $currentUser = null;
            }

            $arrDiscount = $this->discountService->biggestPeriodDiscounts($productsTemp, $currentUser);

            /** @var Product $p */
            foreach ($productsTemp as $p) {
                if (!array_key_exists($p->getId(), $arrDiscount)) {
                    continue;
                }
                $products[] = $p;
            }

        } elseif ($sort == 'most_anted') {
            $products = $this->productRepository->findAllMostWantedProducts();
        } else {
            $products = $this->productRepository->findAllProducts();
        }

        return $products;
    }

    /**
     * @param $sort
     * @param int $productId
     * @return array
     */
    public function sortedProductsInCategory($sort, int $productId): array
    {
        $products = array();

        if ($sort == 'price_asc') {
            $products = $this->productRepository->findAllPriceAscProductInCategories($productId);
        } elseif ($sort == 'price_desc') {
            $products = $this->productRepository->findAllPriceDescProductInCategories($productId);
        } elseif ($sort == 'date_asc') {
            $products = $this->productRepository->findAllDateAscProductInCategories($productId);
        } elseif ($sort == 'date_desc') {
            $products = $this->productRepository->findAllDateDescProductInCategories($productId);
        } elseif ($sort == 'promo') {
            $productsTemp = $this->productRepository->findAllProductsInCategories($productId);
            $currentUser = $this->tokenStorage->getToken()->getUser();

            if (count($productsTemp) == 0) {
                return $products;
            }

            if (!is_object($currentUser) || $currentUser == null) {
                $currentUser = null;
            }

            $arrDiscount = $this->discountService->biggestPeriodDiscounts($productsTemp, $currentUser);

            /** @var Product $p */
            foreach ($productsTemp as $p) {
                if (!array_key_exists($p->getId(), $arrDiscount)) {
                    continue;
                }
                $products[] = $p;
            }
        } elseif ($sort == 'most_anted') {
            $products = $this->productRepository->findAllMostWantedProductsInCategories($productId);
        } else {
            $products = $this->productRepository->findAllProductsInCategories($productId);
        }

        return $products;
    }

    /**
     * @param $currentUser
     * @param Product $product
     * @param Payment $payment
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function sellProduct($currentUser, Product $product, Payment $payment)
    {
        try {
            $product->setOwner($currentUser);
            $product->setCategory($payment->getProducts()->getCategory());
            $product->setName($payment->getProducts()->getName());
            $product->setModel($payment->getProducts()->getModel());

            if (intval($product->getQtty()) <= 0 || $payment->getQtty() < intval($product->getQtty())) {
                $product->setQtty($payment->getQtty());
            }
            if (intval($product->getPrice()) <= 0) {
                $product->setPrice($payment->getPayment());
            }
            $qtty = $payment->getQtty() - $product->getQtty();

            $this->entityManager->getConnection()->beginTransaction();
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            if ($qtty <= 0) {
                $this->entityManager->remove($payment);
                $this->entityManager->flush();
            } else {
                $this->paymentRepository->updateQttyPayment($payment->getId(), $qtty);
            }

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
        }
    }
}