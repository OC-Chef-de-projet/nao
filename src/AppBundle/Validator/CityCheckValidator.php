<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class CityCheckValidator extends ConstraintValidator
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($city, Constraint $constraint)
    {
        $city = $this->em->getRepository('AppBundle:FranceRegion')->findOneBy(array('city' => $city));

        if(!$city) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}