<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Feedback
{
    /**
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Regex( pattern="#^(?!-)[\p{L}- ]{2,20}[^\-]$#u", message="name_format")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Email( message = "email_invalid", checkMX = true)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length( min = 2, minMessage = "format_min_2")
     */
    private $object;

    /**
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length( min = 2, minMessage = "format_min_2")
     */
    private $message;

    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name){
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email){
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObject(){
        return $this->object;
    }

    /**
     * @param $object
     * @return $this
     */
    public function setObject($object){
        $this->object = $object;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage(){
        return $this->message;
    }

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message){
        $this->message = $message;

        return $this;
    }
}
