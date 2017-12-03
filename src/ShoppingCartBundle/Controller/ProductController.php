<?php

namespace ShoppingCartBundle\Controller;

use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\CategoryType;
use ShoppingCartBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/product/create", name="product_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $categories = $this->getDoctrine()->getRepository(Category::class);
        $categoryRole = $categories->findOneBy(['name' => $category->getName()]);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setOwner($this->getUser());
            $product->setCategory($categoryRole);

            // 5) save the Product!
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('shop_index');
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();
        return $this->render('product/create.html.twig',
            array('form' => $form->createView(), 'categories' => $categories, 'products' => $products));
    }

    /**
     * @Route("/product/{id}", name="product_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProduct($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)->find($id);

        return $this->render('product/product.html.twig',
            ['product' => $product]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editProduct($id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->find($product->getCategoryId());

        if ($product === null) {
            return $this->redirectToRoute("shop_index");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        if ($currentUser->isAdmin()) {
            $form = $this->createForm(CategoryType::class, $categories);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($categories);
                $em->flush();
            }
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_view',
                array('id' => $product->getId())
            );
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('product/edit.html.twig',
            array('product' => $product, 'form' => $form->createView(), 'categories' => $categories));
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function deleteProduct($id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if ($product === null) {
            return $this->redirectToRoute("shop_index");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->remove($product);
            //$em->flush();

            $this->getDoctrine()->getRepository(Product::class)->deleteProduct($id);

            return $this->redirectToRoute('shop_index',
                array('id' => $product->getId())
            );
        }

        return $this->render('product/delete.html.twig',
            array('product' => $product, 'form' => $form->createView())
        );
    }

    /**
     * @Route("/product/listingCategory/{id}", name="product_listing_category")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function listingProductsInCategories($id, Request $request)
    {
        $products = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findAllProductsInCategories($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();

        return $this->render('home/index.html.twig',
            array('products' => $products, 'categories' => $categories));
    }
}
