<?php

namespace AppBundle\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserUpdateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Class UserController
 *
 * @Route("/admin/user")
 *
 * @package AppBundle\Controller\Admin
 */
class UserController extends Controller
{

    /**
     * Admin user menu
     *
     * @Route("/{page}/{role}", requirements={"page" = "\d+"} , defaults={"page" = 1, "role" = "ROLE_OBSERVER"}, name="admin_user_index")
     * @Method({"GET"})
     *
     * @param Request $request Http request
     * @param int $page Page to display
     * @param string $role Role filter
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1, $role = 'ROLE_OBSERVER')
    {
        $em = $this->getDoctrine()->getManager();
        $c = $this->get('security.token_storage')->getToken()->getUser();
        $users = $em->getRepository('AppBundle:User')->searchUsersByRole($page, $role, $this->getParameter('list_limit'));

        return $this->render('@AdminUser/index.html.twig', [
            'token' => $this->container->get('lexik_jwt_authentication.jwt_manager')->create($c),
            'paginate' => $this->container->get('app.user')->getPagination($users, $page),
            'users' => $users->getIterator(),
        ]);
    }


    /**
     * Update user profile (only role and blocked status)
     *
     * @Route("/edit/{id}",  requirements={"id" = "\d+"}, name="admin_user_update")
     * @Method({"GET","POST"})
     * @param Request $request Http request
     * @param User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('AppBundle:User')->getRolesForSelect($user);

        $form = $this->createForm(UserUpdateType::class, $user, ['role_choice' => $roles]);
        $form->handleRequest($request);

        // Cancel
        if ($form->isSubmitted()) {
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_user_index', ['page' => 1, 'role' => $user->getRole()]);
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user_index', ['page' => 1, 'role' => $user->getRole()]);
        }
        return $this->render('@AdminUser/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
