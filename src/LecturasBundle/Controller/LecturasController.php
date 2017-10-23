<?php

namespace LecturasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LecturasBundle\Entity\Lectura;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LecturasBundle\Form\LecturaType;

class LecturasController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $lecturas = $em->getRepository("LecturasBundle:Lectura")->findBy(array(),array('fecha' => 'DESC'));

        $form = $this->createForm(LecturaType::class);

        $form->handleRequest($request);

        //probando los gráficos de lava
        $lava = $this->get('lavacharts');
        $data = $lava->DataTable();

        $data->addDateColumn('Fecha')->addNumberColumn('Lectura');

        foreach ($lecturas as $l) {
          
          $row = [$l->getFechaString(), $l->getLectura()];
          $data->addRow($row);
        }

        $lava->AreaChart('lecturas', $data,['title' => 'Lecturas Dibujo']);

        if($form->isSubmitted() && $form->isValid())
        {
          $lectura = $form->getData();
          
          $em->persist($lectura);
          $em->flush();

          return $this->redirectToRoute('lecturas_homepage');

        }

        return $this->render('LecturasBundle::index.html.twig', array('lecturas' => $lecturas,'form' => $form->createView()));
    }

    public function eliminarAction(Request $request)
    {
      $em = $this->getDoctrine()->getEntityManager();

      $id = $request->get('id');

      if($id != "")
      {
        $lectura = $em->getRepository('LecturasBundle:Lectura')->find($id);
        $em->remove($lectura);
        $em->flush();        
      }

      return $this->redirectToRoute('lecturas_homepage');


    }
}
