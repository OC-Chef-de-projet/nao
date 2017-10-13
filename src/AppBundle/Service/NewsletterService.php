<?php

namespace AppBundle\Service;

use AppBundle\Entity\Newsletter;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NewsletterService
 * @package AppBundle\Service
 */
class NewsletterService
{

    private $em;
    private $translator;

    /**
     * NewsletterService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, TranslatorInterface $translator){
        $this->em           = $em;
        $this->translator   = $translator;
    }

    /**
     * Registration to newsletter
     *
     * @param $email
     * @return array
     */
    public function subscribe($email){
        $email = $this->emailClean($email);

        // Verify email address first
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            // Check if this email is already use for newsletter
            if(!$this->isSubscribe($email)){

                // subscribe newsletter
                $newsletter = new Newsletter();
                $newsletter->setEmail($email);
                $this->em->persist($newsletter);
                $this->em->flush();

                $response = array(
                    'status'    => true,
                    'message'   => $this->translator->trans('newsletter_subscribe_success')
                );

            }else{
                $response = array(
                    'status'    => false,
                    'message'   => $this->translator->trans('error_newsletter_already_subscribe')
                );
            }
        }else{
            $response = array(
                'status'    => false,
                'message'   => $this->translator->trans('error_email_format')
            );
        }
        return $response;
    }

    /**
     * Determine if an newsletter entity already exist in the database
     *
     * @param $email
     * @return bool
     */
    private function isSubscribe($email){
        $newsletter = $this->em->getRepository('AppBundle:Newsletter')
            ->findOneBy(array('email' => $this->emailClean($email)));

        return (!$newsletter) ? false : true;
    }

    /**
     * Clear email before checking
     *
     * @param $email
     * @return string
     */
    private function emailClean($email){
        return  trim(strtolower($email));
    }

}

