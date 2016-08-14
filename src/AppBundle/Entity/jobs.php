<?php
/*
    Name: Mehmood Ayaz;
    Course: Web Software Engineering
    Project: ITB CDM work placement job application manager
*/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * jobs
 *
 * @ORM\Table(name="jobs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\jobsRepository")
 */
class jobs
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetimetz")
     */
    private $deadline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetimetz")
     */
    private $dateCreated;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="employer_id", type="integer")
     */
    private $employerId;


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
     * Set title
     *
     * @param string $title
     *
     * @return jobs
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
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return jobs
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return jobs
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return jobs
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
     * Set employerId
     *
     * @param integer $employerId
     *
     * @return jobs
     */
    public function setEmployerId($employerId)
    {
        $this->employerId = $employerId;

        return $this;
    }

    /**
     * Get employerId
     *
     * @return int
     */
    public function getEmployerId()
    {
        return $this->employerId;
    }
}

