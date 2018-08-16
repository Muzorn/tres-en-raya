<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoFicha
 *
 * @ORM\Table(name="tipo_ficha")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoFichaRepository")
 */
class TipoFicha
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
     * @ORM\Column(name="simbolo", type="string", length=1, unique=true)
     */
    private $simbolo;


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
     * Set simbolo
     *
     * @param string $simbolo
     *
     * @return TipoFicha
     */
    public function setSimbolo($simbolo)
    {
        $this->simbolo = $simbolo;

        return $this;
    }

    /**
     * Get simbolo
     *
     * @return string
     */
    public function getSimbolo()
    {
        return $this->simbolo;
    }
}

