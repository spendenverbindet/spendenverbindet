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
     * @var int
     *
     * @ORM\Column(name="users", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $users;

    /**
     * @var int
     *
     * @ORM\Column(name="projects", type="integer")
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="reports")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="projects")
     */
    private $projects;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reported_at", type="datetime")
     */
    private $reportedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="amount_reports", type="smallint")
     */
    private $amountReports;


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

    /**
     * Set amountReports
     *
     * @param integer $amountReports
     *
     * @return Report
     */
    public function setAmountReports($amountReports)
    {
        $this->amountReports = $amountReports;
    
        return $this;
    }

    /**
     * Get amountReports
     *
     * @return integer
     */
    public function getAmountReports()
    {
        return $this->amountReports;
    }
}

