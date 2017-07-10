<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\GroupActivity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Groupactivity controller.
 *
 * @Route("admin/groupactivities")
 */
class GroupActivityController extends Controller
{
    /**
     * Lists all groupActivity entities.
     *
     * @Route("/", name="admin_groupactivities_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groupActivities = $em->getRepository('AppBundle:GroupActivity')->findAll();

        return $this->render('groupactivity/index.html.twig', array(
            'groupActivities' => $groupActivities,
        ));
    }

    /**
     * Submit the form for sending notifications to all group activity's subscribers
     *
     * @Route("/queuenotifications", name="admin_groupactivities_queuenotifications")
     * @Method("POST")
     */
    public function queueNotificationsAction(Request $request)
    {
        $textEmailTemplate = $request->request->get('email-text');
        $textSmsTemplate = $request->request->get('sms-text');
        $groupActivityId = intval($request->request->get('group-activity-id'));
        $em = $this->getDoctrine()->getManager();
        $groupActivity = $em->getRepository('AppBundle:GroupActivity')
            ->findOneBy(array('id' => $groupActivityId));
        $customersSubscribers = $em->getRepository('AppBundle:Customer')->findBy(array('isLocked' => false));
        $sendNotificationProducer = $this->get('old_sound_rabbit_mq.send_notification_producer');

        foreach ($customersSubscribers as $customerSubscriber){

            if ($customerSubscriber->getSubscriptionMethod($groupActivity) === 'email'){
                $textEmail = $customerSubscriber->fillMessageTemplate($textEmailTemplate);
                $msg = array('text' => $textEmail, 'send_to' => $customerSubscriber->getEmail());
                $sendNotificationProducer->setDeliveryMode(1);
                $sendNotificationProducer->publish(serialize($msg), 'rk.notification.email');
            } else if ($customerSubscriber->getSubscriptionMethod($groupActivity) === 'sms'){
                $textSms = $customerSubscriber->fillMessageTemplate($textSmsTemplate);
                $msg = array('text' => $textSms, 'send_to' => $customerSubscriber->getPhoneNumber());
                $sendNotificationProducer->setDeliveryMode(1);
                $sendNotificationProducer->publish(serialize($msg), 'rk.notification.sms');
            }
        }

        return $this->redirectToRoute('admin_groupactivities_index');
    }

    /**
     * Creates a new groupActivity entity.
     *
     * @Route("/new", name="admin_groupactivities_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $groupActivity = new Groupactivity();
        $form = $this->createForm('AppBundle\Form\GroupActivityType', $groupActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupActivity);
            $em->flush();

            return $this->redirectToRoute('admin_groupactivities_show', array('id' => $groupActivity->getId()));
        }

        return $this->render('groupactivity/new.html.twig', array(
            'groupActivity' => $groupActivity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a groupActivity entity.
     *
     * @Route("/{id}", name="admin_groupactivities_show")
     * @Method("GET")
     */
    public function showAction(GroupActivity $groupActivity)
    {
        return $this->render('groupactivity/show.html.twig', array(
            'groupActivity' => $groupActivity,
            'placeholderVariables' => Customer::PLACEHOLDER_VARIABLES
        ));
    }

    /**
     * Displays a form to edit an existing groupActivity entity.
     *
     * @Route("/{id}/edit", name="admin_groupactivities_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, GroupActivity $groupActivity)
    {
        $editForm = $this->createForm('AppBundle\Form\GroupActivityType', $groupActivity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_groupactivities_edit', array('id' => $groupActivity->getId()));
        }

        return $this->render('groupactivity/edit.html.twig', array(
            'groupActivity' => $groupActivity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a groupActivity entity.
     *
     * @Route("/{id}", name="admin_groupactivities_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, GroupActivity $groupActivity)
    {
        $form = $this->createDeleteForm($groupActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupActivity);
            $em->flush();
        }

        return $this->redirectToRoute('admin_groupactivities_index');
    }

    /**
     * Creates a form to delete a groupActivity entity.
     *
     * @param GroupActivity $groupActivity The groupActivity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(GroupActivity $groupActivity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_groupactivities_delete', array('id' => $groupActivity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
