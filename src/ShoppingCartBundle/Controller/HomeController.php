<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;
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

        if ($currentUser !== null && $currentUser->isBan()){
            // ban USER -> isBan
            return $this->redirectToRoute('security_logout');
        }

        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();

        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();

        //var_dump($products[1]->getReviews()[0]);
        //var_dump($products[1]->getReviews()[0]->getOwner());


        $arrDiscount = $this->biggestPeriodDiscounts($products);

        //var_dump($arrDiscount); exit();

        if ($currentUser !== null) {
            $payments = $this->getDoctrine()->getRepository(Payment::class)
                ->findYourCart($currentUser->getId());

            return $this->render('home/index.html.twig', array('products' => $products,
                'categories' => $categories, 'payments' => $payments, 'arrDiscount' => $arrDiscount));
        }


        return $this->render('home/index.html.twig', array('products' => $products,
            'categories' => $categories, 'reviews' => $reviews, 'arrDiscount' => $arrDiscount));
    }

    /**
     * @Route("/sort", name="sort_index")
     * @Method("GET")
     */
    public function sortByCategory()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $arrDiscount = $this->biggestPeriodDiscounts($products);

        return $this->render('home/index.html.twig',
            array('products' => $products, 'arrDiscount' => $arrDiscount));
    }

    /**
     * @param $products
     * @return array
     */
    private function biggestPeriodDiscounts($products)
    {
        $arrDiscount = [];

        /** @var Product $p */
        foreach ($products as $p) {

            if (count($p->getDiscounts()) === 0) {
                continue;
            }

            /** @var Product $product */
            $product = $this->getDoctrine()->getRepository(Product::class)->find($p->getId());

            if ($product === null) {
                continue;
            }

            $dateDiscProduct = new \DateTime('2000-01-01 00:00:00');
            $percent = 0;

            foreach ($product->getDiscounts() as $discount) {

                if ($discount->getEndDate() >= ((new \DateTime('now'))
                        ->modify("1 hour")
                        ->format('Y-m-d H:m:s')) &&
                    $discount->getEndDate() > $dateDiscProduct) {

                    $percent = $discount->getPercent();
                    $dateDiscProduct = $discount->getEndDate();
                }
            }

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

            if ($percent > 0) {
                $newPrice = round($product->getPrice() - (($percent / $product->getPrice()) * 100), 2);

                $arrDiscount[$product->getId()] = array('percent' => floatval($percent), 'newPrice' => $newPrice);
            }
        }
        return $arrDiscount;
    }
}
