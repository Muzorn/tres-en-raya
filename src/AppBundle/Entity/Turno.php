<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TurnoRepository")
 */
class Turno
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
     * Varios turnos se juegan en una Partida.
     * @ORM\ManyToOne(targetEntity="Partida", inversedBy="turnos")
     * @ORM\JoinColumn(name="partida_id", referencedColumnName="id")
     */
    private $partida;

    /**
     * Varios turnos son jugados por un Jugador.
     * @ORM\ManyToOne(targetEntity="Jugador", inversedBy="turnos")
     * @ORM\JoinColumn(name="jugador_id", referencedColumnName="id")
     */
    private $jugadoPor;

    /**
     * En un Turno se pone una Ficha.
     * @ORM\OneToOne(targetEntity="Ficha")
     * @ORM\JoinColumn(name="ficha_id", referencedColumnName="id")
     */
    private $ficha;


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
     * Set partida
     *
     * @param \AppBundle\Entity\Partida $partida
     *
     * @return Turno
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
     * Set jugadoPor
     *
     * @param \AppBundle\Entity\Jugador $jugadoPor
     *
     * @return Turno
     */
    public function setJugadoPor(\AppBundle\Entity\Jugador $jugadoPor = null)
    {
        $this->jugadoPor = $jugadoPor;

        return $this;
    }

    /**
     * Get jugadoPor
     *
     * @return \AppBundle\Entity\Jugador
     */
    public function getJugadoPor()
    {
        return $this->jugadoPor;
    }

    /**
     * Set ficha
     *
     * @param \AppBundle\Entity\Ficha $ficha
     *
     * @return Turno
     */
    public function setFicha(\AppBundle\Entity\Ficha $ficha = null)
    {
        $this->ficha = $ficha;

        return $this;
    }

    /**
     * Get ficha
     *
     * @return \AppBundle\Entity\Ficha
     */
    public function getFicha()
    {
        return $this->ficha;
    }
}
