<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\NotificationSubscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Profile controller.
 *
 * @Route("profile")
 */
class ProfileController extends Controller
{
    /**
     * Displays all group activities for a customer
     * to select which of them he/she wants to receive notifications about
     * and which type of notification: email or sms
     *
     * @Route("/notifications", name="profile_notifications_settings")
     * @Method({"GET", "POST"})
     */
    public function notificationsSettingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $this->container->get('security.token_storage')->getToken()->getUser();
        $allGroupActivitiesWithSubscriptionInfoForCustomer = $em->getRepository('AppBundle:GroupActivity')
            ->getAllGroupActivitiesWithSubscriptionInfoForCustomer($customer);

        //the form (without formtype definition) was submitted with post
        if ($request->isMethod('POST')) {
            $notificationSubscriptionRepository = $em->getRepository('AppBundle:NotificationSubscription');
            $groupActivitiesAllEntities = $em->getRepository('AppBundle:GroupActivity')->findAll();
            foreach ($groupActivitiesAllEntities as $groupActivity){
                $groupActivityId = $groupActivity->getId();
                $method = $request->request->get($groupActivityId);
                if ($method !== "0") {
                    // one of methods/types was selected
                    if (!$allGroupActivitiesWithSubscriptionInfoForCustomer[$groupActivityId]['method']){
                        //case one: there was no method set and now there is, create it anew
                        $notificationSubscription = new NotificationSubscription($method);
                        $notificationSubscription->setCustomer($customer);
                        $notificationSubscription->setGroupActivity($groupActivity);
                        $em->persist($notificationSubscription);
                    } else {
                        //case two: method was changed to another method, update it
                        $notificationSubscription = $notificationSubscriptionRepository
                            ->findOneBy(array('customer' => $customer, 'groupActivity' => $groupActivity));
                        $notificationSubscription->setMethod($method);
                        $em->persist($notificationSubscription);
                    }
                } else {
                    // 'none' method was set meaning that subscription for this activity is not desired
                    if ($allGroupActivitiesWithSubscriptionInfoForCustomer[$groupActivityId]['method']){
                        //case three: there was some method set before, now the subscription is cancelled
                        $notificationSubscription = $notificationSubscriptionRepository
                            ->findOneBy(array('customer' => $customer, 'groupActivity' => $groupActivity));
                        $em->remove($notificationSubscription);
                    }
                }
            }
            //execute all changes in one transaction
            $em->flush();

            //todo: add flash message
            return $this->redirect($request->getUri());
        }

        return $this->render('profile/activities_notifications_settings.html.twig', array(
            'group_activities_subscriptions' => $allGroupActivitiesWithSubscriptionInfoForCustomer
        ));
    }

    /**
     * Customer's profile page where his/her info is available and password can be changed
     *
     * @Route("/info", name="profile_info")
     * @Method({"GET", "POST"})
     */
    public function profileAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $this->container->get('security.token_storage')->getToken()->getUser();
        $customerPasswordForm = $this->createForm('AppBundle\Form\CustomerPasswordType', $customer);
        $customerPasswordForm->handleRequest($request);

        if ($customerPasswordForm->isSubmitted() && $customerPasswordForm->isValid()) {
            //set customer's password
            $encodedPassword = $encoder->encodePassword($customer, $customer->getPassword());
            $customer->setPassword($encodedPassword);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('profile/profile_info.html.twig', array(
            'customer' => $customer,
            'customer_password_form' => $customerPasswordForm->createView(),
        ));
    }
}
