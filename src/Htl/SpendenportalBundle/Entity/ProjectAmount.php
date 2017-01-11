<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectAmount
 *
 * @ORM\Table(name="project_amount")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\ProjectAmountRepository")
 */
class ProjectAmount
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
     * @ORM\Column(name="target_amount", type="decimal", precision=10, scale=2)
     */
    private $targetAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="achieved_amount", type="decimal", precision=10, scale=2)
     */
    private $achievedAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="current_amount", type="decimal", precision=10, scale=2)
     */
    private $currentAmount;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="Project", mappedBy="projectamount")
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

