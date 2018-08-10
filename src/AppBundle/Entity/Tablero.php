<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tablero
 *
 * @ORM\Table(name="tablero")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TableroRepository")
 */
class Tablero
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
     * @ORM\Column(name="num_filas", type="integer")
     */
    private $numFilas;

    /**
     * @var int
     *
     * @ORM\Column(name="num_columnas", type="integer")
     */
    private $numColumnas;

    /**
     * Un Tablero se juega en una Partida.
     * @ORM\OneToOne(targetEntity="Partida", inversedBy="tablero")
     * @ORM\JoinColumn(name="partida_id", referencedColumnName="id")
     */
    private $partida;


    /**
     * Un Tablero tiene puestas varias Fichas.
     * @ORM\OneToMany(targetEntity="Ficha", mappedBy="tablero")
     */
    private $fichas;


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
     * Set numFilas
     *
     * @param integer $numFilas
     *
     * @return Tablero
     */
    public function setNumFilas($numFilas)
    {
        $this->numFilas = $numFilas;

        return $this;
    }

    /**
     * Get numFilas
     *
     * @return int
     */
    public function getNumFilas()
    {
        return $this->numFilas;
    }

    /**
     * Set numColumnas
     *
     * @param integer $numColumnas
     *
     * @return Tablero
     */
    public function setNumColumnas($numColumnas)
    {
        $this->numColumnas = $numColumnas;

        return $this;
    }

    /**
     * Get numColumnas
     *
     * @return int
     */
    public function getNumColumnas()
    {
        return $this->numColumnas;
    }

    /**
     * Get dimension
     *
     * @return int
     */
    public function getDimension()
    {
        return $this->numColumnas * $this->numFilas;
    }

    /**
     * Set partida
     *
     * @param \AppBundle\Entity\Partida $partida
     *
     * @return Tablero
     */
    public function setPartida(\AppBundle\Entity\Partida $partida = null)
    {
        $this->partida = $partida;

        return $this;
    }

    /**
     * Get partida
     *
     * @return \AppBundle\Entity\Partida
     */
    public function getPartida()
    {
        return $this->partida;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ficha
     *
     * @param \AppBundle\Entity\Ficha $ficha
     *
     * @return Tablero
     */
    public function addFicha(\AppBundle\Entity\Ficha $ficha)
    {
        $this->fichas[] = $ficha;

        return $this;
    }

    /**
     * Remove ficha
     *
     * @param \AppBundle\Entity\Ficha $ficha
     */
    public function removeFicha(\AppBundle\Entity\Ficha $ficha)
    {
        $this->fichas->removeElement($ficha);
    }

    /**
     * Get fichas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichas()
    {
        return $this->fichas;
    }
}
