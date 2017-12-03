<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\ShoppingCartBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="shop_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();

        if ($currentUser !== null) {
            $payments = $this->getDoctrine()->getRepository(Payment::class)
                ->findYourCart($currentUser->getId());

            return $this->render('home/index.html.twig',
                array('products' => $products, 'categories' => $categories, 'payments' => $payments));
        }

        return $this->render('home/index.html.twig',
            array('products' => $products, 'categories' => $categories));
    }

    /**
     * @Route("/sort", name="sort_index")
     * @Method("GET")
     */
    public function sortByCategory()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('home/index.html.twig',
        ['products' => $products]);
    }
}
