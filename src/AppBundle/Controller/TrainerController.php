<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trainer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Trainer controller.
 *
 * @Route("admin/trainers")
 */
class TrainerController extends Controller
{
    /**
     * Lists all trainer entities.
     *
     * @Route("/", name="admin_trainers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $trainers = $em->getRepository('AppBundle:Trainer')->findAll();

        return $this->render('trainer/index.html.twig', array(
            'trainers' => $trainers,
        ));
    }

    /**
     * Creates a new trainer entity.
     *
     * @Route("/new", name="admin_trainers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $trainer = new Trainer();
        $form = $this->createForm('AppBundle\Form\TrainerType', $trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trainer);
            $em->flush();

            return $this->redirectToRoute('admin_trainers_show', array('id' => $trainer->getId()));
        }

        return $this->render('trainer/new.html.twig', array(
            'trainer' => $trainer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trainer entity.
     *
     * @Route("/{id}", name="admin_trainers_show")
     * @Method("GET")
     */
    public function showAction(Trainer $trainer)
    {
        $deleteForm = $this->createDeleteForm($trainer);

        return $this->render('trainer/show.html.twig', array(
            'trainer' => $trainer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing trainer entity.
     *
     * @Route("/{id}/edit", name="admin_trainers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Trainer $trainer)
    {
        $deleteForm = $this->createDeleteForm($trainer);
        $editForm = $this->createForm('AppBundle\Form\TrainerType', $trainer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_trainers_edit', array('id' => $trainer->getId()));
        }

        return $this->render('trainer/edit.html.twig', array(
            'trainer' => $trainer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trainer entity.
     *
     * @Route("/{id}", name="admin_trainers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Trainer $trainer)
    {
        $form = $this->createDeleteForm($trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trainer);
            $em->flush();
        }

        return $this->redirectToRoute('admin_trainers_index');
    }

    /**
     * Creates a form to delete a trainer entity.
     *
     * @param Trainer $trainer The trainer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trainer $trainer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_trainers_delete', array('id' => $trainer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
