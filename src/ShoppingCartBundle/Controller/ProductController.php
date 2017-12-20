<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\CategoryType;
use ShoppingCartBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Service\DiscountServiceInterface;
use ShoppingCartBundle\Service\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var DiscountServiceInterface */
    private $discountService;

    /** @var ProductServiceInterface */
    private $productService;

    /**
     * ProductController constructor.
     * @param DiscountServiceInterface $discountService
     * @param ProductServiceInterface $productService
     */
    public function __construct(DiscountServiceInterface $discountService, ProductServiceInterface $productService)
    {
        $this->discountService = $discountService;
        $this->productService = $productService;
    }

    /**
     * @param Request $request
     *
     * @Route("/product/create", name="product_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $category = new Category();
        $category->setName($request->get('category')['name']);
        $categories = $this->getDoctrine()->getRepository(Category::class);
        $categoryRole = $categories->findOneBy(['name' => $category->getName()]);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isProduct = strlen($product->getName()) > 0 && strlen($product->getModel()) > 0 &&
                floatval($product->getQtty()) > 0 && floatval($product->getPrice()) >= 0;
            if ($isProduct) {
                $product->setOwner($this->getUser());
                $product->setCategory($categoryRole);

                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();

                return $this->redirectToRoute('shop_index');
            }
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAllProducts();
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());

        return $this->render('product/create.html.twig',
            array('form' => $form->createView(), 'categories' => $categories,
                'products' => $products, 'payments' => $payments)
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
    public function editAction($id, Request $request)
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
            $product->setMostWanted(intval($request->get('topProduct')['mostWanted']));

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
            ->findYourCart($currentUser->getId());

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
    public function deleteAction($id, Request $request)
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
     * @Route("/product/category/{id}", name="product_category")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function categoryAction($id, Request $request)
    {
        preg_match('/sort=\w+/', $request->getRequestUri(), $url);
        $sort = '';
        if (count($url) > 0) {
            $arrUrl = explode('sort=', $url[0]);
            $sort = trim(end($arrUrl));
        }
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $products = $this->productService->sortedProductsInCategory($sort, $id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();
        $arrDiscount = $this->discountService->biggestPeriodDiscounts($products, $currentUser);

        if ($currentUser !== null) {
            $payments = $this->getDoctrine()->getRepository(Payment::class)
                ->findYourCart($currentUser->getId());

            return $this->render('home/index.html.twig', array('products' => $products,
                'categories' => $categories, 'payments' => $payments, 'arrDiscount' => $arrDiscount));
        }

        return $this->render('home/index.html.twig', array('products' => $products,
            'categories' => $categories, 'arrDiscount' => $arrDiscount));
    }

    /**
     * @Route("/product/prasc", name="product_price_asc")
     *
     * @param Request $request
     * @return Response
     */
    public function sortPriceAscAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'price_asc'));
        }

        return $this->redirectToRoute('shop_index', array('sort' => 'price_asc'));
    }

    /**
     * @Route("/product/prdesc", name="product_price_desc")
     *
     * @param Request $request
     * @return Response
     */
    public function sortPriceDescAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'price_desc'));
        }

        return $this->redirectToRoute('shop_index', array('sort' => 'price_desc'));
    }

    /**
     * @Route("/product/dtasc", name="product_date_asc")
     *
     * @param Request $request
     * @return Response
     */
    public function sortDateAscAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'date_asc'));
        }
        return $this->redirectToRoute('shop_index', array('sort' => 'date_asc'));
    }

    /**
     * @Route("/product/dtdesc", name="product_date_desc")
     *
     * @param Request $request
     * @return Response
     */
    public function sortDateDescAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'date_desc'));
        }
        return $this->redirectToRoute('shop_index', array('sort' => 'date_desc'));
    }

    /**
     * @Route("/product/promo", name="product_promo_asc")
     *
     * @param Request $request
     * @return Response
     */
    public function sortPromoAscAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'promo'));
        }
        return $this->redirectToRoute('shop_index', array('sort' => 'promo'));
    }

    /**
     * @Route("/product/top", name="product_top")
     *
     * @param Request $request
     * @return Response
     */
    public function sortMostWantedAction(Request $request)
    {
        preg_match('/product\/category\/\d+/', $request->server->get('HTTP_REFERER'), $url);

        if (count($url) > 0) {
            $arrUrl = explode('/', $url[0]);
            $catecoryId = intval(end($arrUrl));

            return $this->redirectToRoute('product_category',
                array('id' => $catecoryId, 'sort' => 'most_anted'));
        }
        return $this->redirectToRoute('shop_index', array('sort' => 'most_anted'));
    }

    /**
     * @Route("/product/sell/{id}", name="product_sale")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sellAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)->find($id);

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setOwner($this->getUser());
            $product->setCategory($payments->getProducts()->getCategory());
            $product->setName($payments->getProducts()->getName());
            $product->setModel($payments->getProducts()->getModel());

            if (intval($product->getQtty()) <= 0 || $payments->getQtty() < intval($product->getQtty())) {
                $product->setQtty($payments->getQtty());
            }
            if (intval($product->getPrice()) <= 0) {
                $product->setPrice($payments->getPayment());
            }
            $qtty = $payments->getQtty() - $product->getQtty();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            if ($qtty <= 0) {
                $em->remove($payments);
                $em->flush();
            } else {
                $this->getDoctrine()->getRepository(Payment::class)
                    ->updateQttyPayment($payments->getId(), $qtty);
            }
            return $this->redirectToRoute('shop_index');
        }

        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findUserByProducts($currentUser->getId());

        return $this->render('product/sell.html.twig', array('form' => $form->createView(),
                'products' => $products, 'payments' => $payments)
        );
    }

    /**
     * @Route("/product/{id}", name="product_view")
     * @param $id
     * @return Response
     */
    public function viewAction($id)
    {
        /** @var User $user */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("shop_index");
        }

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $arrDiscount= $this->discountService->biggestPeriodDiscounts(array($product), $this->getUser());
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($this->getUser()->getId());

        return $this->render('product/product.html.twig', array('product' => $product,
                'payments' => $payments, 'arrDiscount' => $arrDiscount
        ));
    }
}
