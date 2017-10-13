<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Type\RegisterType;

class RegistrationController extends Controller
{
    /**
     * @Route("/inscription", name="registration")
     * @Method({"GET","POST"})
     */
    public function registrationAction(Request $request)
    {
        // Only for user not logged
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $wantNewsletter =  isset($request->get('register')['newsletter']) ? true : false;
            $this->container->get('app.user')->create($user, $wantNewsletter);
            //return $this->redirectToRoute('registration');
        }

        return $this->render('registration.html.twig', array(
            'form'      => $form->createView()
        ));
    }

    /**
     * @Route("/inscription/activation/{code}", name="registration.activation", requirements={"code": "[a-z0-9]+"})
     * @Method({"GET","POST"})
     */
    public function AccountActivationAction(Request $request)
    {

    }
}
