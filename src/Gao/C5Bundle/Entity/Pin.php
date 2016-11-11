<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pin
 *
 * @ORM\Table(name="pin")
 * @ORM\Entity
 */
class Pin
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
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="pin_number", type="string", length=20, nullable=false)
     */
    private $pinNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="used", type="boolean", nullable=false)
     */
    private $used;

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
     * @return Pin
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
     * Set pinNumber
     *
     * @param string $pinNumber
     * @return Pin
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
     * Set used
     *
     * @param boolean $used
     * @return Pin
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set pdId
     *
     * @param integer $pdId
     * @return Pin
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
     * @return Pin
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
}
