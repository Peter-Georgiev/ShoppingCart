<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        return $this->render("review/view.html.twig",
            array('product' => $product, 'payments' => $payments)
        );
    }

    /**
     * @Route("/review/save/{id}", name="review_save")
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

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $product->addReview($review);
            $em->persist($product);
            $em->flush();
        }

        return $this->render("review/view.html.twig",
            array('product' => $product, 'payments' => $payments)
        );
    }
}
