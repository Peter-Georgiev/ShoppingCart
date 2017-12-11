<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Document;
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
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $payment = new Payment();
        $payment->setUsers($this->getUser());
        $payment->setProducts($product);
        $payment->setQtty(1);

        if (count($product->getDiscounts()) > 0) {
            $percent = $this->biggestPeriodDiscount($id);
            $payment->setDiscount($percent);
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

    /**
     * @Route("/payment/checkout", name="payment_checkout")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function checkout(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(Payment::class)->findYourCart($currentUser->getId());

        if ($payments !== null) {

            $documentId = 0;
            foreach ($payments as $payment) {
                /**
                 * @var Payment $payment
                 * @var Product $product
                 */
                $product = $this->getDoctrine()->getRepository(Product::class)
                    ->find($payment->getProducts()->getId());
                $quantity = $product->getQtty() - $payment->getQtty();

                if ($quantity >= 0) {
                    $em = $this->getDoctrine()->getManager();

                    if ($documentId === 0) {
                        $document = new Document();
                        $document->setIsBuy();
                        $em->persist($document);
                        $em->flush();
                        $documentId = $document->getId();
                    }

                    $product->setQtty($quantity);
                    $em->persist($product);
                    //$em->flush();

                    // Percent ????????????????????????
                    /*$percent = 10;
                    if ($percent > 0) {
                        $payment->setDiscount(10);
                    } else {
                        $payment->setPayment($payment->getPrice());
                    }*/

                    //pay
                    $payment->setPayment($payment->getPrice());
                    $pay = $payment->getUsers()->getCash() - $payment->getPrice();
                    $payment->getUsers()->setCash($pay);

                    $payment->setDocumentId($documentId);

                    $payment->setIsPaid();
                    $em->persist($payment);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('payment_view');
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
        //var_dump($paymentsSum); exit();

        return $this->render('payment/view.html.twig',
            array('paymentsPaids' => $paymentsPaids,
                'payments' => $payments, 'paymentsSum' => $paymentsSum[0]['totalPrice'])
        );
    }

    private function biggestPeriodDiscount($productId)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);

        if ($product === null) {
            return 0;
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

        return $percent;
    }
}
