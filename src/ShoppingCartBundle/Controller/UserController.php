<?php

namespace ShoppingCartBundle\Controller;

use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Role;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($userRole);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            try {
                $em->flush();
            } catch (\Exception $e) {
                $error = 'Duplicate user (' . $user->getUsername() . ')';

                return $this->render('user/register.html.twig',
                    array('form' => $form->createView(), 'error' => $error));
            }
            //$this->userService->register($user);
            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile", name="user_profile")
     */
    public function profileAction()
    {
        /** @var User $user */
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute("shop_index");
        }

        $role = str_replace('ROLE_', '', $currentUser->getRoles()[0]);
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render("user/profile.html.twig", array('user' => $currentUser,
                'role' => $role, 'payments' => $payments
        ));
    }

    /**
     * @Route("/user/view", name="users_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function viewAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin()) {
            return $this->redirectToRoute("user_profile");
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render("user/view.html.twig",  array('form' => $form->createView(), 'users' => $users,
                'roles' => $roles, 'payments' => $payments
        ));
    }
}
