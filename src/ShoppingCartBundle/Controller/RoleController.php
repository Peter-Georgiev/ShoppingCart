<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Role;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @Route("/admin/role/change/{id}", name="role_change")
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
            $userRole = $roleRepository->findOneBy(['name' => $role->getName()]);

            $em = $this->getDoctrine()->getManager();
            $em->getRepository(Role::class)->changeRole($userRole->getId(), $id);

            return $this->redirectToRoute('users_view');
        }

        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        return $this->render("admin/role/change.html.twig",
            array('form' => $form->createView(), 'user' => $user, 'roles' => $roles)
        );
    }
}
