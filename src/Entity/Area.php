<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AreaRepository")
 * @ORM\Table(name="areas")
 */
class Area
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
    * @ORM\Column(type="integer")
    */
    private $estado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_actualizacion;


    public function  getEstado(): ?int
    {
        return $this->estado;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
    public function  setEstado(int $estado): self
    {

        $this->estado=$estado;
        return $this;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(string $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getFechaActualizacion(): ?string
    {
        return $this->fecha_actualizacion;
    }

    public function setFechaActualizacion(?string $fecha_actualizacion): self
    {
        $this->fecha_actualizacion = $fecha_actualizacion;

        return $this;
    }
}
