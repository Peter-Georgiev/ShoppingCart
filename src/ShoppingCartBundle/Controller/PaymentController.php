<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
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
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $payment = new Payment();

        $payment->setUsers($this->getUser());
        $payment->setProducts($product);
        $payment->setQtty(1);

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

        return $this->render('payment/view_cart.html.twig',
            array('payments' => $payments,
                'totalPrice' => $totalPrice[0]['totalPrice'],
                'hasCheckout' => $hasCheckout));

    }


    /**
     * @Route("/payment/remall", name="payment_remove_products")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function removeAllInCart(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(Payment::class)->findYourCart($currentUser->getId());
        //var_dump($payment); exit();
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
    public function removeProductInCart($id, Request $request)
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

        return $this->redirectToRoute('payment_view_cart');
    }
}
