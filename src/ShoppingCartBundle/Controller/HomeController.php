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
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @Route("/", name="shop_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();
        $arrDiscount = $this->biggestPeriodDiscounts($products);

        /** @var User $currentUser */
        $currentUser = $this->getUser();
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

        /** @var Discount $discountUser */
        $discountUser = null;
        if ($this->getUser() !== null) {
            $discountUser = $this->getDoctrine()->getRepository(Discount::class)
                ->findUserDiscount($this->getUser())[0];
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

        return $arrDiscount;
    }
}
