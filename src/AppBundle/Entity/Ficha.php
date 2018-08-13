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
     * Varias Fichas pueden ser de un Tipo.
     * @ORM\ManyToOne(targetEntity="TipoFicha")
     * @ORM\JoinColumn(name="tipo_ficha_id", referencedColumnName="id")
     */
    private $tipo;

    /**
     * Varias Fichas están puestas sobre un Tablero.
     * @ORM\ManyToOne(targetEntity="Tablero", inversedBy="fichas")
     * @ORM\JoinColumn(name="tablero_id", referencedColumnName="id")
     */
    private $tablero;

    /**
     * Varias Fichas son puestas por un Jugador.
     * @ORM\ManyToOne(targetEntity="Jugador")
     * @ORM\JoinColumn(name="jugador_id", referencedColumnName="id")
     */
    private $jugador;


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

    /**
     * Set tablero
     *
     * @param \AppBundle\Entity\Tablero $tablero
     *
     * @return Ficha
     */
    public function setTablero(\AppBundle\Entity\Tablero $tablero = null)
    {
        $this->tablero = $tablero;

        return $this;
    }

    /**
     * Get tablero
     *
     * @return \AppBundle\Entity\Tablero
     */
    public function getTablero()
    {
        return $this->tablero;
    }

    /**
     * Set tipo
     *
     * @param \AppBundle\Entity\TipoFicha $tipo
     *
     * @return Ficha
     */
    public function setTipo(\AppBundle\Entity\TipoFicha $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \AppBundle\Entity\TipoFicha
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set jugador
     *
     * @param \AppBundle\Entity\Jugador $jugador
     *
     * @return Ficha
     */
    public function setJugador(\AppBundle\Entity\Jugador $jugador = null)
    {
        $this->jugador = $jugador;

        return $this;
    }

    /**
     * Get jugador
     *
     * @return \AppBundle\Entity\Jugador
     */
    public function getJugador()
    {
        return $this->jugador;
    }
}
