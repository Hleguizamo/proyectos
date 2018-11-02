<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequerimientoRepository")
  * @ORM\Table(name="requerimientos")
 */
class Requerimiento
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
    private $estado_requerimientos_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $modulo_id;

    /**
     * @ORM\Column(name="numero_requerimiento", type="string", length=45)
     */
    private $numero_requerimiento;


    /**
     * @ORM\Column(name="descripcion", type="string", length=45)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="fecha_asignacion", type="datetime")
     */
    private $fecha_asignacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_cierre;

    /**
     * @ORM\Column(name="observaciones",type="string", length=100)
     * 
     */
    private $observacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_creacion;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_estimada_entrega;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_actualizacion;

    /**
     * @ORM\Column(name="estado", type="integer", options={"default" : 1})
     */
    private $estado;



    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstadoRequerimientosId(): ?int
    {
        return $this->estado_requerimientos_id;
    }

    public function setEstadoRequerimientosId(int $estado_requerimientos_id): self
    {
        $this->estado_requerimientos_id = $estado_requerimientos_id;

        return $this;
    }

    public function getModuloId(): ?int
    {
        return $this->modulo_id;
    }

    public function setModuloId(int $modulo_id): self
    {
        $this->modulo_id = $modulo_id;

        return $this;
    }

    public function getNumeroRequerimiento(): ?string
    {
        return $this->numerorequerimiento;
    }

    public function setNumeroRequerimiento(string $numero_requerimiento): self
    {
        $this->numero_requerimiento = $numero_requerimiento;

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

    public function getFechaAsigna(): ?\DateTimeInterface
    {
        return $this->fecha_asigna;
    }

    public function setFechaAsigna($fecha_asigna): self
    {
        $this->fecha_asignacion = $fecha_asigna;

        return $this;
    }

    public function getFechaCierre(): ?\DateTimeInterface
    {
        return $this->fecha_cierre;
    }

    public function setFechaCierre($fecha_cierre): self
    {
        $this->fecha_cierre = $fecha_cierre;

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
    
    public function getFechaEntrega(): ?\DateTimeInterface
    {
        return $this->fecha_estimada_entrega;
    }

    public function setFechaEntrega($fecha_estimada_entrega): self
    {
        $this->fecha_estimada_entrega = $fecha_estimada_entrega;

        return $this;
    }

    public function setEstado($estado): self
    {
        $this->estado=$estado;
        return $this;
    }
    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion): self
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
