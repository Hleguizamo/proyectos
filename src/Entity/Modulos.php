<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModulosRepository")
  * @ORM\Table(name="modulos")
 */
class Modulos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $aplicacion_id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_actualizacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAplicacionId(): ?int
    {
        return $this->aplicacion_id;
    }

    public function setAplicacionId(int $aplicacion_id): self
    {
        $this->aplicacion_id = $aplicacion_id;

        return $this;
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getFechaActualizacion(): ?\DateTimeInterface
    {
        return $this->fecha_actualizacion;
    }

    public function setFechaActualizacion(?\DateTimeInterface $fecha_actualizacion): self
    {
        $this->fecha_actualizacion = $fecha_actualizacion;

        return $this;
    }
}
