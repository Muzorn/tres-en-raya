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
}
