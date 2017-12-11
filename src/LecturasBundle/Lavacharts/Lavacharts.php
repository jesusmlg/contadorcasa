<?php

namespace LecturasBundle\Lavacharts;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
//use Symfony\Component\DependencyInjection\Container;
/**
 *
 */
class Lavacharts
{
  private $entityManager;
  private $lava;

  function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
    $this->lava = new \Khill\Lavacharts\Lavacharts;

  }
  public function ComparativoMesAnio()
  {

    $datatable = $this->lava->DataTable();

    if(!$ultimaLecturaActual = $this->ultimaLectura())
      return $datatable;

    if(!$ultimaFactura = $this->ultimaFactura())
      return $datatable;

    if(!$penultimaFactura = $this->penultimaFactura())
        return $datatable;

    $interval = new \DateInterval('P1M');
    $interval->invert = 1;
    $ultimaMesPasado = $ultimaLecturaActual->getFecha()->add($interval);

     $sql = "SELECT l.* FROM lectura l WHERE
     l.fecha = (SELECT max(l2.fecha) FROM lectura l2 WHERE l2.fecha <= '".$ultimaMesPasado->format('Y-m-d')."')";


    $rsmb = new ResultSetMappingBuilder($this->entityManager);
    $rsmb->addRootEntityFromClassMetadata('LecturasBundle\Entity\Lectura', 'l');

    if(!$lecturaMesPasado = $this->entityManager->createNativeQuery($sql,$rsmb)->getOneOrNullResult())
    return $datatable;



    $datatable->addStringColumn('Contador')
      ->addNumberColumn('Lecturas')
      ->addRow(['Mes Actual: '.$ultimaLecturaActual->getFecha()->format('d-m-Y'),  $ultimaLecturaActual->getLectura() - $ultimaFactura->getLectura()])
      ->addRow(['Mes Anterior: '.$lecturaMesPasado->getFecha()->format('d-m-Y'),  $ultimaFactura->getLectura() - $penultimaFactura->getLectura() ]);


    //$lava->BarChart('comparativo', $data);

    return $datatable;

  }

  public function ultimosDiezDias()
  {


    $lecturas = $this->entityManager->getRepository('LecturasBundle:Lectura')
                                    ->createQueryBuilder('l')
                                    ->orderBy("l.fecha", "DESC")
                                    ->setMaxResults(10)
                                    ->getQuery()
                                    ->getResult();

    $lecturas = array_reverse($lecturas);

    $datatable = $this->lava->DataTable();
    $datatable->addStringColumn('Year')
              ->addNumberColumn('Lecturas');

    $first = true;

    foreach ($lecturas as $l) {

        if(!$first){
            $lecturaDelDia = $l->getLectura() - $lanterior->getLectura();
            $datatable->addRow([$lanterior->getDayOfWeek(),$lecturaDelDia]);
        }
        $first = false;
        $lanterior = $l;
    }

    return $datatable;
  }

  private function ultimaLectura()
  {
    $repo = $this->entityManager->getRepository('LecturasBundle:Lectura');

    $ultimaLecturaActual =  $repo->createQueryBuilder('l3')
                         //  ->where("")
                          ->orderBy("l3.fecha", "DESC")
                          ->setMaxResults(1)
                          ->getQuery()
                          ->getOneOrNullResult();

    return $ultimaLecturaActual;
  }

  private function consumoDesdeUltFactura()
  {
    $repo = $this->entityManager->getRepository('LecturasBundle:Lectura');

    //$dato = $repo->
  }

  private function ultimaFactura()
  {
    return $this->entityManager->getRepository('FacturasBundle:Factura')
                               ->createQueryBuilder('f')
                               ->orderBy('f.fecha','DESC')
                               ->setMaxResults(1)
                               ->getQuery()
                               ->getOneOrNullResult();
  }

  private function penultimaFactura()
  {
    return $this->entityManager->getRepository('FacturasBundle:Factura')
                               ->createQueryBuilder('f')
                               ->where("f.fecha< '".$this->ultimaFactura()->getFecha()->format('Y-m-d')."'")
                               ->orderBy('f.fecha','DESC')
                               ->setMaxResults(1)
                               ->getQuery()
                               ->getOneOrNullResult();
  }


}
