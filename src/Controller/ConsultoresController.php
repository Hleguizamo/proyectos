<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Requerimiento;

use App\Entity\EstadoRequerimiento;
use App\Entity\Modulos;
use App\Entity\Rol;
use App\Entity\Area;
use App\Entity\TipoDocumento;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use App\Utils\CsvReader;
class ConsultoresController extends AbstractController
{

	/**
     * @Route("/", name="usuarios")
     */
    public function index()
    {
        return $this->render('usuarios/index.html.twig', [
            'controller_name' => 'UsuariosController',
        ]);
    }
	 /**
     * @Route("/consultores", name="consultores")
     */
    public function consultores()
    {
        
    	$areas = $this->getDoctrine()->getRepository(Area::class)->findAll();
    	$req = $this->getDoctrine()->getRepository(Requerimiento::class)->findAll();
    	$esta = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findAll();
    	$mod = $this->getDoctrine()->getRepository(Modulos::class)->findAll();
  
        return $this->render('requerimientos/requerimientos.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
            'js' => '',
            'enableUpload' => true,
        ]);
    }

    /**
     * @Route("/consultores/crudDatas", name="consultores/crudData")
     */
    public function getCrudData(){
        $tipo_documento =  $this->getDoctrine()->getRepository(TipoDocumento::class)->findDocumento();
        $rol= $this->getDoctrine()->getRepository(Rol::class)->findRol();
         $area =  $this->getDoctrine()->getRepository(Area::class)->findAreasOption();

        $data = array(
            'PageTitle' => 'Consultores',
            'columns' => array(
                ["data"=> "id_documento",               "name" => "Tipo documentos",    "type"=>"select", "options"=>$tipo_documento, "CRUD"=> [1,0,0,0] ],
            	["data"=> "nombre_documento",           "name" => "Tipo_documento",    "type"=>"number","CRUD"=> [0,0,1,1] ],
                ["data"=> "nombre_usuario",             "name" => "Nombre",     "type"=>"text","CRUD"=> [1,1,1,1] ],
                ["data"=> "apellido_usuario",           "name" => "Apellido",     "type"=>"text","CRUD"=> [1,0,1,1] ],
                ["data"=> "numero_documento",           "name" => "Numero_documento",     "type"=>"number", "CRUD"=> [1,1,1,1] ],
                ["data"=> "email",                       "name" => "Email",     "type"=>"email","CRUD"=> [1,1,1,1] ],
                ["data"=> "area_id",                    "name" => "Area",    "type"=>"select", "options"=>$area,"CRUD"=> [1,0,0,0] ],
                ["data"=> "id_rol",                     "name" => "Rol",    "type"=>"select", "options"=> 
                                                            array(
                                                                ['value'=>'2','name'=>'Consultor']), "CRUD"=> [1,0,0,0] ],               
                ["data"=> "celular",             		"name" => "Celular",     "type"=>"number","CRUD"=> [1,0,1,1] ],
                ["data"=> "telefono",             		"name" => "Telefono",     "type"=>"number","CRUD"=> [1,0,1,1] ],
                
                ["data"=> "nombre_rol",             	"name" => "nombre_rol",    "type"=>"text","CRUD"=> [0,1,1,1] ],
               
                ["data"=> "estado",                    "name"=> "Estado",      "type"=>"select", 
                                                        "options"=> 
                                                            array(
                                                                ['value'=>'1','name'=>'Activo'],
                                                                ['value'=>'0','name'=>'Inactivo'])
                ,"CRUD"=> [1,0,1,1]],
                ["data"=> "id_usuario",       "name" => "id_usuario",       "type"=>"number", "CRUD"=> [0,0,0,0] ],
                ["data"=> "options",  "width"=>"200px",                  "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning btn-sm" onclick="edit(event,this)" >Editar</button>   <button type="button" class="btn btn-danger btn-sm" onclick="deleteReg(event,this)"> Eliminar </button>', "CRUD"=> [0,1,0,0] ],
            ),
            'dataRoute' => "getConsultores",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarUsuarios',
            'editUrl' => 'updateUsuarios',  // url donde se mandan a editar los datos
            'deleteUrl' => 'eliminarConsultor',
            'getDataEdit' => 'showUsuario',  // url donde se consultan los datos a editar
            'idColumn' => 'id_usuario',   // nombre de la columna que es id para los registros    
        );
        return new JsonResponse($data);
    }

    /** 
     * @Route("/eliminarConsultor", name="eliminarConsultor")
     */
    public function eliminarConsultor(Request $rq){
        $id_usuario = $rq->get("id");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try{
            
            $usuario = $entityManager->find(Usuario::class,$id_usuario);
        
            $usuario->setEstado(0);
        
            $entityManager->persist($usuario);
            $entityManager->flush();
     
            $data = array('success' => true);
            $entityManager->getConnection()->commit();
        }catch(Exception $e){
            $entityManager->getConnection()->rollBack();
            $data = array('success'=>false);
        }
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Requerimiento actualizado correctamente'
        ));

    }
   
   
               
               
    /**
     * @Route("/agregarUsuarios", name="agregarUsuarios")
     */
     public function agregarUsuarios(Request $rq){
        $session =new  Session(new NativeSessionStorage(), new AttributeBag());
        $nombre = $rq->get("nombre_usuario");
        $apellido = $rq->get("apellido_usuario");
        $rol   = $rq->get("id_rol");
        $celular = $rq->get("celular");
        $telefono = $rq->get("telefono");
        $area = $rq->get("area_id");
        $email   = $rq->get("email");
        $estado = $rq->get("estado");
        $num_doc = $rq->get("numero_documento");
        $tipo_doc = $rq->get("id_documento");
        $id_empresa=$session->get('id_empresa');
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = new Usuario();
        $usuario->setEmpresaId($id_empresa);
        $usuario->setRolId($rol);

        $usuario->setTipoDocumentoId($tipo_doc);
        $usuario->setEstado($estado);
        $usuario->setNumeroDocumento($num_doc);
        $usuario->setNombres($nombre);
        $usuario->setApellidos($apellido);
        $usuario->setCelular($celular);
        $usuario->setTelefono($telefono);
        $usuario->setAreaId($area);
        $usuario->setEmail($email);

        
        $entityManager->persist($usuario);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Usuario insertada correctamente'
        ));


    }

    /**
     * @Route("/showUsuario", name="showUsuario")
     */
    public function showUsuario(Request $rq){
        $id_usuario= $rq->get('id');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findUsuarioById($id_usuario);
        return new JsonResponse(array(
            'success' => true,
            'data' => $usuario
        ));
    }

    /**
     * @Route("/updateUsuarios", name="updateUsuarios")
     */
     public function updateUsuarios(Request $rq){
        $session =new  Session(new NativeSessionStorage(), new AttributeBag());
        $id_usuario= $rq->get('id');
        $nombre = $rq->get("nombre_usuario");
        $apellido = $rq->get("apellido_usuario");
        $rol   = $rq->get("id_rol");
        $celular = $rq->get("celular");
        $telefono = $rq->get("telefono");
        $area = $rq->get("area_id");
        $email   = $rq->get("email");
        $estado = $rq->get("estado");
        $num_doc = $rq->get("numero_documento");
        $tipo_doc = $rq->get("id_documento");
        $id_empresa=$session->get('id_empresa');
        $entityManager = $this->getDoctrine()->getManager();

        $usuario = $entityManager->find(Usuario::class,$id_usuario);
        $usuario->setEmpresaId($id_empresa);
        $usuario->setRolId($rol);
        $usuario->setTipoDocumentoId($tipo_doc);
        $usuario->setEstado($estado);
        $usuario->setNumeroDocumento($num_doc);
        $usuario->setNombres($nombre);
        $usuario->setApellidos($apellido);
        $usuario->setCelular($celular);
        $usuario->setTelefono($telefono);
        $usuario->setAreaId($area);
        $usuario->setEmail($email);

        
        $entityManager->persist($usuario);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Usuario actualizado correctamente'
        ));


    }


    /**
     * @Route("/getConsultores", name="getConsultores")
     */
    public function getConsultores(){
        try {


            /*$areas = $this->getDoctrine()->getRepository(Empresas::class)->findAll();
            $serializer = $serializer = JMS\Serializer\SerializerBuilder::create()->build();
            $dat=$serializer->serialize($areas, "post");*/
            /*$encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);*/
            $gerencia = $this->getDoctrine()->getRepository(Usuario::class)->findUsuario(2);
            //dd($empr);
            
            //$jsonContent = $serializer->serialize($empr, 'json');
            //$data = $serializer->deserialize($inputStr, $typeName, $format);
            //$entityManager = $this->getDoctrine()->getManager();
            //$empresa = new Empresas;
            //$empresa->setNombre("pepito");
            $arr = array(
                    'success' => true,
                    'msg' => 'todo funciono correctamente',
                    'datos' => $gerencia,//json_decode($jsonContent),
                );
              
           
        } catch (Exception $e) {
            

            $arr = array(
                    'success' => false,
                    'msg' => 'error',
                    'datos' => null,
                );
           
        }
        return new JsonResponse($arr);

    }
    
    /**
     * @Route("/cerrar", name="cerrars")
     */
    public function cerrar()
    {
       $session =new  Session(new NativeSessionStorage(), new AttributeBag());
       $session->invalidate();
       return $this->redirectToRoute('usuarios');
    }
    /**
     * @Route("/login", name="Log")
     */
    public function login(Request $request){
    	 $cc=$request->get('cc');
    	 $session =new  Session(new NativeSessionStorage(), new AttributeBag());
    	 //$session->start();

    	 try {
	    	$data = $this->getDoctrine()->getRepository(Usuario::class)->Login($cc);
	    	 
	    	//creamos token

	    	$token=rand();
	    	$valido=count($data)>0;
	    	$data['token']=count($data)>0? $token : null;
	    	$this->getDoctrine()->getRepository(Usuario::class)->SetToken($token,$cc);
	    	$arr = array(
	                'success' => count($data)>0,
	                'msg' => count($data)>0? 'Usuario valido' : 'Usuario invalido',
	                'datos' => $data,
	            );
	    	
	    	if($valido){
	    	
	    		$session->set('rol_usuario',$data[0]['nombre_rol']);
	    		$session->set('nombre_usuario',$data[0]['nombre_usuario']." ".$data[0]['apellido_usuario']);
	    		$session->set('id_usuario',$data[0]['id_usuario']);
                $session->set('id_empresa',$data[0]['id_empresa']);
	    	    $session->set('id_rol',$data[0]['id_rol']);



	    	
	    	
	    		return $this->redirectToRoute('requerimientos');
	    	}else{
	    		return $this->redirectToRoute('usuarios');
	    	}
	    	
	    	
	    } catch (Exception $e) {
	    	 $arr = array(
	                'success' => count($data)>0,
	                'msg' => 'error',
	                'datos' => null,
	            );
	    }
    	
    	

    }

    /**
     * @Route("/getNombre/{id_usuario}", name="ObtenerNombre")
     */
    public function get_nombre($id_usuario){
    	$arr = array();
	    try {
	    	$data = $this->getDoctrine()->getRepository(Area::class)->getAreasByUser($id_usuario);
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $data,
	            );
	    	
	    } catch (Exception $e) {
	    	 $arr = array(
	                'success' => false,
	                'msg' => 'error',
	                'datos' => null,
	            );
	    }
    	return new JsonResponse($arr);
    }


    public function validarRegistroCsv($entityManager,$registro){
        return array(true,'error predefinido');
    }

    public function guardarRegistroCsv($entityManager,$registro){
        
            /*
                fecha crea                   '19/10/18'             
                numero_requerimiento,        '569999999'
                descripcion,                 'requerimiento de prueba plano 1'
                modulo_id,                   '1'
                estado_requerimientos_id,    '2'
                fecha_asignacion,            '29/08/18'
                fecha_estimada_entrega,      '30/08/18'
                fecha_cierre,                '31/08/18'
                observaciones,               'Observación'
                consultor_id,                '1'
                usuario_id                   '1'
            */

          


            $usuario = new Usuario();
            $usuario->setNombres($registro[0]);
            $usuario->setApellidos($registro[1]);
            $usuario->setNumeroDocumento($registro[2]);
            $usuario->setEmail($registro[3]);
            $usuario->setAreaId($registro[4]);
            $usuario->setTipoDocumentoId($registro[5]);  
            $usuario->setEmpresaId($registro[6]);
            $usuario->setRolId($registro[7]);        
            $usuario->setEstado($registro[8]);
            $usuario->setCelular($registro[9]);         
            $usuario->setTelefono($registro[10]);
            $usuario->setTelefono($registro[11]);

          
            
            $entityManager->persist($usuario);
            $entityManager->flush();

        }

    /**
     * @Route("usuarios/readCsv2", name="usuarios/readCsv2")
     */
    public function readCsv2(Request $r){

        $dir_subida  = $this->getParameter('kernel.project_dir').'/assets/csv/';
        $fichero_subido = $dir_subida . basename($_FILES['File']['name']);
        $uploadOk = 1;
        if (move_uploaded_file($_FILES['File']['tmp_name'], $fichero_subido)) {
            $entityManager = $this->getDoctrine()->getManager();
            $lector = new CsvReader();
            $lector->setParameters(
                    $fichero_subido,
                    $entityManager,
                    $this
                );
            try{
                $response = $lector->readCsv();
            }catch(Exception $e){
                $response = array(false,'Parametros insuficientes para leer el archivo');
            }
            $arr = array(
                    'success' => $response['success'],
                    'msg' => $response['success']? 'Fichero importado exitosamente' : 'Errores al subir el fichero',
                    'errors' => $response['errors']
                  
                );
            //unlink($fichero_subido);
        } else {
             $arr = array(
                    'success' => false,
                    'msg' => 'Error al intentar mover el fichero',

                );
        }
    

        return new JsonResponse($arr);
        
        
    }
}