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
    public function __construct(EntityManager $em, TranslatorInterface $translator, $mailchimp){
        $this->em           = $em;
        $this->translator   = $translator;
        $this->mailchimp    = $mailchimp;
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

                // subscribe to mailChimp
                $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $this->emailClean($email)));

                if($user) {
                    $this->mailchimp->subscribeToList($user->getEmail(), $user->getName());
                }else
                {
                    $this->mailchimp->subscribeToList($email);
                }

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
     * Unsubscribe to newsletter
     *
     * @param $email
     */
    public function unsubscribe($email){
        $email = $this->emailClean($email);

        // Verify email address first
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $newsletter = $this->em->getRepository('AppBundle:Newsletter')
                ->findOneBy(array('email' => $this->emailClean($email)));

            if($newsletter){
                $this->em->remove($newsletter);
                $this->em->flush();
                $this->mailchimp->unsubscribeToList($email);
            }
        }
    }

    /**
     * Determine if an newsletter entity already exist in the database
     *
     * @param $email
     * @return bool
     */
    public function isSubscribe($email){
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

