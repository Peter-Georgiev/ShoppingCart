<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Service\DiscountServiceInterface;
use ShoppingCartBundle\Service\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var ProductServiceInterface */
    private $productService;

    /** @var DiscountServiceInterface */
    private $discountService;

    /**
     * HomeController constructor.
     * @param ProductServiceInterface $productService
     * @param DiscountServiceInterface $discountService
     */
    public function __construct(ProductServiceInterface $productService,
                                DiscountServiceInterface $discountService)
    {
        $this->productService = $productService;
        $this->discountService = $discountService;
    }

    /**
     * @Route("/", name="shop_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        preg_match('/sort=\w+/', $request->getRequestUri(), $url);
        $sort = '';
        if (count($url) > 0) {
            $arrUrl = explode('sort=', $url[0]);
            $sort = trim(end($arrUrl));
        }

        $products = $this->productService->sortedProducts($sort);

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();

        $arrDiscount= $this->discountService->biggestPeriodDiscounts($products, $this->getUser());

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser !== null) {
            $payments = $this->getDoctrine()->getRepository(Payment::class)
                ->findYourCart($currentUser->getId());

            return $this->render('home/index.html.twig', array('products' => $products,
                'categories' => $categories, 'payments' => $payments, 'arrDiscount' => $arrDiscount));
        }

        //var_dump($products); exit();
        return $this->render('home/index.html.twig', array('products' => $products,
            'categories' => $categories, 'reviews' => $reviews, 'arrDiscount' => $arrDiscount));
    }
}
