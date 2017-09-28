<?php

namespace AppBundle\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserUpdateType;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * Class UserController
 *
 * @package AppBundle\Controller\Admin
 */
class UserController extends Controller
{

    /**
     * Admin user menu
     *
     * @param Request $request  Http request
     * @param int $page         Page to display
     * @param string $role      Role filter
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
        }

        $pattern = ''; // No filter by default
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if(isset($data['search']) && !empty($data['search'])){
                $pattern = strip_tags($data['search']);
                $page = 1;
            }
        }

        $users = $em->getRepository('AppBundle:User')->searchUsersByRole($page,$role,$this->getParameter('list_limit'),$pattern);

        return $this->render('@AdminUser/index.html.twig', [
            'header' => [
                'bodyClass' => 'background-2',
                'tabs' => $this->container->get('app.user')->getUsersTabs($role),
                'breadcrumb' => $this->container->get('app.user')->getIndexBreadcrumb()
            ],
            'paginate' => $this->container->get('app.user')->getPagination($users,$page),
            'users' => $users->getIterator(),
            'form' => $form->createView()
        ]);
    }


    public function paginateAction(Request $request)
    {
        $logger = $this->get('logger');

        if ($request->isXMLHttpRequest()) {
            $page = $request->request->get('page');
            $logger->info('I just got the logger '.$page);
            $response = ['output' => 'here the result!'];
            return new JsonResponse($response);
        }
    }

    /**
     * Update user profile (only role and blocked)
     *
     * @param Request $request  Http request
     * @param User $user        User entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
        return $this->render('@AdminUser/user.html.twig', [
            'header' => [
                'breadcrumb' => $this->container->get('app.user')->getIndexBreadcrumb(),
                'bodyClass' => 'background-2'
            ],
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
