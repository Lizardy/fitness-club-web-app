<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Customer controller.
 *
 * @Route("admin/customers")
 */
class CustomerController extends Controller
{
    /**
     * Lists all customer entities.
     *
     * @Route("/", name="admin_customers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository('AppBundle:Customer')->findAll();

        return $this->render('customers/index.html.twig', array(
            'customers' => $customers,
        ));
    }

    /**
     * Admin creates a new customer entity.
     *
     * @Route("/new", name="admin_customers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm('AppBundle\Form\CustomerType', $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //generate token for a customer to be able to activate his/her profile
            $activationToken = rtrim(strtr(base64_encode(random_bytes(10)), '+/=', '-_'),'=');
            $customer->setActivationToken($activationToken);
            //password is empty string for now, because customer will be prompted to set it
            //customer will not be able to login anyway while account is not active
            $customer->setPassword('');
            $customer->setRoles(array('ROLE_USER'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            //send email with activation token to the new customer
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('Your account has been created')
                ->setFrom(array(
                    $this->getParameter('mail_from_address') => $this->getParameter('mail_from_name')
                ))
                ->setTo($customer->getEmail())
                ->setBody(
                    $this->renderView('auth/activation_email.html.twig', [
                        'homepage' => $this->generateUrl('homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL),
                        'name' => $customer->getFirstName(),
                        'token' => $activationToken
                    ]), 'text/html'
                );
            $mailer->send($message);

            return $this->redirectToRoute('admin_customers_show', array('id' => $customer->getId()));
        }

        return $this->render('customers/new.html.twig', array(
            'customer' => $customer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customer entity.
     *
     * @Route("/{id}", name="admin_customers_show")
     * @Method("GET")
     */
    public function showAction(Customer $customer)
    {
        $deleteForm = $this->createDeleteForm($customer);

        return $this->render('customers/show.html.twig', array(
            'customer' => $customer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customer entity.
     *
     * @Route("/{id}/edit", name="admin_customers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Customer $customer)
    {
        $deleteForm = $this->createDeleteForm($customer);
        $editForm = $this->createForm('AppBundle\Form\CustomerType', $customer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_customers_edit', array('id' => $customer->getId()));
        }

        return $this->render('customers/edit.html.twig', array(
            'customer' => $customer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a customer entity.
     *
     * @Route("/{id}", name="admin_customers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Customer $customer)
    {
        $form = $this->createDeleteForm($customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();
        }

        return $this->redirectToRoute('admin_customers_index');
    }

    /**
     * Creates a form to delete a customer entity.
     *
     * @param Customer $customer The customer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Customer $customer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_customers_delete', array('id' => $customer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
