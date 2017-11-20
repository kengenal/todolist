<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RegisterController extends Controller
{   
    
    /**
     * @Route("/register", name="registration")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function registerAction(Request $request)
    {
        $user = new User();
        
        $form = $this->createUserRegistrationForm($user);
        
        return $this->render('registration/register.html.twig',[
            'register_form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/registration-form-submission", name="registration_form_submission")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */

    public function handleFormSubmissionAction(Request $request)
    {
        $user = new User();

        $form = $this->createUserRegistrationForm($user);

        $form->handleRequest($request);

        if ( !$form->isSubmitted() || !$form->isValid())
        {

            return $this->render('registration/register.html.twig', [
                'register_form' => $form->createView(),

            ]);
        }

        $password = $this
            ->get('security.password_encoder')
            ->encodePassword(
                $user,
                $user->getPlainPassword()
            )
        ;

        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));

        $this->addFlash('success', 'You are now successfully registered!');

        return $this->redirectToRoute('todo');

    }

    /**
     * @param $user
     * @return \Symfony\Component\Form\Form
     */
    private function createUserRegistrationForm($user)
    {
        return $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('registration_form_submission')
        ]);
    }
}