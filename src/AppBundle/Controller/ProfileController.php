<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        //$em->getRepository('AppBundle:Customer')->findOneBy(['id' => $customer->getId()]);
        $groupActivitiesAllExisting = $em->getRepository('AppBundle:GroupActivity')->findAll();

//        if ($customerPasswordForm->isSubmitted() && $customerPasswordForm->isValid()) {
//            //set customer's password and update isActive
//            $customer->setIsActive(true);
//            $em->flush();
//            return $this->redirectToRoute('', array('id' => $customer->getId()));
//        }

        return $this->render('profile/activities_notifications_settings.html.twig', array(
            'customer' => $customer,
            'group_activities_all_existing' => $groupActivitiesAllExisting,
        ));
    }
}
