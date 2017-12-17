<?php

namespace ShoppingCartBundle\Service;



use Doctrine\ORM\EntityManager;
use ShoppingCartBundle\Repository\ProductRepository;

class ProductService implements ProductServiceInterface
{
    /** @var EntityManager */
    private $entityManager;

    /** @var ProductRepository */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManager $entityManager,
                                ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @param $sort
     * @return array|null
     */
    public function sortedProducts($sort): array
    {
        $products = null;

        if ($sort == 'price_asc') {
            $products = $this->productRepository->findAllPriceAscProduct();
        } elseif ($sort == 'price_desc') {
            $products = $this->productRepository->findAllPriceDescProduct();
        } elseif ($sort == 'date_asc') {
            $products = $this->productRepository->findAllDateAscProduct();
        } elseif ($sort == 'date_desc') {
            $products = $this->productRepository->findAllDateDescProduct();
        } else {
            $products = $this->productRepository->findAllProducts();
        }

        return $products;
    }

    public function sortedProductsInCategory($sort, int $productId): array
    {
        $products = null;

        if ($sort == 'price_asc') {
            $products = $this->productRepository->findAllPriceAscProductInCategories($productId);
        } elseif ($sort == 'price_desc') {
            $products = $this->productRepository->findAllPriceDescProductInCategories($productId);
        } elseif ($sort == 'date_asc') {
            $products = $this->productRepository->findAllDateAscProductInCategories($productId);
        } elseif ($sort == 'date_desc') {
            $products = $this->productRepository->findAllDateDescProductInCategories($productId);
        } else {
            $products = $this->productRepository->findAllProductsInCategories($productId);
        }

        return $products;
    }
}