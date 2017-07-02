<?php

namespace AppBundle\Entity;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinTable;
use AppBundle\Entity\GroupActivity;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer implements AdvancedUserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, unique=false)
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, unique=false)
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="patronym", type="string", length=255, unique=false)
     */
    private $patronym;

    /**
     * @var date
     *
     * @ORM\Column(name="birth_date", type="date", unique=false)
     */
    private $birthDate;

    /**
     * @var enum
     *
     * @ORM\Column(name="gender", type="string", columnDefinition="enum('male', 'female')")
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=20)
     */
    private $phoneNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="isLocked", type="boolean")
     */
    private $isLocked;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * Many Customers have Many Group Activities.
     * @ManyToMany(targetEntity="GroupActivity", inversedBy="customers")
     * @JoinTable(name="customers_groupactivities")
     */
    private $groupActivities;

    public function __construct()
    {
        $this->isActive = false;
        $this->isLocked = false;
        $this->groups = new ArrayCollection();
    }

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
     * Set email
     *
     * @param string $email
     *
     * @return Customer
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
     * Set password
     *
     * @param string $password
     *
     * @return Customer
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
     * Set gender
     *
     * @param \enum $gender
     * @return Customer
     */
    public function setGender(\enum $gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return \enum
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Customer
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isLocked
     *
     * @param boolean $isLocked
     *
     * @return Customer
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * Get isLocked
     *
     * @return bool
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Customer
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return !($this->isLocked);
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set patronym
     *
     * @param string $patronym
     *
     * @return Customer
     */
    public function setPatronym($patronym)
    {
        $this->patronym = $patronym;

        return $this;
    }

    /**
     * Get patronym
     *
     * @return string
     */
    public function getPatronym()
    {
        return $this->patronym;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Customer
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Customer
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Add groupActivity
     *
     * @param \AppBundle\Entity\GroupActivity $groupActivity
     *
     * @return Customer
     */
    public function addGroupActivity(\AppBundle\Entity\GroupActivity $groupActivity)
    {
        $this->groupActivities[] = $groupActivity;

        return $this;
    }

    /**
     * Remove groupActivity
     *
     * @param \AppBundle\Entity\GroupActivity $groupActivity
     */
    public function removeGroupActivity(\AppBundle\Entity\GroupActivity $groupActivity)
    {
        $this->groupActivities->removeElement($groupActivity);
    }

    /**
     * Get groupActivities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupActivities()
    {
        return $this->groupActivities;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            $this->isLocked,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            $this->isLocked,
            ) = unserialize($serialized);
    }
}
