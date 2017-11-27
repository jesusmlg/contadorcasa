<?php

namespace AppBundle\DataFixtures\ORM;

use LecturasBundle\Entity\Lectura;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 */
class Fixtures extends Fixture
{

  function load (ObjectManager $manager)
  {

    $kw= 2000;
    for($i=0;$i<27;$i++)
    {
      $kw+= rand(3,14);
      $lectura = new Lectura();

      $lectura->setLectura($kw);
      $lectura->setFecha(new \DateTime('2017-11-'.($i+1)));

      $manager->persist($lectura);

    }
    $manager->flush();
  }
}


 ?>
