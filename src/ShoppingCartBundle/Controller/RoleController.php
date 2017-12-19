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

            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $formUserRole = $roleRepository->findOneBy(['name' => $role->getName()]);
            $formIsBan = boolval($request->request->get('ban')['ban']);
            $formCash = floatval($request->get('user')['cash']);

            if (trim(strtoupper($user->getRoles()[0])) !== trim(strtoupper($formUserRole->getName()))) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository(Role::class)->changeRole($formUserRole->getId(), $id);
            }

            if ($user->getCash() !== $formCash) {
                $this->getDoctrine()->getRepository(User::class)->updateCash($id, $formCash);
            }

            if ($user->isBan() !== $formIsBan) {
                $this->getDoctrine()->getRepository(User::class)->updateBanUser($id, $formIsBan);
            }

            return $this->redirectToRoute('users_view');
        }

        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        $payments = $this->getDoctrine()->getRepository(Payment::class)
            ->findYourCart( $currentUser->getId());

        return $this->render("role/change.html.twig", array('form' => $form->createView(), 'user' => $user,
                'roles' => $roles, 'payments' => $payments
        ));
    }
}
