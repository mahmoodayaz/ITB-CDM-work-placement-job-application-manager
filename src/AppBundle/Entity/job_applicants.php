<?php
/*
    Name: Mehmood Ayaz;
    Course: Web Software Engineering
    Project: ITB CDM work placement job application manager
*/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * job_applicants
 *
 * @ORM\Table(name="job_applicants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\job_applicantsRepository")
 */
class job_applicants
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
     * @ORM\Column(name="job_id", type="integer")
     */
    private $jobId;

    /**
     * @var int
     *
     * @ORM\Column(name="student_id", type="integer")
     */
    private $studentId;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz")
     */
    private $date;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set jobId
     *
     * @param integer $jobId
     *
     * @return job_applicants
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get jobId
     *
     * @return int
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * Set studentId
     *
     * @param integer $studentId
     *
     * @return job_applicants
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId
     *
     * @return int
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return job_applicants
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return job_applicants
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

