<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpresaRepository")
 * @ORM\Table(name="empresas")
 */
class Empresa
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $pais;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_externo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

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

    public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }

    public function getPais(){
        return $this->pais;
    }

    public function setPais($pais){
        $this->pais = $pais;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function getId_externo(){
        return $this->id_externo;
    }

    public function setId_externo($id_externo){
        $this->id_externo = $id_externo;
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
