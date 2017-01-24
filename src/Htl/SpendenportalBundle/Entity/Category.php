<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="category_text", type="string", length=255)
     */
    private $categoryText;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="categorys")
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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
     * Set categoryText
     *
     * @param string $categoryText
     *
     * @return Category
     */
    public function setCategoryText($categoryText)
    {
        $this->categoryText = $categoryText;
    
        return $this;
    }

    /**
     * Get categoryText
     *
     * @return string
     */
    public function getCategoryText()
    {
        return $this->categoryText;
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

