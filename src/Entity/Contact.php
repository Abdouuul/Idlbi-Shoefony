<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Contact{
    /**
     * 
     * 
     * @Assert\NotBlank(message="Veuillez renseigner votre prénom.")
     */
    private ?string $firstName = null;

    /**
     * 
     * 
     * @Assert\NotBlank(message="Veuillez renseigner votre nom.")
     */
    private ?string $lastName = null;

    /**
     * 
     * @Assert\NotBlank(message="Veuillez renseigner votre email.")
     * @Assert\Email(message="L'email {{ value }} n'est pas valide.")
     */
    private ?string $email = null;

    /**
     * 
     * @Assert\NotBlank(message="Veuillez renseigner votre message.")
     * @Assert\Length(min=25, minMessage="Votre message doit faire au moins {{ limit }} caractères")
     */
    private ?string $message = null;


    

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}