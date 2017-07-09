<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Customer;

/**
 * GroupActivityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupActivityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get all existing group activities with related subscription method for the customer
     * @param Customer $customer
     * @return array
     */
    public function getAllGroupActivitiesWithSubscriptionInfoForCustomer(Customer $customer){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT ga.id, ga.activityName, ga.description, ns.method
                FROM AppBundle:GroupActivity ga INDEX BY ga.id
                LEFT JOIN AppBundle:NotificationSubscription ns WITH ns.groupActivity = ga and ns.customer = :customer
                ')
            ->setParameter('customer', $customer)
            ->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
//        SELECT ga.activityName, ga.description, ns.method
//        FROM groupactivity as ga
//        LEFT JOIN notification_subscription as ns ON ns.groupactivity_id = ga.id and ns.customer_id = 8
    }
}
