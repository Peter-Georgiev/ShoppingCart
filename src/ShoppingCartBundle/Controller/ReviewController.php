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
    protected static $DATE_FORMAT = 'Y-m-d H:i:s';

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
