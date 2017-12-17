<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/category/create", name="category_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_create');
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart($currentUser->getId());
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllCategories();
        return $this->render('category/create.html.twig',
            array('form' => $form->createView(), 'categories' => $categories, 'payments' => $payments));
    }

    /**
     * @Route("/category/views/{id}", name="category_view")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function viewAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute('security_logout');
        }

        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute('shop_index');
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());
        $categories = $this->getDoctrine()->getRepository(Category::class)->find($id);

        return $this->render('category/category.html.twig',
            array('categories' => array($categories), 'payments' => $payments));
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction($id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if ($category === null) {
            return $this->redirectToRoute("shop_index");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($category);
                $em->flush();
            } catch (\Exception $e) {
                $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
                $payments = $this->getDoctrine()->getRepository(Payment::class)
                    ->findYourCart( $currentUser->getId());

                return $this->render('category/delete.html.twig',
                    array('category' => $category, 'form' => $form->createView(),
                        'payments' => $payments, 'danger' => 'КАТЕГОРИЯТА се използва!'));
            }

            return $this->redirectToRoute('category_create',
                array('id' => $category->getId())
            );
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render('category/delete.html.twig',
            array('category' => $category, 'form' => $form->createView(), 'payments' => $payments)
        );
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAction($id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if ($category === null) {
            return $this->redirectToRoute("shop_index");
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && !$currentUser->isEdit()) {
            return $this->redirectToRoute("shop_index");
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_create',
                array('id' => $category->getId())
            );
        }

        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render('category/edit.html.twig', array('category' => $category,
                'form' => $form->createView(), 'payments' => $payments)
        );
    }
}
