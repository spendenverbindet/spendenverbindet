<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    //const SERVER_PATH_TO_IMAGE_FOLDER = '/web/bundles/htlspendenportal/img';

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string")
     */
    private $pictureUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="pictures")
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
     * Set pictureUrl
     *
     * @param string pictureUrl
     *
     * @return Project
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    /**
     * Get pictureUrl
     *
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
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

    /*
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getPictureUrl()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        $this->getPictureUrl()->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER,$this->getPictureUrl()
        );

        // set the path property to the filename where you've saved the file
        $this->filename = $this->getPictureUrl();

        // clean up the file property as you won't need it anymore
        $this->setPictureUrl(null);
    }
    */
}

