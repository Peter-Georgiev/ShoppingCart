<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Discount;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * @Route("/review/{id}", name="review_view")
     *
     * @param $id
     * @return Response
     */
    public function viewAction($id)
    {
        /** @var User $user */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $arrDiscount = $this->biggestPeriodDiscounts(array($product));

        return $this->render("review/view.html.twig", array('product' => $product,
                'payments' => $payments, 'arrDiscount' => $arrDiscount)
        );
    }

    /**
     * @Route("/review/create/{id}", name="review_create")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function createAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("security_login");
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $review = new Review();
        $review->setOwner($currentUser);
        $review->setOwnerId($currentUser->getId());
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        $arrDiscount = $this->biggestPeriodDiscounts(array($product));

        if ($form->isSubmitted() && strlen($review->getMessage()) > 0) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $product->addReview($review);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('review_view', array('id' => $id));
        }

        return $this->render("review/view.html.twig", array('product' => $product,
                'payments' => $payments, 'arrDiscount' => $arrDiscount)
        );
    }

    /**
     * @param $products
     * @return array
     */
    private function OLDbiggestPeriodDiscounts($products)
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
