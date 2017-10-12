<?php

namespace AppBundle\Mailer;
use AppBundle\Entity\Feedback;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
{
    protected $mailer;
    private $translator;
    private $templating;
    private $from;
    private $to;
    private $name;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating ,TranslatorInterface $translator, $from, $to, $name)
    {
        $this->mailer       = $mailer;
        $this->templating   = $templating;
        $this->translator   = $translator;
        $this->from         = $from;
        $this->to           = $to;
        $this->name         = $name;
    }

    /**
     * Send a mail
     *
     * @param $to
     * @param $subject
     * @param $body
     */
    public function sendMail($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom(array($this->from => $this->name))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html')
        ;
        $this->mailer->send($mail);
    }

    /**
     * sending a feedback email
     */
    public function sendFeedback(Feedback $feedback){
        $to         = $this->to;
        $subject    = $this->translator->trans('email_feedback_subject');
        $body       = $this->templating->render('email/feedback.html.twig', array('feedback' => $feedback));
        $this->sendMail($to, $subject, $body);
    }

}

