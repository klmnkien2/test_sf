<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankAccLog
 *
 * @ORM\Table(name="bank_acc_log")
 * @ORM\Entity
 */
class BankAccLog
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
     * @var string
     *
     * @ORM\Column(name="vcb_acc_number", type="string", length=50, nullable=false)
     */
    private $vcbAccNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_gd", type="integer", nullable=true)
     */
    private $countGd;

    /**
     * @var integer
     *
     * @ORM\Column(name="count_pd", type="integer", nullable=true)
     */
    private $countPd;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_gd_amount", type="integer", nullable=true)
     */
    private $totalGdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_pd_amount", type="integer", nullable=true)
     */
    private $totalPdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_sent_amount", type="integer", nullable=true)
     */
    private $totalSentAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_receive_amount", type="integer", nullable=true)
     */
    private $totalReceiveAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
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
     * Set vcbAccNumber
     *
     * @param string $vcbAccNumber
     * @return BankAccLog
     */
    public function setVcbAccNumber($vcbAccNumber)
    {
        $this->vcbAccNumber = $vcbAccNumber;

        return $this;
    }

    /**
     * Get vcbAccNumber
     *
     * @return string 
     */
    public function getVcbAccNumber()
    {
        return $this->vcbAccNumber;
    }

    /**
     * Set countGd
     *
     * @param integer $countGd
     * @return BankAccLog
     */
    public function setCountGd($countGd)
    {
        $this->countGd = $countGd;

        return $this;
    }

    /**
     * Get countGd
     *
     * @return integer 
     */
    public function getCountGd()
    {
        return $this->countGd;
    }

    /**
     * Set countPd
     *
     * @param integer $countPd
     * @return BankAccLog
     */
    public function setCountPd($countPd)
    {
        $this->countPd = $countPd;

        return $this;
    }

    /**
     * Get countPd
     *
     * @return integer 
     */
    public function getCountPd()
    {
        return $this->countPd;
    }

    /**
     * Set totalGdAmount
     *
     * @param integer $totalGdAmount
     * @return BankAccLog
     */
    public function setTotalGdAmount($totalGdAmount)
    {
        $this->totalGdAmount = $totalGdAmount;

        return $this;
    }

    /**
     * Get totalGdAmount
     *
     * @return integer 
     */
    public function getTotalGdAmount()
    {
        return $this->totalGdAmount;
    }

    /**
     * Set totalPdAmount
     *
     * @param integer $totalPdAmount
     * @return BankAccLog
     */
    public function setTotalPdAmount($totalPdAmount)
    {
        $this->totalPdAmount = $totalPdAmount;

        return $this;
    }

    /**
     * Get totalPdAmount
     *
     * @return integer 
     */
    public function getTotalPdAmount()
    {
        return $this->totalPdAmount;
    }

    /**
     * Set totalSentAmount
     *
     * @param integer $totalSentAmount
     * @return BankAccLog
     */
    public function setTotalSentAmount($totalSentAmount)
    {
        $this->totalSentAmount = $totalSentAmount;

        return $this;
    }

    /**
     * Get totalSentAmount
     *
     * @return integer 
     */
    public function getTotalSentAmount()
    {
        return $this->totalSentAmount;
    }

    /**
     * Set totalReceiveAmount
     *
     * @param integer $totalReceiveAmount
     * @return BankAccLog
     */
    public function setTotalReceiveAmount($totalReceiveAmount)
    {
        $this->totalReceiveAmount = $totalReceiveAmount;

        return $this;
    }

    /**
     * Get totalReceiveAmount
     *
     * @return integer 
     */
    public function getTotalReceiveAmount()
    {
        return $this->totalReceiveAmount;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return BankAccLog
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
     * Set status
     *
     * @param boolean $status
     * @return BankAccLog
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
}
