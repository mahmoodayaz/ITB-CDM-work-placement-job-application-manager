<?php
/*
    Name: Mehmood Ayaz;
    Course: Web Software Engineering
    Project: ITB CDM work placement job application manager
*/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * comment_all
 *
 * @ORM\Table(name="comment_all")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\comment_allRepository")
 */
class comment_all
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
     * @ORM\Column(name="lecturer_id", type="integer")
     */
    private $lecturerId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

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
     * Set lecturerId
     *
     * @param integer $lecturerId
     *
     * @return comment_all
     */
    public function setLecturerId($lecturerId)
    {
        $this->lecturerId = $lecturerId;

        return $this;
    }

    /**
     * Get lecturerId
     *
     * @return int
     */
    public function getLecturerId()
    {
        return $this->lecturerId;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return comment_all
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return comment_all
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return comment_all
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

