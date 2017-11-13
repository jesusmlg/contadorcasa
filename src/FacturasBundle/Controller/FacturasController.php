<?php

namespace FacturasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LecturasBundle\Entity\Lectura;
use FacturasBundle\Entity\Factura;
use FacturasBundle\Form\FacturaType;
use Symfony\Component\HttpFoundation\Request;

class FacturasController extends Controller
{
    public function indexAction()
    {
        return $this->render('FacturasBundle:Default:index.html.twig');
    }

    public function agregarAction(Request $request)
    {
      $form = $this->createForm(FacturaType::class);
      $em = $this->getDoctrine()->getEntityManager();

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid() )
      {
          $factura = $form->getData();
          $em->persist($factura);
          $em->flush();

          return $this->redirectToRoute('lecturas_homepage');
      }

      return $this->render('FacturasBundle::new.html.twig', array('form' => $form->createView()));
    }
}
