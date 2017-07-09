<?php

namespace AppBundle\Repository;
use AppBundle\Entity\GroupActivity;

/**
 * CustomerRepository
 */
class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param GroupActivity $groupActivity
     * @return array
     */
    public function getAllCustomersSubscribedToActivity(GroupActivity $groupActivity)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT cust.id, cust.email, cust.firstName, cust.lasName, cust.patronym, cust.birthDate, ns.method
                FROM AppBundle:Customer cust 
                INNER JOIN AppBundle:NotificationSubscription ns WITH ns.customer = cust and ns.groupActivity = :groupActivity
                WHERE cust.isLocked = false
                ')
            ->setParameter('groupActivity', $groupActivity)
            ->getResult();
    }
}
