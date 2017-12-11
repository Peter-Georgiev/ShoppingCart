<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\DiscountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscountController extends Controller
{
    /**
     * @Route("/discount/category", name="discount_category")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function categoryAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute("security_login");
        }

        if (!$currentUser->isAdmin() || !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        $categoryId = $request->request->get('category')['id'];

        $isValid = ($discount->getEndDate() > $discount->getStartDate()) &&
            $discount->getPercent() > 0;

        if ($form->isSubmitted() && $categoryId > 0 && $isValid) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($discount);
            $em->flush();

            $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);

            $category->addDiscount($discount);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('discount_category');
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('discount/create.html.twig',
            array('form' => $form->createView(), 'categories' => $categories,
                'date' => (new \DateTime())->modify("1 hour"))
        );
    }

    /**
     * @Route("/discount/allproducts", name="discount_allproducts")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function allProductsAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute("security_login");
        }

        if (!$currentUser->isAdmin() || !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $isValid = ($discount->getEndDate() > $discount->getStartDate()) &&
            $discount->getPercent() > 0;

        if ($form->isSubmitted() && $isValid) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($discount);
            $em->flush();

            foreach ($products as $product) {
                $product->addDiscount($discount);
                $em->persist($product);
                $em->flush();
            }

            return $this->redirectToRoute('discount_allproducts');
        }

        $productsDateDisc = $this->getDoctrine()->getRepository(Product::class)->findAllDateDisc();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();

        return $this->render('discount/all_products.html.twig',
            array('form' => $form->createView(), 'products' => $products,
                'productsDateDisc' => $productsDateDisc, 'date' => (new \DateTime())->modify("1 hour"))
        );
    }

    /**
     * @Route("/discount/product", name="discount_product")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function productAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute("security_login");
        }

        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        $productId = $request->request->get('product')['id'];

        $isValid = ($discount->getEndDate() > $discount->getStartDate()) &&
            $discount->getPercent() > 0;

        if ($form->isSubmitted() && $isValid) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($discount);
            $em->flush();

            $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);

            $product->addDiscount($discount);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('discount_product');
        }

        $productsDateDisc = $this->getDoctrine()->getRepository(Product::class)->findAllDateDisc();
        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findUserByProducts($currentUser->getId());

        if ($currentUser->isAdmin() || $currentUser->isEdit()) {
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        }

        return $this->render('discount/product.html.twig',
            array('form' => $form->createView(), 'products' => $products,
                'productsDateDisc' => $productsDateDisc, 'date' => (new \DateTime())->modify("1 hour"))
        );
    }
}
