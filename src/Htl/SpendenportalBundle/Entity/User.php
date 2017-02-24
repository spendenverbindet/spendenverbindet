<?php
// src/Htl/SpendenportalBundle/Entity/User.php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(name="BeduerftigkeitsbeweisFile", type="string", nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $fileUpload;


    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=60)
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(name="year_of_birth", type="string")
     * @Assert\DateTime()
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", nullable=true)
     */
    private $town;
    
    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=20, nullable=true)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="housenumber_doornumber", type="string", length=20, nullable=true)
     */
    
    private $housenumberDoornumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reg_date", type="datetime", nullable=true)
     */
    private $regDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked = true;

    /**
     * @ORM\OneToMany(targetEntity="Report", mappedBy="users")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="Donation", mappedBy="users")
     */
    private $donations;

    /**
     * @ORM\OneToMany(targetEntity="Follower", mappedBy="users")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="users")
     */
    private $projects;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->projects = new ArrayCollection();
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
     * Get fileUpload
     *
     * @return string
     */
    public function getFileUpload()
    {
        return $this->fileUpload;
    }

    /**
     * Set fileUpload
     *
     * @param string $fileUpload
     *
     * @return User
     */
    public function setFileUpload($fileUpload)
    {
        $this->fileUpload = $fileUpload;

        return $this;
    }
    
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }
    
    /**
     * Set town
     *
     * @param string $town
     *
     * @return User
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }
    
    /**
     * Set street
     *
     * @param string $street
     *
     * @return User
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set housenumberDoornumber
     *
     * @param string $housenumberDoornumber
     *
     * @return User
     */
    public function setHousenumberDoornumber($housenumberDoornumber)
    {
        $this->housenumberDoornumber = $housenumberDoornumber;

        return $this;
    }

    /**
     * Get housenumberDoornumber
     *
     * @return string
     */
    public function getHousenumberDoornumber()
    {
        return $this->housenumberDoornumber;
    }

    /**
     * Set regDate
     *
     * @param \DateTime $regDate
     *
     * @return User
     */
    public function setRegDate($regDate)
    {
        $this->regDate = $regDate;

        return $this;
    }

    /**
     * Get regDate
     *
     * @return \DateTime
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     *
     * @return User
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set reports
     *
     * @param integer $reports
     *
     * @return User
     */
    public function setReport($reports)
    {
        $this->reports = $reports;

        return $this;
    }

    /**
     * Get reports
     *
     * @return integer
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Set donation
     *
     * @param integer $donations
     *
     * @return User
     */
    public function setDonation($donations)
    {
        $this->donations = $donations;

        return $this;
    }

    /**
     * Get donations
     *
     * @return integer
     */
    public function getDonations()
    {
        return $this->donations;
    }

    /**
     * Set followers
     *
     * @param integer $followers
     *
     * @return User
     */
    public function setFollower($followers)
    {
        $this->followers = $followers;

        return $this;
    }

    /**
     * Get followers
     *
     * @return integer
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set projects
     *
     * @param integer $projects
     *
     * @return User
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
