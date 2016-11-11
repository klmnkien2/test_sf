<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pd
 *
 * @ORM\Table(name="pd")
 * @ORM\Entity
 */
class Pd
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
     * @ORM\Column(name="pd_amount", type="integer", nullable=false)
     */
    private $pdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="applied_interest_rate", type="integer", nullable=false)
     */
    private $appliedInterestRate;

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
     * @return Pd
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
     * Set pdAmount
     *
     * @param integer $pdAmount
     * @return Pd
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
     * Set appliedInterestRate
     *
     * @param integer $appliedInterestRate
     * @return Pd
     */
    public function setAppliedInterestRate($appliedInterestRate)
    {
        $this->appliedInterestRate = $appliedInterestRate;

        return $this;
    }

    /**
     * Get appliedInterestRate
     *
     * @return integer 
     */
    public function getAppliedInterestRate()
    {
        return $this->appliedInterestRate;
    }

    /**
     * Set pinId
     *
     * @param integer $pinId
     * @return Pd
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
     * @return Pd
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
     * @return Pd
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
     * @return Pd
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
