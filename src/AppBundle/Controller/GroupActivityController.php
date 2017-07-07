<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GroupActivity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/sendnotifications", name="admin_groupactivities_sendnotifications")
     * @Method("POST")
     */
    public function sendNotificationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $groupActivities = $em->getRepository('AppBundle:GroupActivity')->findAll();
        //todo: use rabbitmq to send notifications

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
        $deleteForm = $this->createDeleteForm($groupActivity);

        return $this->render('groupactivity/show.html.twig', array(
            'groupActivity' => $groupActivity,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($groupActivity);
        $editForm = $this->createForm('AppBundle\Form\GroupActivityType', $groupActivity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_groupactivities_edit', array('id' => $groupActivity->getId()));
        }

        return $this->render('groupactivity/edit.html.twig', array(
            'groupActivity' => $groupActivity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
