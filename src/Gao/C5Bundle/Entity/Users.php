<?php

namespace Gao\C5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Users
 *
 * @ORM\Entity(repositoryClass="Gao\C5Bundle\Entity\UsersRepository")
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="ref_id", type="integer", nullable=false)
     */
    private $refId;

    /**
     * @var integer
     *
     * @ORM\Column(name="creator_id", type="integer", nullable=false)
     */
    private $creatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="vcb_acc_number", type="string", length=50, nullable=false)
     */
    private $vcbAccNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=1000, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=100, nullable=false)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_verified", type="boolean", nullable=false)
     */
    private $emailVerified;

    /**
     * @var integer
     *
     * @ORM\Column(name="first_pd_done", type="integer", nullable=false)
     */
    private $firstPdDone;

    /**
     * @var string
     *
     * @ORM\Column(name="pd_gd_state", type="string", length=20, nullable=false)
     */
    private $pdGdState;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_state_update", type="datetime", nullable=false)
     */
    private $lastStateUpdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="pd_count", type="integer", nullable=false)
     */
    private $pdCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="pd_total", type="integer", nullable=false)
     */
    private $pdTotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="gd_count", type="integer", nullable=false)
     */
    private $gdCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="gd_total", type="integer", nullable=false)
     */
    private $gdTotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="outstanding_pd", type="integer", nullable=false)
     */
    private $outstandingPd;

    /**
     * @var integer
     *
     * @ORM\Column(name="outstanding_gd", type="integer", nullable=false)
     */
    private $outstandingGd;

    /**
     * @var boolean
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=false)
     */
    private $blocked;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_interest_rate", type="integer", nullable=false)
     */
    private $currentInterestRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="c_level", type="integer", nullable=false)
     */
    private $cLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="outstanding_ref_amount", type="integer", nullable=false)
     */
    private $outstandingRefAmount;



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
     * Set refId
     *
     * @param integer $refId
     * @return Users
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;

        return $this;
    }

    /**
     * Get refId
     *
     * @return integer 
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * Set creatorId
     *
     * @param integer $creatorId
     * @return Users
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * Get creatorId
     *
     * @return integer 
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return Users
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set vcbAccNumber
     *
     * @param string $vcbAccNumber
     * @return Users
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
     * Set phone
     *
     * @param string $phone
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return Users
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getHash()
    {
        return $this->lastLogin;
    }

    /**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     * @return Users
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    /**
     * Get emailVerified
     *
     * @return boolean 
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * Set firstPdDone
     *
     * @param integer $firstPdDone
     * @return Users
     */
    public function setFirstPdDone($firstPdDone)
    {
        $this->firstPdDone = $firstPdDone;

        return $this;
    }

    /**
     * Get firstPdDone
     *
     * @return integer 
     */
    public function getFirstPdDone()
    {
        return $this->firstPdDone;
    }

    /**
     * Set pdGdState
     *
     * @param string $pdGdState
     * @return Users
     */
    public function setPdGdState($pdGdState)
    {
        $this->pdGdState = $pdGdState;

        return $this;
    }

    /**
     * Get pdGdState
     *
     * @return string 
     */
    public function getPdGdState()
    {
        return $this->pdGdState;
    }

    /**
     * Set lastStateUpdate
     *
     * @param \DateTime $lastStateUpdate
     * @return Users
     */
    public function setLastStateUpdate($lastStateUpdate)
    {
        $this->lastStateUpdate = $lastStateUpdate;

        return $this;
    }

    /**
     * Get lastStateUpdate
     *
     * @return \DateTime 
     */
    public function getLastStateUpdate()
    {
        return $this->lastStateUpdate;
    }

    /**
     * Set pdCount
     *
     * @param integer $pdCount
     * @return Users
     */
    public function setPdCount($pdCount)
    {
        $this->pdCount = $pdCount;

        return $this;
    }

    /**
     * Get pdCount
     *
     * @return integer 
     */
    public function getPdCount()
    {
        return $this->pdCount;
    }

    /**
     * Set pdTotal
     *
     * @param integer $pdTotal
     * @return Users
     */
    public function setPdTotal($pdTotal)
    {
        $this->pdTotal = $pdTotal;

        return $this;
    }

    /**
     * Get pdTotal
     *
     * @return integer 
     */
    public function getPdTotal()
    {
        return $this->pdTotal;
    }

    /**
     * Set gdCount
     *
     * @param integer $gdCount
     * @return Users
     */
    public function setGdCount($gdCount)
    {
        $this->gdCount = $gdCount;

        return $this;
    }

    /**
     * Get gdCount
     *
     * @return integer 
     */
    public function getGdCount()
    {
        return $this->gdCount;
    }

    /**
     * Set gdTotal
     *
     * @param integer $gdTotal
     * @return Users
     */
    public function setGdTotal($gdTotal)
    {
        $this->gdTotal = $gdTotal;

        return $this;
    }

    /**
     * Get gdTotal
     *
     * @return integer 
     */
    public function getGdTotal()
    {
        return $this->gdTotal;
    }

    /**
     * Set outstandingPd
     *
     * @param integer $outstandingPd
     * @return Users
     */
    public function setOutstandingPd($outstandingPd)
    {
        $this->outstandingPd = $outstandingPd;

        return $this;
    }

    /**
     * Get outstandingPd
     *
     * @return integer 
     */
    public function getOutstandingPd()
    {
        return $this->outstandingPd;
    }

    /**
     * Set outstandingGd
     *
     * @param integer $outstandingGd
     * @return Users
     */
    public function setOutstandingGd($outstandingGd)
    {
        $this->outstandingGd = $outstandingGd;

        return $this;
    }

    /**
     * Get outstandingGd
     *
     * @return integer 
     */
    public function getOutstandingGd()
    {
        return $this->outstandingGd;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     * @return Users
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * Get blocked
     *
     * @return boolean 
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set currentInterestRate
     *
     * @param integer $currentInterestRate
     * @return Users
     */
    public function setCurrentInterestRate($currentInterestRate)
    {
        $this->currentInterestRate = $currentInterestRate;

        return $this;
    }

    /**
     * Get currentInterestRate
     *
     * @return integer 
     */
    public function getCurrentInterestRate()
    {
        return $this->currentInterestRate;
    }

    /**
     * Set cLevel
     *
     * @param integer $cLevel
     * @return Users
     */
    public function setCLevel($cLevel)
    {
        $this->cLevel = $cLevel;

        return $this;
    }

    /**
     * Get cLevel
     *
     * @return integer 
     */
    public function getCLevel()
    {
        return $this->cLevel;
    }

    /**
     * Set outstandingRefAmount
     *
     * @param integer $outstandingRefAmount
     * @return Users
     */
    public function setOutstandingRefAmount($outstandingRefAmount)
    {
        $this->outstandingRefAmount = $outstandingRefAmount;

        return $this;
    }

    /**
     * Get outstandingRefAmount
     *
     * @return integer 
     */
    public function getOutstandingRefAmount()
    {
        return $this->outstandingRefAmount;
    }

    /**
     * Implement security function
     */
//     Defined
//     public function getUsername()
//     {
//         return $this->username;
//     }

//     Defined
//     public function getSalt()
//     {
//         // you *may* need a real salt depending on your encoder
//         // see section on salt below
//         return null;
//     }

//     Defined
//     public function getPassword()
//     {
//         return $this->password;
//     }
    
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->emailVerified,
            $this->blocked,
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->emailVerified,
            $this->blocked,
            // $this->salt
        ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return !($this->blocked);
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->emailVerified;
    }
}
