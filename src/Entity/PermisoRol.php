<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermisoRolRepository")
 */
class PermisoRol
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
    private $roles_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $permiso_id;

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

    public function getRolesId(): ?int
    {
        return $this->roles_id;
    }

    public function setRolesId(int $roles_id): self
    {
        $this->roles_id = $roles_id;

        return $this;
    }

    public function setPermisoId(int $permiso_id): self
    {
        $this->permiso_id = $permiso_id;

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
