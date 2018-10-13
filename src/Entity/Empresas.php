<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Empresas
 *
 * @ORM\Table(name="empresas")
 * @ORM\Entity
 */
class Empresas
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
     * @var string|null
     *
     * @ORM\Column(name="codigo", type="string", length=45, nullable=true)
     */
    private $codigo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pais", type="string", length=45, nullable=true)
     */
    private $pais;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_externo", type="integer", nullable=true)
     */
    private $idExterno;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Gerencias", mappedBy="empresa")
     */
    private $gerencias;

    public function __construct()
    {
        $this->gerencias = new ArrayCollection();
    }



    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getNombre(){
        return $this->nombre;
    }

    /**
     * @return Collection|Gerencias[]
     */
    public function getGerencias(): Collection
    {
        return $this->gerencias;
    }

    public function addGerencia(Gerencias $gerencia): self
    {
        if (!$this->gerencias->contains($gerencia)) {
            $this->gerencias[] = $gerencia;
            $gerencia->setEmpresa($this);
        }

        return $this;
    }

    public function removeGerencia(Gerencias $gerencia): self
    {
        if ($this->gerencias->contains($gerencia)) {
            $this->gerencias->removeElement($gerencia);
            // set the owning side to null (unless already changed)
            if ($gerencia->getEmpresa() === $this) {
                $gerencia->setEmpresa(null);
            }
        }

        return $this;
    }



}
