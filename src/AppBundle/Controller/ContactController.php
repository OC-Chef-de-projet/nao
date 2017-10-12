<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feedback;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Type\FeedbackType;

class ContactController extends Controller
{

    /**
     * @Route("/contact", name="contact")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $feeback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feeback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->container->get('app.mailer')->sendFeedback($feeback);
            $this->addFlash("success", true);
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', array(
            'form'      => $form->createView()
        ));
    }
}
