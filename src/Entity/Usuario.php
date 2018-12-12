<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
  * @ORM\Table(name="usuarios")
 */
class Usuario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
     /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;
        /**
     * @ORM\Column(type="integer")
     */
    private $area_id;

    /**
     * @ORM\Column(name="gerencia_id", type="integer")
     */
    private $gerencia_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $empresa_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rol_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipo_documento_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $numero_documento;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_externo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_actualizacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }
     public function getToken(): ?int
    {
        return $this->token;
    }

    public function getEmpresaId(): ?int
    {
        return $this->empresa_id;
    }

    public function setEmpresaId(int $empresa_id): self
    {
        $this->empresa_id = $empresa_id;

        return $this;
    }

    public function getRolId(): ?int
    {
        return $this->rol_id;
    }

    public function setRolId(int $rol_id): self
    {
        $this->rol_id = $rol_id;

        return $this;
    }

    public function setToken(int $token): self
    {
        $this->token = $token;

        return $this;
    }

      public function getAreaId(): ?int
    {
        return $this->area_id;
    } 

    public function getGerenciaId(): ?int
    {
        return $this->gerenica_id;
    } 
   
    public function getTipoDocumentoId(): ?int
    {
        return $this->tipo_documento_id;
    }

    public function setTipoDocumentoId(int $tipo_documento_id): self
    {
        $this->tipo_documento_id = $tipo_documento_id;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

       public function setAreaId(string $area_id): self
    {
        $this->area_id=$area_id;

        return $this;
    }


    public function setGerenciaId(string $gerenica_id): self
    {
        $this->gerencia_id = $gerenica_id;

        return $this;
    }


    public function getNumeroDocumento(): ?string
    {
        return $this->numero_documento;
    }

    public function setNumeroDocumento(string $numero_documento): self
    {
        $this->numero_documento = $numero_documento;

        return $this;
    }

    public function getIdExterno(): ?int
    {
        return $this->id_externo;
    }

    public function setIdExterno(int $id_externo): self
    {
        $this->id_externo = $id_externo;

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

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(string $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
