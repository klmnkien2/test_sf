<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity
 */
class Transaction
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
     * @var integer
     *
     * @ORM\Column(name="pd_user_id", type="integer", nullable=true)
     */
    private $pdUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="gd_user_id", type="integer", nullable=true)
     */
    private $gdUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="gd_acc_number", type="string", length=50, nullable=true)
     */
    private $gdAccNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="pd_acc_number", type="string", length=50, nullable=true)
     */
    private $pdAccNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date", type="datetime", nullable=true)
     */
    private $approvedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;



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
     * Set pdId
     *
     * @param integer $pdId
     * @return Transaction
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
     * @return Transaction
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
     * Set pdUserId
     *
     * @param integer $pdUserId
     * @return Transaction
     */
    public function setPdUserId($pdUserId)
    {
        $this->pdUserId = $pdUserId;

        return $this;
    }

    /**
     * Get pdUserId
     *
     * @return integer 
     */
    public function getPdUserId()
    {
        return $this->pdUserId;
    }

    /**
     * Set gdUserId
     *
     * @param integer $gdUserId
     * @return Transaction
     */
    public function setGdUserId($gdUserId)
    {
        $this->gdUserId = $gdUserId;

        return $this;
    }

    /**
     * Get gdUserId
     *
     * @return integer 
     */
    public function getGdUserId()
    {
        return $this->gdUserId;
    }

    /**
     * Set gdAccNumber
     *
     * @param string $gdAccNumber
     * @return Transaction
     */
    public function setGdAccNumber($gdAccNumber)
    {
        $this->gdAccNumber = $gdAccNumber;

        return $this;
    }

    /**
     * Get gdAccNumber
     *
     * @return string 
     */
    public function getGdAccNumber()
    {
        return $this->gdAccNumber;
    }

    /**
     * Set pdAccNumber
     *
     * @param string $pdAccNumber
     * @return Transaction
     */
    public function setPdAccNumber($pdAccNumber)
    {
        $this->pdAccNumber = $pdAccNumber;

        return $this;
    }

    /**
     * Get pdAccNumber
     *
     * @return string 
     */
    public function getPdAccNumber()
    {
        return $this->pdAccNumber;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Transaction
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
     * Set approvedDate
     *
     * @param \DateTime $approvedDate
     * @return Transaction
     */
    public function setApprovedDate($approvedDate)
    {
        $this->approvedDate = $approvedDate;

        return $this;
    }

    /**
     * Get approvedDate
     *
     * @return \DateTime 
     */
    public function getApprovedDate()
    {
        return $this->approvedDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
