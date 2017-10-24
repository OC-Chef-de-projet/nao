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
        $region = explode('-', $city);

        if(!empty($city) && isset($region[1])){
            dump($region);
            $city = $this->em->getRepository('AppBundle:FranceRegion')->findOneBy(array('city' => trim($region[1])));

            if(!$city) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }else{
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
