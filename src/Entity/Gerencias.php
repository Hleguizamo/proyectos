<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gerencias
 *
 * @ORM\Table(name="gerencias", indexes={@ORM\Index(name="fk_gerencias_empresas_idx", columns={"empresas_id"})})
 * @ORM\Entity
 */
class Gerencias
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Empresas", inversedBy="gerencias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;

    public function getEmpresa(): ?Empresas
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresas $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

   
 
    


}
