<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * GroupActivity
 *
 * @ORM\Table(name="groupactivity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupActivityRepository")
 */
class GroupActivity
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
     * @ORM\Column(name="activityName", type="string", length=255)
     */
    private $activityName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Many GroupActivities have One Trainer.
     * @ManyToOne(targetEntity="Trainer", inversedBy="groupActivities")
     * @JoinColumn(name="trainer_id", referencedColumnName="id")
     */
    private $trainer;

    /**
     * Many Group Activities have Many Customers subscribed to them via Notification Subscriptions.
     * @OneToMany(targetEntity="NotificationSubscription", mappedBy="groupActivity")
     */
    private $notificationSubscriptions;

    public function __construct() {
        $this->users = new ArrayCollection();
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
     * Set activityName
     *
     * @param string $activityName
     *
     * @return GroupActivity
     */
    public function setActivityName($activityName)
    {
        $this->activityName = $activityName;

        return $this;
    }

    /**
     * Get activityName
     *
     * @return string
     */
    public function getActivityName()
    {
        return $this->activityName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return GroupActivity
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set trainer
     *
     * @param \AppBundle\Entity\Trainer $trainer
     *
     * @return GroupActivity
     */
    public function setTrainer(\AppBundle\Entity\Trainer $trainer = null)
    {
        $this->trainer = $trainer;

        return $this;
    }

    /**
     * Get trainer
     *
     * @return \AppBundle\Entity\Trainer
     */
    public function getTrainer()
    {
        return $this->trainer;
    }

    public function addNotificationSubscription(\AppBundle\Entity\NotificationSubscription $notificationSubscription)
    {
        $this->notificationSubscriptions[] = $notificationSubscription;

        return $this;
    }

    /**
     * Remove notificationSubscription
     *
     * @param \AppBundle\Entity\NotificationSubscription $notificationSubscription
     */
    public function removeNotificationSubscription(\AppBundle\Entity\NotificationSubscription $notificationSubscription)
    {
        $this->notificationSubscriptions->removeElement($notificationSubscription);
    }

    /**
     * Get notificationSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationSubscriptions()
    {
        return $this->notificationSubscriptions;
    }
}
