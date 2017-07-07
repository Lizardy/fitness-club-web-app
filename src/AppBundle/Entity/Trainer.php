<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\GroupActivity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Trainer
 *
 * @ORM\Table(name="trainer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrainerRepository")
 */
class Trainer
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="patronym", type="string", length=255, nullable=true)
     */
    private $patronym;

    /**
     * One Trainer has Many Group Activities.
     * @OneToMany(targetEntity="GroupActivity", mappedBy="trainer")
     */
    private $groupActivities;

    public function __construct() {
        $this->groupActivities = new ArrayCollection();
    }

    public function __toString() {
        return $this->lastName . " " . $this->firstName . " " . $this->patronym;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Trainer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Trainer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set patronym
     *
     * @param string $patronym
     *
     * @return Trainer
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
     * Add groupActivity
     *
     * @param \AppBundle\Entity\GroupActivity $groupActivity
     *
     * @return Trainer
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
}
