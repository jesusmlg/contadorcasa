<?php

namespace FacturasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estimacion
 *
 * @ORM\Table(name="estimacion")
 * @ORM\Entity(repositoryClass="FacturasBundle\Repository\EstimacionRepository")
 */
class Estimacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fijo", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $fijo;

    /**
     * @var string
     *
     * @ORM\Column(name="preciokw", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $preciokw;

    /**
     * @var string
     *
     * @ORM\Column(name="iva", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $iva;


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
     * Set fijo
     *
     * @param string $fijo
     *
     * @return Estimacion
     */
    public function setFijo($fijo)
    {
        $this->fijo = $fijo;

        return $this;
    }

    /**
     * Get fijo
     *
     * @return string
     */
    public function getFijo()
    {
        return $this->fijo;
    }

    /**
     * Set preciokw
     *
     * @param string $preciokw
     *
     * @return Estimacion
     */
    public function setPreciokw($preciokw)
    {
        $this->preciokw = $preciokw;

        return $this;
    }

    /**
     * Get preciokw
     *
     * @return string
     */
    public function getPreciokw()
    {
        return $this->preciokw;
    }

    /**
     * Set iva
     *
     * @param string $iva
     *
     * @return Estimacion
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return string
     */
    public function getIva()
    {
        return $this->iva;
    }
}
