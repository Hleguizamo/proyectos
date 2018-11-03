<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use App\Entity\PermisoRol;



class OptionsBuilder{

	private $editPermissionId = 2;
	private $deletePermissionId = 3;

	private $editButton = '<button class="editor_edit btn btn-warning btn-sm" onclick="edit(event,this)" >Editar</button> ';

	private $deleteButton = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteReg(event,this)"> Eliminar </button>';

	private $rol_id;

	private $defaultWidth = "200px";

	private $defaultCrud = [0,1,0,0];

	private $session = null;

	private $allowEdit = false;
	private $allowDelete = false;

	private $doctrine = null;

	
	public function getOptions($doctrine){
		$this->doctrine = $doctrine;
		$this->setSessionData();
		$this->setPermisos();
		return $this->buildOptions();
	}

	private function buildOptions(){
		return [
			"data"=> "options",  
			"width"=>$this->defaultWidth,                  
			"name"=> "Opciones" , 
			"defaultContent"=> $this->getButtons(),
			"CRUD"=> $this->defaultCrud,
		];
	}

	private function getButtons(){
		$buttons = $this->allowEdit? $this->editButton : ' ';
		$buttons = $this->allowDelete? $buttons.' '.$this->deleteButton : $buttons;
		return $buttons;
	}

	private function setSessionData(){
		$this->session = new  Session(new NativeSessionStorage(), new AttributeBag());
    	$this->rol_id = $this->session->get('id_rol');
	}

	private function setPermisos(){
		if($this->rol_id == 1){
			//Si es un administrador tiene todos los permisos
			$this->allowEdit = true;
			$this->allowDelete =  true;
		}else{
			//Para el consultor y el usuario se averiguan los permisos
			$perm = $this->consultarPermiso($this->rol_id,$this->editPermissionId);
    		$this->allowEdit = $perm != null;
    		$perm = $this->consultarPermiso($this->rol_id,$this->deletePermissionId);
    		$this->allowDelete =  $perm != null;
		}
    	

    
    }

    public function consultarPermiso($id_rol,$id_permiso){
    	$permiso = $this->doctrine
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