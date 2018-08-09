<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partida
 *
 * @ORM\Table(name="partida")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartidaRepository")
 */
class Partida
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
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime")
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime", nullable=true)
     */
    private $fin;

    /**
     * @var bool
     *
     * @ORM\Column(name="en_curso", type="boolean")
     */
    private $enCurso;

    /**
     * @var bool
     *
     * @ORM\Column(name="finalizada", type="boolean")
     */
    private $finalizada;

    /**
     * @var bool
     *
     * @ORM\Column(name="empate", type="boolean")
     */
    private $empate;

    /**
     * Una partida puede tener un ganador.
     * @ORM\ManyToOne(targetEntity="Jugador")
     * @ORM\JoinColumn(name="ganador_id", referencedColumnName="id")
     */
    private $ganador;

    /**
     * En una partida juega un jugador.
     * @ORM\ManyToOne(targetEntity="Jugador")
     * @ORM\JoinColumn(name="jugador_2_id", referencedColumnName="id")
     */
    private $jugador1;

    /**
     * En una partida juega un segundo jugador.
     * @ORM\ManyToOne(targetEntity="Jugador")
     * @ORM\JoinColumn(name="jugador_2_id", referencedColumnName="id")
     */
    private $jugador2;


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
     * Set inicio
     *
     * @param \DateTime $inicio
     *
     * @return Partida
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     *
     * @return Partida
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set enCurso
     *
     * @param boolean $enCurso
     *
     * @return Partida
     */
    public function setEnCurso($enCurso)
    {
        $this->enCurso = $enCurso;

        return $this;
    }

    /**
     * Get enCurso
     *
     * @return bool
     */
    public function getEnCurso()
    {
        return $this->enCurso;
    }

    /**
     * @return bool
     */
    public function isFinalizada()
    {
        return $this->finalizada;
    }

    /**
     * @param bool $finalizada
     */
    public function setFinalizada($finalizada)
    {
        $this->finalizada = $finalizada;
    }

    /**
     * @return bool
     */
    public function isEmpate()
    {
        return $this->empate;
    }

    /**
     * @param bool $empate
     */
    public function setEmpate($empate)
    {
        $this->empate = $empate;
    }

    /**
     * Get finalizada
     *
     * @return boolean
     */
    public function getFinalizada()
    {
        return $this->finalizada;
    }

    /**
     * Get empate
     *
     * @return boolean
     */
    public function getEmpate()
    {
        return $this->empate;
    }

    /**
     * Set ganador
     *
     * @param \AppBundle\Entity\Jugador $ganador
     *
     * @return Partida
     */
    public function setGanador(Jugador $ganador = null)
    {
        $this->ganador = $ganador;

        return $this;
    }

    /**
     * Get ganador
     *
     * @return \AppBundle\Entity\Jugador
     */
    public function getGanador()
    {
        return $this->ganador;
    }

    /**
     * Set jugador1
     *
     * @param \AppBundle\Entity\Jugador $jugador1
     *
     * @return Partida
     */
    public function setJugador1(Jugador $jugador1 = null)
    {
        $this->jugador1 = $jugador1;

        return $this;
    }

    /**
     * Get jugador1
     *
     * @return \AppBundle\Entity\Jugador
     */
    public function getJugador1()
    {
        return $this->jugador1;
    }

    /**
     * Set jugador2
     *
     * @param \AppBundle\Entity\Jugador $jugador2
     *
     * @return Partida
     */
    public function setJugador2(Jugador $jugador2 = null)
    {
        $this->jugador2 = $jugador2;

        return $this;
    }

    /**
     * Get jugador2
     *
     * @return \AppBundle\Entity\Jugador
     */
    public function getJugador2()
    {
        return $this->jugador2;
    }
}
