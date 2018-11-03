<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GerenciaRepository")
 * @ORM\Table(name="gerencias")
 */
class Gerencia
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

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function  setEstado(int $estado): self
    {

        $this->estado=$estado;
        return $this;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_actualizacion;

        public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getFecha_creacion(){
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion){
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_actualizacion(){
        return $this->fecha_actualizacion;
    }

    public function setFecha_actualizacion($fecha_actualizacion){
        $this->fecha_actualizacion = $fecha_actualizacion;
    }

   

    
}
