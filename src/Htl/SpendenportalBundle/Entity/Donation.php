<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donation
 *
 * @ORM\Table(name="donation")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\DonationRepository")
 */
class Donation
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
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sent", type="datetime")
     */
    private $dateSent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_received", type="datetime")
     */
    private $dateReceived;

    /**
     * @var bool
     *
     * @ORM\Column(name="anonym", type="boolean")
     */
    private $anonym;

    /**
     * @var int
     *
     * @ORM\Column(name="users", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="donations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="projects", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="donations")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="projects")
     */
    private $projects;


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
     * Set amount
     *
     * @param string $amount
     *
     * @return Donation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateSent
     *
     * @param \DateTime $dateSent
     *
     * @return Donation
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;
    
        return $this;
    }

    /**
     * Get dateSent
     *
     * @return \DateTime
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * Set dateReceived
     *
     * @param \DateTime $dateReceived
     *
     * @return Donation
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = $dateReceived;
    
        return $this;
    }

    /**
     * Get dateReceived
     *
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * Set anonym
     *
     * @param boolean $anonym
     *
     * @return Donation
     */
    public function setAnonym($anonym)
    {
        $this->anonym = $anonym;
    
        return $this;
    }

    /**
     * Get anonym
     *
     * @return boolean
     */
    public function getAnonym()
    {
        return $this->anonym;
    }

    /**
     * Set users
     *
     * @param integer $users
     *
     * @return Follower
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return integer
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set projects
     *
     * @param integer $projects
     *
     * @return Donation
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;
    
        return $this;
    }

    /**
     * Get projects
     *
     * @return integer
     */
    public function getProjects()
    {
        return $this->projects;
    }
}

