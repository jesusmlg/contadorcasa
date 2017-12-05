<?php

namespace LecturasBundle\Repository;

/**
 * LecturaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LecturaRepository extends \Doctrine\ORM\EntityRepository
{
  public function UltimaLectura()
  {
    return $this->getEntityManager()
    			->getRepository("LecturasBundle:Lectura")
                ->createQueryBuilder('l')
                ->orderBy('l.fecha','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

  }

  public function lecturasPostFactura()
  {

  	$ultimaFactura = $this->getEntityManager()->getRepository("FacturasBundle:Factura")->UltimaFactura();

  	return $this->getEntityManager()
  				->getRepository("LecturasBundle:Lectura")
                ->createQueryBuilder('l')
                ->where("l.fecha > :fecha")
                ->setParameter('fecha',$ultimaFactura->getFecha())
                ->orderBy('l.fecha','DESC')
                ->getQuery()
                ->getResult();

  }


}
