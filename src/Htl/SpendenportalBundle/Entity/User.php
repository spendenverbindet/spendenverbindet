<?php
// src/Htl/SpendenportalBundle/Entity/User.php

namespace Htl\SpendenportalBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\NotBlank(message="Please enter if you are Spender or Empfaenger", groups={"Registration", "Profile"})
     */
    protected $isDonator = false;


    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your mobilpass-number.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=10,
     *     max=10,
     *     minMessage="The number is too short.",
     *     maxMessage="The number is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $mobil_pass_number;


    

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set isDonator
     *
     * @param boolean $isDonator
     *
     * @return User
     */
    public function setIsDonator($isDonator)
    {
        $this->isDonator = $isDonator;

        return $this;
    }

    /**
     * Get isDonator
     *
     * @return boolean
     */
    public function getIsDonator()
    {
        return $this->isDonator;
    }

    /**
     * Set mobilPassNumber
     *
     * @param string $mobilPassNumber
     *
     * @return User
     */
    public function setMobilPassNumber($mobilPassNumber)
    {
        $this->mobil_pass_number = $mobilPassNumber;

        return $this;
    }

    /**
     * Get mobilPassNumber
     *
     * @return string
     */
    public function getMobilPassNumber()
    {
        return $this->mobil_pass_number;
    }
}
