<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dispute
 *
 * @ORM\Table(name="dispute", indexes={@ORM\Index(name="message", columns={"message"})})
 * @ORM\Entity
 */
class Dispute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pd_id", type="integer", nullable=true)
     */
    private $pdId;

    /**
     * @var integer
     *
     * @ORM\Column(name="gd_id", type="integer", nullable=true)
     */
    private $gdId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     */
    private $message;



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
     * Set userId
     *
     * @param integer $userId
     * @return Dispute
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set pdId
     *
     * @param integer $pdId
     * @return Dispute
     */
    public function setPdId($pdId)
    {
        $this->pdId = $pdId;

        return $this;
    }

    /**
     * Get pdId
     *
     * @return integer 
     */
    public function getPdId()
    {
        return $this->pdId;
    }

    /**
     * Set gdId
     *
     * @param integer $gdId
     * @return Dispute
     */
    public function setGdId($gdId)
    {
        $this->gdId = $gdId;

        return $this;
    }

    /**
     * Get gdId
     *
     * @return integer 
     */
    public function getGdId()
    {
        return $this->gdId;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Dispute
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Dispute
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Dispute
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
}
