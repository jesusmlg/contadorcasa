<?php

namespace LecturasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lectura
 *
 * @ORM\Table(name="lectura")
 * @ORM\Entity(repositoryClass="LecturasBundle\Repository\LecturaRepository")
 */
class Lectura
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id=0;

    /**
     * @var string
     *
     * @ORM\Column(name="lectura", type="decimal", precision=5, scale=0)
     */
    private $lectura = "0";

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha = "01-01-1900";


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lectura
     *
     * @param string $lectura
     *
     * @return Lectura
     */
    public function setLectura($lectura)
    {
        $this->lectura = $lectura;

        return $this;
    }

    /**
     * Get lectura
     *
     * @return string
     */
    public function getLectura()
    {
        return $this->lectura;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Lectura
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Get fecha
     *
     * @return string
     */
    public function getFechaString()
    {
        $res = $this->getFecha()->format('d-m-Y');
        return $res;

    }
}
