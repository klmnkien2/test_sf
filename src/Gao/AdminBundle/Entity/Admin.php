<?php

namespace Gao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Admin
 *
 * @ORM\Entity(repositoryClass="Gao\AdminBundle\Entity\AdminRepository")
 * @ORM\Table(name="admin")
 * @ORM\Entity
 */
class Admin implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="creator_id", type="integer", nullable=true)
     */
    private $creatorId;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="role", type="string", length=100, nullable=true)
     */
    private $role;

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
     * @var \DateTime
     *
     * @ORM\Column(name="current_login", type="datetime", nullable=true)
     */
    private $currentLogin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_verified", type="boolean", nullable=true)
     */
    private $emailVerified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=true)
     */
    private $blocked;



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
     * Set creatorId
     *
     * @param integer $creatorId
     * @return Admin
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
     * @return Admin
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
     * Set phone
     *
     * @param string $phone
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * Set role
     *
     * @param string $role
     * @return Admin
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Admin
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
     * @return Admin
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
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set currentLogin
     *
     * @param \DateTime $currentLogin
     * @return Admin
     */
    public function setCurrentLogin($currentLogin)
    {
        $this->currentLogin = $currentLogin;

        return $this;
    }

    /**
     * Get currentLogin
     *
     * @return \DateTime 
     */
    public function getCurrentLogin()
    {
        return $this->currentLogin;
    }

    /**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     * @return Admin
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
     * Set blocked
     *
     * @param boolean $blocked
     * @return Admin
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
     * Implement security function
     */
    public function getRoles()
    {
        return array($this->role);
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
            $this->blocked
        ));
    }
    
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->emailVerified,
            $this->blocked
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
