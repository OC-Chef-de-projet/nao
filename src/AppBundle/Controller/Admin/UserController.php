<?php

namespace AppBundle\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserUpdateType;

class UserController extends Controller
{

    /**
     * Lists all user entities.
     *
     */
    public function indexAction(Request $request, $page = 1, $role = 'ROLE_OBSERVER' )
    {


        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->add('search', TextType::class,[
                'label' => '',
                'required'   => false,
                'data' => '_1'
            ])
            ->add('send', SubmitType::class,[
                'label' => '',
                'attr' => [
                    'class' => 'hide'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        $data = $form->getData();
        if(isset($data['search']) && !empty($data['search'])){
            $pattern = strip_tags($data['search']);
            //$page = 1;
        }

        $pattern = ''; // No filter by default
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if(isset($data['search']) && !empty($data['search'])){
                $pattern = strip_tags($data['search']);
                $page = 1;
            }
        }

        $limit = 8;
        $users = $em->getRepository('AppBundle:User')->searchUsersByRole($page,$role,$limit,$pattern);
        $totalUsers = $users->count();
        $totalDisplayed = $users->getIterator()->count();
        $maxPages = ceil($users->count() / $limit);
        return $this->render('@AdminUser/index.html.twig', array(
            'bodyClass' => 'background-2',
            'tabs' => $this->container->get('app.user')->getUsersTabs($role),
            'users' => $users->getIterator(),
            'totalUsers' => $totalUsers,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages,
            'breadcrumb' => $this->container->get('app.user')->getIndexBreadcrumb(),
            'form' => $form->createView()
        ));
    }

    public function updateAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('AppBundle:User')->getRolesForSelect($user);

        $form = $this->createForm(UserUpdateType::class, $user, [ 'role_choice' => $roles]);
        $form->handleRequest($request);

        // Cancel
        if ($form->isSubmitted()) {
            if($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('admin_user_index');
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $user = $em->getRepository('AppBundle:User')->find($user->getId());
        }
        return $this->render('@AdminUser/user.html.twig', array(
            'breadcrumb' => $this->container->get('app.user')->getIndexBreadcrumb(),
            'bodyClass' => 'background-2',
            'form' => $form->createView(),
            'user' => $user
        ));
    }


}
