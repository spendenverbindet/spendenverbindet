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

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=40)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=60)
     */
    private $lastname;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

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
     * @ORM\Column(name="housenumber", type="string", length=20, nullable=true)
     */
    private $housenumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reg_date", type="datetime")
     */
    private $regDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime")
     */
    private $expiresAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked;

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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * Set street
     *
     * @param string $street
     *
     * @return Person
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
     * @return Person
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
     * Set housenumber
     *
     * @param string $housenumber
     *
     * @return Person
     */
    public function setHousenumber($housenumber)
    {
        $this->housenumber = $housenumber;

        return $this;
    }

    /**
     * Get housenumber
     *
     * @return string
     */
    public function getHousenumber()
    {
        return $this->housenumber;
    }

    /**
     * Set regDate
     *
     * @param \DateTime $regDate
     *
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return Person
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get persons
     *
     * @return integer
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
