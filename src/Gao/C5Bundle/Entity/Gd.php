<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gd
 *
 * @ORM\Table(name="gd")
 * @ORM\Entity
 */
class Gd
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
     * @ORM\Column(name="pd_id", type="integer", nullable=false)
     */
    private $pdId;

    /**
     * @var integer
     *
     * @ORM\Column(name="gd_amount", type="integer", nullable=true)
     */
    private $gdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="pd_amount", type="integer", nullable=true)
     */
    private $pdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="ref_amount", type="integer", nullable=true)
     */
    private $refAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="pin_id", type="integer", nullable=false)
     */
    private $pinId;

    /**
     * @var string
     *
     * @ORM\Column(name="pin_number", type="string", length=20, nullable=false)
     */
    private $pinNumber;

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
     * @return Gd
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
     * @return Gd
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
     * Set gdAmount
     *
     * @param integer $gdAmount
     * @return Gd
     */
    public function setGdAmount($gdAmount)
    {
        $this->gdAmount = $gdAmount;

        return $this;
    }

    /**
     * Get gdAmount
     *
     * @return integer 
     */
    public function getGdAmount()
    {
        return $this->gdAmount;
    }

    /**
     * Set pdAmount
     *
     * @param integer $pdAmount
     * @return Gd
     */
    public function setPdAmount($pdAmount)
    {
        $this->pdAmount = $pdAmount;

        return $this;
    }

    /**
     * Get pdAmount
     *
     * @return integer 
     */
    public function getPdAmount()
    {
        return $this->pdAmount;
    }

    /**
     * Set refAmount
     *
     * @param integer $refAmount
     * @return Gd
     */
    public function setRefAmount($refAmount)
    {
        $this->refAmount = $refAmount;

        return $this;
    }

    /**
     * Get refAmount
     *
     * @return integer 
     */
    public function getRefAmount()
    {
        return $this->refAmount;
    }

    /**
     * Set pinId
     *
     * @param integer $pinId
     * @return Gd
     */
    public function setPinId($pinId)
    {
        $this->pinId = $pinId;

        return $this;
    }

    /**
     * Get pinId
     *
     * @return integer 
     */
    public function getPinId()
    {
        return $this->pinId;
    }

    /**
     * Set pinNumber
     *
     * @param string $pinNumber
     * @return Gd
     */
    public function setPinNumber($pinNumber)
    {
        $this->pinNumber = $pinNumber;

        return $this;
    }

    /**
     * Get pinNumber
     *
     * @return string 
     */
    public function getPinNumber()
    {
        return $this->pinNumber;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Gd
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
     * @return Gd
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
}
