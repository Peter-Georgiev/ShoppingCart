<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Document;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Service\DiscountServiceInterface;
use ShoppingCartBundle\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var DiscountServiceInterface */
    private $discountService;

    /** @var PaymentService */
    private $paymentService;

    /**
     * PaymentController constructor.
     * @param DiscountServiceInterface $discountService
     */
    public function __construct(DiscountServiceInterface $discountService,
                                PaymentService $paymentService)
    {
        $this->discountService = $discountService;
        $this->paymentService = $paymentService;
    }

    /**
     * @Route("/payment/cart/{id}", name="payment_cart")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function addInCartAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute('security_login');
        }

        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $discount = $this->discountService->biggestPeriodDiscounts(array($product), $currentUser);

        $payment = new Payment();
        $payment->setUsers($this->getUser());
        $payment->setProducts($product);
        $payment->setQtty(1);

        if (count($discount) > 0) {
            $payment->setDiscount($discount[$id]['percent']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $em->flush();

        return $this->redirectToRoute('payment_view_cart');
    }

    /**
     * @Route("/payment/viewcart", name="payment_view_cart")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function viewCartAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $totalPrice = $this->getDoctrine()->getRepository(Payment::class)
            ->findSumYourCart($currentUser->getId());

        if ($totalPrice[0]['totalPrice'] === null) {
            $totalPrice[0] = array('totalPrice' => 0.00);
        }

        $hasCheckout = true;
        if ($totalPrice[0]['totalPrice'] >= $currentUser->getCash()) {
            $hasCheckout = false;
        }

        return $this->render('payment/view_cart.html.twig', array('payments' => $payments,
                'totalPrice' => $totalPrice[0]['totalPrice'], 'hasCheckout' => $hasCheckout));
    }

    /**
     * @Route("/payment/remall", name="payment_remove_products")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function removeAllInCartAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(Payment::class)->findYourCart($currentUser->getId());

        foreach ($payments as $payment) {
            $em->remove($payment);
            $em->flush();
        }

        return $this->redirectToRoute('payment_view_cart');
    }

    /**
     * @Route("/payment/rem/{id}", name="payment_remove_product")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function removeProductInCartAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $em = $this->getDoctrine()->getManager();
        $payment = $em->getRepository(Payment::class)->find($id);
        $em->remove($payment);
        $em->flush();

        if ($currentUser->isAdmin()) {
            return $this->redirectToRoute('payment_view_admin');
        }

        return $this->redirectToRoute('payment_view_cart');
    }

    /**
     * @Route("/payment/checkout", name="payment_checkout")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function checkoutAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());

        if ($payments !== null) {
            try {
                $this->paymentService->checkout($payments);
                return $this->redirectToRoute('payment_view');
            } catch (\Exception $e) {
                //TODO - NO
            }
        }
        return $this->redirectToRoute('payment_view_cart');
    }

    /**
     * @Route("/payment/view", name="payment_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function viewAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $paymentsPaids = $this->getDoctrine()->getRepository(Payment::class)
            ->findAllBuy($currentUser->getId());
        $paymentsSum = $this->getDoctrine()->getRepository(Payment::class)
            ->findSumPayments($currentUser->getId());

        return $this->render('payment/view.html.twig', array('paymentsPaids' => $paymentsPaids,
                'payments' => $payments, 'paymentsSum' => $paymentsSum[0]['totalPrice'])
        );
    }

    /**
     * @Route("/payment/viewadm", name="payment_view_admin")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function viewAdminAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        if (!$currentUser->isAdmin()) {
            return $this->redirectToRoute('payment_view');
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $paymentsPaids = $this->getDoctrine()->getRepository(Payment::class)->findAll();
        //$paymentsSum = $this->getDoctrine()->getRepository(Payment::class)->findAll();
        return $this->render('payment/view_admin.html.twig', array('paymentsPaids' => $paymentsPaids,
                'payments' => $payments
        ));
    }
}
