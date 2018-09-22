<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrazabilidadRequerimientoRepository")
 */
class TrazabilidadRequerimiento
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
    private $requerimiento_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $usuario_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado_requerimiento_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $observacion;

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

    public function getRequerimientoId(): ?int
    {
        return $this->requerimiento_id;
    }

    public function setRequerimientoId(int $requerimiento_id): self
    {
        $this->requerimiento_id = $requerimiento_id;

        return $this;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(int $usuario_id): self
    {
        $this->usuario_id = $usuario_id;

        return $this;
    }

    public function getEstadoRequerimientoId(): ?int
    {
        return $this->estado_requerimiento_id;
    }

    public function setEstadoRequerimientoId(int $estado_requerimiento_id): self
    {
        $this->estado_requerimiento_id = $estado_requerimiento_id;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(string $observacion): self
    {
        $this->observacion = $observacion;

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
