<?php

namespace Htl\SpendenportalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity(repositoryClass="Htl\SpendenportalBundle\Repository\ReportRepository")
 */
class Report
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="reports")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $projects;

    /**
     * @var \String
     *
     * @ORM\Column(name="reportText", type="string")
     */
    private $reportText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reported_at", type="datetime")
     */
    private $reportedAt;

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
     * @return Report
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
        return $this->donators;
    }

    /**
     * Set projects
     *
     * @param integer $projects
     *
     * @return Report
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
     * Set reportText
     *
     * @param \String $reportText
     *
     * @return Report
     */
    public function setReportText($reportText)
    {
        $this->reportText = $reportText;

        return $this;
    }

    /**
     * Get reportText
     *
     * @return \String
     */
    public function getReportText()
    {
        return $this->reportText;
    }

    /**
     * Set reportedAt
     *
     * @param \DateTime $reportedAt
     *
     * @return Report
     */
    public function setReportedAt($reportedAt)
    {
        $this->reportedAt = $reportedAt;
    
        return $this;
    }

    /**
     * Get reportedAt
     *
     * @return \DateTime
     */
    public function getReportedAt()
    {
        return $this->reportedAt;
    }
    
}

