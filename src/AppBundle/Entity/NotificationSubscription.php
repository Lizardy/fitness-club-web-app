<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * NotificationSubscription
 *
 * @ORM\Table(name="notification_subscription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationSubscriptionRepository")
 */
class NotificationSubscription
{
    /**
     * Notification Subscription has method of notification delivery such as sms or email
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=40)
     */
    private $method;

    /**
     * Customer who is subscribed to notifications
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="notificationSubscriptions")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    protected $customer;

    /**
     * Notifications about this Group activity
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="GroupActivity", inversedBy="notificationSubscriptions")
     * @ORM\JoinColumn(name="groupactivity_id", referencedColumnName="id", nullable=false)
     */
    protected $groupActivity;

    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * Set method
     *
     * @param string $method
     *
     * @return NotificationSubscription
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return NotificationSubscription
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set groupActivity
     *
     * @param \AppBundle\Entity\GroupActivity $groupActivity
     *
     * @return NotificationSubscription
     */
    public function setGroupActivity(\AppBundle\Entity\GroupActivity $groupActivity)
    {
        $this->groupActivity = $groupActivity;

        return $this;
    }

    /**
     * Get groupActivity
     *
     * @return \AppBundle\Entity\GroupActivity
     */
    public function getGroupActivity()
    {
        return $this->groupActivity;
    }
}
