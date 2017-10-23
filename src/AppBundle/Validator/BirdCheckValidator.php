<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class BirdCheckValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($name, Constraint $constraint)
    {
        $bird = $this->em->getRepository('AppBundle:Taxref')->findOneBy(array('common_name' => $name));

        if(!$bird || empty(trim($name))) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}

