<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ficha
 *
 * @ORM\Table(name="ficha")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FichaRepository")
 */
class Ficha
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
     * @var int
     *
     * @ORM\Column(name="pos_fila", type="integer")
     */
    private $posFila;

    /**
     * @var int
     *
     * @ORM\Column(name="pos_columna", type="integer")
     */
    private $posColumna;


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
     * Set posFila
     *
     * @param integer $posFila
     *
     * @return Ficha
     */
    public function setPosFila($posFila)
    {
        $this->posFila = $posFila;

        return $this;
    }

    /**
     * Get posFila
     *
     * @return int
     */
    public function getPosFila()
    {
        return $this->posFila;
    }

    /**
     * Set posColumna
     *
     * @param integer $posColumna
     *
     * @return Ficha
     */
    public function setPosColumna($posColumna)
    {
        $this->posColumna = $posColumna;

        return $this;
    }

    /**
     * Get posColumna
     *
     * @return int
     */
    public function getPosColumna()
    {
        return $this->posColumna;
    }
}

