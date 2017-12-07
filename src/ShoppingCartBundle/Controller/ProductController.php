<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
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
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute("security_login");
        }

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
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render('product/create.html.twig',
            array('form' => $form->createView(), 'categories' => $categories,
                'products' => $products, 'payments' => $payments)
        );
    }

    /**
     * @Route("/product/{id}", name="product_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProduct($id)
    {
        /** @var User $user */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("shop_index");
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)->find($id);

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($this->getUser()->getId());

        return $this->render('product/product.html.twig',
            array('product' => $product, 'payments' => $payments)
    );
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
        if ($product === null) {

            return $this->redirectToRoute("shop_index");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->find($product->getCategoryId());
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ArrayCollection|Category $categories */
            $categories = $category = $this->getDoctrine()->getRepository(Category::class)
                ->findCategoryIdByName($category->getName());

            $this->getDoctrine()->getRepository(Product::class)
                ->updateCatIdInProduct($product->getId(), $categories[0]->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_view',
                array('id' => $product->getId())
            );
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render('product/edit.html.twig',
            array('product' => $product, 'form' => $form->createView(),
                'categories' => $categories, 'payments' => $payments)
        );
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

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($this->getDoctrine()->getRepository(Product::class)->find($id));
                $em->flush();
            } catch (\Exception $e) {
                $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

                return $this->render('product/delete.html.twig',
                    array('product' => $product, 'form' => $form->createView(), 'payments' => $payments,
                        'danger' => 'Продукта се използва!')
                );
            }

            //$this->getDoctrine()->getRepository(Product::class)->deleteProduct($id);
            return $this->redirectToRoute('shop_index',
                array('id' => $product->getId())
            );
        }

        return $this->render('product/delete.html.twig',
            array('product' => $product, 'form' => $form->createView(), 'payments' => $payments)
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

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser !== null) {
            $payments = $this->getDoctrine()->getRepository(Payment::class)
                ->findYourCart($currentUser->getId());

            return $this->render('home/index.html.twig',
                array('products' => $products, 'categories' => $categories, 'payments' => $payments));
        }

        return $this->render('home/index.html.twig',
            array('products' => $products, 'categories' => $categories));
    }
}
