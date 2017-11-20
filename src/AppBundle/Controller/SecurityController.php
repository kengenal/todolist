<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;


class SecurityController extends Controller
{
    
    /**
     *@Route("/login", name="login")
     */
    public function loginAction()
    {
        
        return $this->render('security/login.html.twig',[

        ]);
    }

    /**
     * @Route("/logout")
     * @throws \RuntimeExeption
     */
    public function logoutAction()
    {
        throw new \RuntimeExeption('Nope');
    }
}