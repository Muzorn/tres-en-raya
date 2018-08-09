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
}

