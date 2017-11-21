<?php

namespace SessionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionsController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function newAction(Request $request)
    {
        $form = $this->createFormBuilder()->add('pass', PasswordType::class, array('attr' => array('class' => 'form-control form-group')))
                                          ->add('submit', SubmitType::class, array('label' => 'Acceder','attr' => array('class' => 'form-control btn btn-primary')))
                                          ->getForm();

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())
        {

          if($form['pass']->getData() == $this->getParameter('contador_pass'))
          {
            $token = md5($this->getParameter('contador_pass'));
            $request->getSession()->set('contador_token',$token);

            $response = new Response();
            $response->headers->setCookie(new Cookie('contador_cookie',$token, time()+(3600*24*30)));
            $response->sendHeaders();

            return $this->redirectToRoute('lecturas_homepage');
          }
        }

        return $this->render('SessionsBundle::new.html.twig', array('form' => $form->createView()));
    }
}
