<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PermisoRol;
use App\Utils\OptionsBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;


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
    	$permisos[] = $this->consultarPermiso($this->Consultor,$this->agregar);
    	$permisos[] = $this->consultarPermiso($this->Consultor,$this->editar);
    	$permisos[] = $this->consultarPermiso($this->Consultor,$this->eliminar);
    	$permisos[] = $this->consultarPermiso($this->Consultor,$this->subircsv);
    	$permisos[] = $this->consultarPermiso($this->Consultor,$this->descargar);

    	$permisos[] = $this->consultarPermiso($this->Usuario,$this->agregar);
    	$permisos[] = $this->consultarPermiso($this->Usuario,$this->editar);
    	$permisos[] = $this->consultarPermiso($this->Usuario,$this->eliminar);
    	$permisos[] = $this->consultarPermiso($this->Usuario,$this->subircsv);
    	$permisos[] = $this->consultarPermiso($this->Usuario,$this->descargar);
    	$optBuilder = new OptionsBuilder();
        $optBuilder->getOptions($this->getDoctrine());
        $session =new  Session(new NativeSessionStorage(), new AttributeBag());
        $id_rol=$session->get('id_rol');
        if($id_rol != 1){
            $permisoAgregar = $optBuilder->consultarPermiso($id_rol,1)!=null;
        }else{
            $permisoAgregar = true;
        }
        return $this->render('permisos/index.html.twig', [
            'permisos' => $permisos,
            'permisoAgregar' => false
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
    	return $this->redirectToRoute('permisos');
    }

    private function guardarPermiso($id_permiso,$id_rol,$valor,$em){
    	
    	$permiso = $this->consultarPermiso($id_rol,$id_permiso);			
    	if($permiso == null && $valor != null){
    		//Se otorga el permiso
    		$perm = new PermisoRol();
    		$perm->setRolesId($id_rol);
    		$perm->setPermisoId($id_permiso);
    		$em->persist($perm);
	        
    	}else if($permiso != null && $valor == null){
    		//Se quita el permiso
    		$em->remove($permiso);
    	}
    }

    public function consultarPermiso($id_rol,$id_permiso){
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
