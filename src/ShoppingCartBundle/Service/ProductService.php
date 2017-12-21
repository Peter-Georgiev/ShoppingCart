<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Repository\ProductRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductService implements ProductServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /** @var DiscountService */
    private $discountService;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * ProductService constructor.
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     * @param DiscountService $discountServices
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ProductRepository $productRepository,
                                DiscountService $discountService,
                                TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->discountService = $discountService;
        $this->tokenStorage = $tokenStorage;
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
}