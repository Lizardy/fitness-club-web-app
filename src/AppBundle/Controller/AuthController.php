<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Auth controller.
 *
 * @Route("auth")
 */
class AuthController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * Displays form for a customer to set password and activate account
     *
     * @Route("/activate/{token}", name="auth_activate")
     * @Method({"GET", "POST"})
     */
    public function activateAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $activationToken = $request->get('token');
        $customer = $em->getRepository('AppBundle:Customer')->findOneBy(['activationToken' => $activationToken]);
        $customerPasswordForm = $this->createForm('AppBundle\Form\CustomerPasswordType', $customer);
        $customerPasswordForm->handleRequest($request);

        if ($customerPasswordForm->isSubmitted() && $customerPasswordForm->isValid()) {
            //set customer's password, update isActive and "expire" token
            $encodedPassword = $encoder->encodePassword($customer, $customer->getPassword());
            $customer->setPassword($encodedPassword);
            $customer->setIsActive(true);
            $customer->setActivationToken(null);
            $em->flush();

            //manually authenticate customer
            //$this->authenticateCustomer($customer);
            return $this->redirectToRoute('profile_notifications_settings', array('customer_id' => $customer->getId()));
        }

        return $this->render('auth/activate_account.html.twig', array(
            'customer' => $customer,
            'customer_password_form' => $customerPasswordForm->createView(),
        ));
    }

    private function authenticateCustomer(Customer $customer)
    {
        $providerKey = 'main'; // firewall name
        $token = new UsernamePasswordToken($customer, null, $providerKey, $customer->getRoles());

        //tokenStorageInterface->setToken($token);
    }
}
