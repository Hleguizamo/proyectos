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
     * @ORM\Column(type="datetime")
     */
    private $fecha_actualizacion;

   

    
}
