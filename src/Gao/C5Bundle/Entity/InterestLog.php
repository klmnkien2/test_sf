<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InterestLog
 *
 * @ORM\Table(name="interest_log")
 * @ORM\Entity
 */
class InterestLog
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
     * @ORM\Column(name="from_user_id", type="integer", nullable=false)
     */
    private $fromUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pd_amount", type="integer", nullable=false)
     */
    private $pdAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="interest_amount", type="integer", nullable=false)
     */
    private $interestAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="from_user_level", type="integer", nullable=false)
     */
    private $fromUserLevel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
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
     * Set fromUserId
     *
     * @param integer $fromUserId
     * @return InterestLog
     */
    public function setFromUserId($fromUserId)
    {
        $this->fromUserId = $fromUserId;

        return $this;
    }

    /**
     * Get fromUserId
     *
     * @return integer 
     */
    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    /**
     * Set pdAmount
     *
     * @param integer $pdAmount
     * @return InterestLog
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
     * Set interestAmount
     *
     * @param integer $interestAmount
     * @return InterestLog
     */
    public function setInterestAmount($interestAmount)
    {
        $this->interestAmount = $interestAmount;

        return $this;
    }

    /**
     * Get interestAmount
     *
     * @return integer 
     */
    public function getInterestAmount()
    {
        return $this->interestAmount;
    }

    /**
     * Set fromUserLevel
     *
     * @param integer $fromUserLevel
     * @return InterestLog
     */
    public function setFromUserLevel($fromUserLevel)
    {
        $this->fromUserLevel = $fromUserLevel;

        return $this;
    }

    /**
     * Get fromUserLevel
     *
     * @return integer 
     */
    public function getFromUserLevel()
    {
        return $this->fromUserLevel;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return InterestLog
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
