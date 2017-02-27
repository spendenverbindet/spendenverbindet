<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\PostRepository")
 */
class Post
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
     * @var sring
     *
     * @ORM\Column(name="picture", type="string")
     */
    private $postPictureUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="post_title", type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="post_text", type="text")
     */
    private $postText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="posts")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
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
     * Set postPictureUrl
     *
     * @param string postPictureUrl
     *
     * @return Project
     */
    public function setPostPictureUrl($postPictureUrl)
    {
        $this->postPictureUrl = $postPictureUrl;

        return $this;
    }

    /**
     * Get titlePictureUrl
     *
     * @return string
     */
    public function getPostPictureUrl()
    {
        return $this->postPictureUrl;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * Set postText
     *
     * @param string $postText
     *
     * @return Post
     */
    public function setPostText($postText)
    {
        $this->postText = $postText;
    
        return $this;
    }

    /**
     * Get postText
     *
     * @return string
     */
    public function getPostText()
    {
        return $this->postText;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
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
     * Set projects
     *
     * @param int $projects
     *
     * @return Category
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get projects
     *
     * @return int
     */
    public function getProjects()
    {
        return $this->projects;
    }
}

