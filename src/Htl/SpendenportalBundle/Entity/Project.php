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
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;

    /**
     * @var int
     *
     * @ORM\Column(name="users", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="users")
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="categorys", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="projects")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="categorys")
     */
    private $categorys;

    /**
     * @var int
     *
     * @ORM\Column(name="projectamounts", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="Projectamount", inversedBy="projects")
     * @ORM\JoinColumn(name="projectAmount_id", referencedColumnName="projectamounts")
     */
    private $projectamounts;

    /**
     * @var int
     *
     * @ORM\Column(name="followers", type="integer")
     *
     * @ORM\OneToMany(targetEntity="Follower", mappedBy="project")
     */
    private $followers;

    /**
     * @var int
     *
     * @ORM\Column(name="reports", type="integer")
     *
     * @ORM\OneToMany(targetEntity="Report", mappedBy="project")
     */
    private $reports;

    /**
     * @var int
     *
     * @ORM\Column(name="donations", type="integer")
     *
     * @ORM\OneToMany(targetEntity="Donation", mappedBy="project")
     */
    private $donations;

    /**
     * @var int
     *
     * @ORM\Column(name="posts", type="integer")
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="project")
     */
    private $post;

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
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Project
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    
        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
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
     * Set projectamounts
     *
     * @param integer $projectamounts
     *
     * @return Project
     */
    public function setFkProjectProjectAmountID($projectamounts)
    {
        $this->projectamounts = $projectamounts;
    
        return $this;
    }

    /**
     * Get projectamounts
     *
     * @return integer
     */
    public function getProjectamount()
    {
        return $this->projectamounts;
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
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get posts
     *
     * @return integer
     */
    public function getPosts()
    {
        return $this->posts;
    }
}

