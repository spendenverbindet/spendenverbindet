<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Follower
 *
 * @ORM\Table(name="follower")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\FollowerRepository")
 */
class Follower
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
     * @var int
     *
     * @ORM\Column(name="users", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="followers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="users")
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="projects", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="followers")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="projects")
     */
    private $projects;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="followed_since", type="datetime")
     */
    private $followedSince;


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
     * @return Follower
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

    /**
     * Set followedSince
     *
     * @param \DateTime $followedSince
     *
     * @return Follower
     */
    public function setFollowedSince($followedSince)
    {
        $this->followedSince = $followedSince;
    
        return $this;
    }

    /**
     * Get followedSince
     *
     * @return \DateTime
     */
    public function getFollowedSince()
    {
        return $this->followedSince;
    }
}

