<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="title", type="string", length=60)
     */
    private $title;

    /**
     * @var sring
     * 
     * @ORM\Column(name="titlePictureUrl", type="string", nullable=true)
     */
    private $titlePictureUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="shortinfo", type="text", nullable=true)
     */
    private $shortinfo;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="target_amount", type="decimal", precision=10, scale=2)
     */
    private $targetAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="achieved_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $achievedAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="current_amount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $currentAmount;

    /**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="projects")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $categorys;


    /**
     * @ORM\OneToMany(targetEntity="Follower", mappedBy="projects")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="Report", mappedBy="projects")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="Donation", mappedBy="projects")
     */
    private $donations;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="projects")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="projects")
     */
    private $pictures;

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
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titlePictureUrl
     *
     * @param string titlePictureUrl
     *
     * @return Project
     */
    public function setTitlePictureUrl($titlePictureUrl)
    {
        $this->title = $titlePictureUrl;

        return $this;
    }

    /**
     * Get titlePictureUrl
     *
     * @return string
     */
    public function getTitlePictureUrl()
    {
        return $this->titlePictureUrl;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set shortinfo
     *
     * @param string $shortinfo
     *
     * @return Project
     */
    public function setShortinfo($shortinfo)
    {
        $this->shortinfo = $shortinfo;
    
        return $this;
    }

    /**
     * Get shortinfo
     *
     * @return string
     */
    public function getShortinfo()
    {
        return $this->shortinfo;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Project
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Project
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set targetAmount
     *
     * @param string $targetAmount
     *
     * @return ProjectAmount
     */
    public function setTargetAmount($targetAmount)
    {
        $this->targetAmount = $targetAmount;

        return $this;
    }

    /**
     * Get targetAmount
     *
     * @return string
     */
    public function getTargetAmount()
    {
        return $this->targetAmount;
    }

    /**
     * Set achievedAmount
     *
     * @param string $achievedAmount
     *
     * @return ProjectAmount
     */
    public function setAchievedAmount($achievedAmount)
    {
        $this->achievedAmount = $achievedAmount;

        return $this;
    }

    /**
     * Get achievedAmount
     *
     * @return string
     */
    public function getAchievedAmount()
    {
        return $this->achievedAmount;
    }

    /**
     * Set currentAmount
     *
     * @param string $currentAmount
     *
     * @return ProjectAmount
     */
    public function setCurrentAmount($currentAmount)
    {
        $this->currentAmount = $currentAmount;

        return $this;
    }

    /**
     * Get currentAmount
     *
     * @return string
     */
    public function getCurrentAmount()
    {
        return $this->currentAmount;
    }

    /**
     * Set users
     *
     * @param integer $users
     *
     * @return Project
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
     * Set categorys
     *
     * @param integer $categorys
     *
     * @return Project
     */
    public function setCategory($categorys)
    {
        $this->categorys = $categorys;
    
        return $this;
    }

    /**
     * Get categorys
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->categorys;
    }

    /**
     * Set followers
     *
     * @param integer $followers
     *
     * @return Project
     */
    public function setFollowers($followers)
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
     * Set reports
     *
     * @param integer $reports
     *
     * @return Project
     */
    public function setReports($reports)
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
     * Set donations
     *
     * @param integer $donations
     *
     * @return Project
     */
    public function setDonations($donations)
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
     * Set posts
     *
     * @param integer $posts
     *
     * @return Project
     */
    public function setPost($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get posts
     *
     * @return integer
     */
    public function getPost()
    {
        return $this->posts;
    }

    /**
     * Set picture
     *
     * @param integer $picture
     *
     * @return Project
     */
    public function setPicture($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Get picture
     *
     * @return integer
     */
    public function getPicture()
    {
        return $this->pictures;
    }
}

