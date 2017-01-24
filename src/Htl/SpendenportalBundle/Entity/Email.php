<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\EmailRepository")
 */
class Email
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="emailAdresse", type="string", length=255)
     */
    private $emailAdresse;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set emailAdresse
     *
     * @param string $emailAdresse
     *
     * @return Email
     */
    public function setEmailAdresse($emailAdresse)
    {
        $this->emailAdresse = $emailAdresse;

        return $this;
    }

    /**
     * Get emailAdresse
     *
     * @return string
     */
    public function getEmailAdresse()
    {
        return $this->emailAdresse;
    }
}

