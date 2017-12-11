<?php

namespace FacturasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LecturasBundle\Entity\Lectura;
use FacturasBundle\Entity\Factura;
use FacturasBundle\Entity\Estimacion;
use FacturasBundle\Form\EstimacionType;
use FacturasBundle\Form\FacturaType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\IAccesoUsuarioController;

class FacturasController extends Controller implements IAccesoUsuarioController
{

    public function agregarAction(Request $request)
    {

      $em = $this->getDoctrine()->getEntityManager();

      $form = $this->createForm(FacturaType::class);

      $facturas = $em->getRepository("FacturasBundle:Factura")->findAll();
      //$listadofacturas = $facturas;
      $paginator = $this->get('knp_paginator');
      $listadofacturas = $paginator->paginate(
          $facturas, // $dql
          $request->query->getInt('page',1),
          5
      );

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid() )
      {
          $factura = $form->getData();
          $em->persist($factura);
          $em->flush();

          return $this->redirectToRoute('lecturas_homepage');
      }

      return $this->render('FacturasBundle::new.html.twig', array('form' => $form->createView(),
                                                                  'facturas' => $listadofacturas));
    }

    public function eliminarAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $id = $request->get('id');
      if($id!="")
      {
        $factura = $em->getRepository("FacturasBundle:Factura")->find($id);
        $em->remove($factura);
        $em->flush();
      }


      return $this->redirectToRoute('facturas_agregar');

    }

    public function estimacionAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      if(!$estimacion = $em->getRepository('FacturasBundle:Estimacion')->valoresEstimacion())
        $estimacion = new Estimacion;

      $form = $this->createForm(EstimacionType::class, $estimacion);

      $form->handleRequest($request);

      if($form->isValid() && $form->isSubmitted())
      {
          $estimacion = $form->getData();
          $em->getRepository('FacturasBundle:Estimacion')->guardarEstimacion($estimacion);

          return $this->redirectToRoute('lecturas_homepage');
      }

      return $this->render('FacturasBundle::_form_estimacion.html.twig', array('form' => $form->createView()));
    }
}
