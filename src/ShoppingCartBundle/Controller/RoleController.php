<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Payment;
use ShoppingCartBundle\Entity\Role;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @Route("/role/change/{id}", name="role_change")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function changeAction($id, Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if ($currentUser == null || !$currentUser->isAdmin()) {
            return $this->redirectToRoute("shop_index");
        }

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isBan = boolval($request->request->get('ban')['ban']);

            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepository->findOneBy(['name' => $role->getName()]);

            if (trim(strtoupper($user->getRoles()[0])) !== trim(strtoupper($userRole->getName()))) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository(Role::class)->changeRole($userRole->getId(), $id);
            }

            if ($user->isBan() !== $isBan) {
                $this->getDoctrine()->getRepository(User::class)->banUserBool($isBan, $id);
            }

            return $this->redirectToRoute('users_view');
        }

        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render("role/change.html.twig", array('form' => $form->createView(), 'user' => $user,
                'roles' => $roles, 'payments' => $payments)
        );
    }
}
