<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PermisoRol;


class PermisosController extends AbstractController
{

	private $agregar   = 1;
	private $editar    = 2;
	private $eliminar  = 3;
	private $subircsv  = 4;
	private $descargar = 5;

	private $Consultor = 2;
	private $Usuario   = 3;

    /**
     * @Route("/permisos", name="permisos")
     */
    public function index()
    {
    	$permisos = $this->getDoctrine()
    					->getRepository(PermisoRol::class)
    					->findAll();
        return $this->render('permisos/index.html.twig', [
            'permisos' => $permisos,
        ]);
    }

    /**
    * @Route("/GuardarPermisos", name="GuardarPermisos")
    **/
    public function GuardarPermisos(Request $rq){
    	$em = $this->getDoctrine()->getManager();
    	
    	$ConsAdd = 		$rq->get("ConsAdd");
    	$ConsEdit = 	$rq->get("ConsEdit");
    	$ConsDel = 		$rq->get("ConsDel");
    	$ConsUpload = 	$rq->get("ConsUpload");
    	$ConsDownload = $rq->get("ConsDownload");
    	$UsuAdd = 		$rq->get("UsuAdd");
    	$UsuEdit = 		$rq->get("UsuEdit");
    	$UsuDel = 		$rq->get("UsuDel");
    	$UsuUpload = 	$rq->get("UsuUpload");
    	$UsuDownload = 	$rq->get("UsuDownload");

    	//se guardan los permisos del consultor
    	$this->guardarPermiso($this->agregar,  $this->Consultor,$ConsAdd,     $em);
    	$this->guardarPermiso($this->editar,   $this->Consultor,$ConsEdit,    $em);
    	$this->guardarPermiso($this->eliminar, $this->Consultor,$ConsDel,     $em);
    	$this->guardarPermiso($this->subircsv, $this->Consultor,$ConsUpload,  $em);
    	$this->guardarPermiso($this->descargar,$this->Consultor,$ConsDownload,$em);

    	//Se guardan los permisos del usuario
    	$this->guardarPermiso($this->agregar,  $this->Usuario,$UsuAdd,     $em);
    	$this->guardarPermiso($this->editar,   $this->Usuario,$UsuEdit,    $em);
    	$this->guardarPermiso($this->eliminar, $this->Usuario,$UsuDel,     $em);
    	$this->guardarPermiso($this->subircsv, $this->Usuario,$UsuUpload,  $em);
    	$this->guardarPermiso($this->descargar,$this->Usuario,$UsuDownload,$em);


    	$em->flush();	
    	return new JsonResponse(array());
    }

    private function guardarPermiso($id_permiso,$id_rol,$valor,$em){
    	
    					
    	if($permiso == null && $valor != null){
    		$perm = new PermisoRol();
    		$perm->setRolesId($id_rol);
    		$perm->setPermisoId($id_permiso);
    		$em->persist($perm);
	        
    	}else if($permiso != null && $valor == null){
    		$em->remove($permiso);
    	}
    }

    private function consultarPermiso($id_rol,$id_permiso){
    	$permiso = $this->getDoctrine()
    					->getRepository(PermisoRol::class)
    					->findOneBy(
    						array(
    							'roles_id'=>$id_rol,
    							'permiso_id' => $id_permiso
    						)
    					);
    	return $permiso;
    }


}
