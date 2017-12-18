<?php

namespace LecturasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LecturasBundle\Entity\Lectura;
use FacturasBundle\Entity\Factura;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LecturasBundle\Form\LecturaType;
use FacturasBundle\Entity\Estimacion;
use Doctrine\ORM\EntityRepository;
use AppBundle\Controller\IAccesoUsuarioController;
use LecturasBundle\Lavacharts\Lavacharts;

class LecturasController extends Controller implements IAccesoUsuarioController
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ultimaFactura = $em->getRepository("FacturasBundle:Factura")->UltimaFactura();
        $ultimaLectura = $em->getRepository("LecturasBundle:Lectura")->UltimaLectura();
        $estimacion = $em->getRepository("FacturasBundle:Estimacion")->valoresEstimacion();

        if($ultimaFactura!= null && $ultimaLectura!= null)
        {
          $totalKWDesdeFactura = $ultimaLectura->getLectura() - $ultimaFactura->getLectura();
          $dias = ($ultimaFactura->getFecha()->diff($ultimaLectura->getFecha()))->format('%R%a');
          $mediaKWDiaria = ($dias >0 ) ? ($totalKWDesdeFactura / $dias) : 0;

          $iva = ($estimacion) ? (1+($estimacion->getIva()/ 100)) : 0;

          $gastoActual = ($estimacion) ? ((($estimacion->getPreciokw() * $totalKWDesdeFactura) + $estimacion->getFijo() ))  * $iva : 0;
          $gastoPrevision = ($estimacion) ? ((($estimacion->getPreciokw() * $mediaKWDiaria * 61) + $estimacion->getFijo() )) * $iva : 0;

        }
        else
        {
          $mediaKWDiaria = 0;
          $totalKWDesdeFactura = 0;
          $ultimaFactura = new Factura();
          $ultimaLectura = new Lectura();
          $gastoActual = $gastoPrevision = 0;
        }


        

        $form = $this->createForm(LecturaType::class);

        $form->handleRequest($request);

        //LAVA GRAPHICS
        $lava = $this->get('lavacharts');
        $datolava = $this->container->get(Lavacharts::class);

        $lava->BarChart('comparativo', $datolava->ComparativoMesAnio(), ['hAxis' => [
        'minValue' => 0
        ]]);

        $lava->ColumnChart('ultimosdiezdias',$datolava->ultimosDiezDias(),['vAxis' => [
        'minValue' => 0
        ]]);


        //END LAVA GRAPHICS

        //PAGINATION
        //$dql = "SELECT lb FROM LecturasBundle:Lectura lb";
        //$query = $em->createQuery($dql);
        
        $lecturas = $em->getRepository("LecturasBundle:Lectura")->lecturasPostFactura();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $lecturas, // $dql
            $request->query->getInt('page',1),
            5
        );

        //END PAGINATION

        if($form->isSubmitted() && $form->isValid())
        {
          $lectura = $form->getData();

          $em->persist($lectura);
          $em->flush();

          return $this->redirectToRoute('lecturas_homepage');

        }



        return $this->render('LecturasBundle::index.html.twig', array('lecturas' => $result,
                                                                      'form' => $form->createView(),
                                                                      'UltimaFactura' => $ultimaFactura,
                                                                      'totalKWDesdeFactura'=> $totalKWDesdeFactura,
                                                                      'mediaKWDiaria' => $this->numN2($mediaKWDiaria),
                                                                      'estimacion' => $estimacion,
                                                                      'gastoActual' => $this->numN2($gastoActual),
                                                                      'gastoPrevision' => $this->numN2($gastoPrevision)
                                                                    ));
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

    private function numN2($n)
    {
      return number_format($n, 2, ',','.');
    }

}
