<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use App\Entity\Requerimiento;

use App\Entity\Area;
use App\Entity\EstadoRequerimiento;
use App\Entity\Modulos;
use App\Entity\Usuario;
use App\Entity\Aplicacion;
use App\Entity\Gerencia;
use App\Entity\TrazabilidadRequerimiento;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SeguimientoController extends AbstractController
{


    /**
     * @Route("/seguimientos", name="seguimientos")
     */
    public function index()
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
            'js' => 'req.js',
        ]);
    }
    /**
     * @Route("/seguimientos/crudDatas", name="seguimientos/crudData")
     */
    public function getCrudData(){
    	$session =new  Session(new NativeSessionStorage(), new AttributeBag());
    	$id_rol=$session->get('id_rol');
    	$apps = $this->getDoctrine()->getRepository(Aplicacion::class)->findAplicacionesOptions();
    	$mod = $this->getDoctrine()->getRepository(Modulos::class)->findModulosOptions();
    	$gerencias = $this->getDoctrine()->getRepository(Gerencia::class)->findGerenciasOptions();
    	$areas =  $this->getDoctrine()->getRepository(Area::class)->findAreasOption();
    	$estados =  $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findEstadoRequerimientoOption();
    	$consultores =  $this->getDoctrine()->getRepository(Usuario::class)->getUsersByRolOption(2);
    	$usuarios =  $this->getDoctrine()->getRepository(Usuario::class)->getUsersByRolOption(3);

    	
    	$data = array(
    		'PageTitle' => 'Seguimiento a los requerimientos',

    		'columns' => array(
    			["data"=> "fecha_creacion", 		"name" => "Fecha Creación",		"type"=>"date", "CRUD"=> [0,1,1,1] ],
    			["data"=> "numero_requerimiento", 	"name"=> "# Requerimiento",		"type"=>"text", "CRUD"=> [1,1,1,1] ],
    			["data"=> "id_requerimiento", 		"name" => "id_requerimiento",		"type"=>"number", "CRUD"=> [0,1,0,0] ],
    			["data"=> "descripcion", 			"name"=> "Descripción",			"type"=>"text", "CRUD"=> [1,1,1,1] ],
    			["data"=> "nombre_aplicacion", 		"name"=> "Aplicación",			"type"=>"select", "options"=> $apps, 	  "CRUD"=> [0,1,1,1] ],
		        
		        
		        
		        ["data"=> "nombre_modulo", 			"name"=> "Módulo",				"type"=>"select", "options"=> $mod,  	  "CRUD"=> [1,1,1,1] ],
		        ["data"=> "nombre_gerencia", 		"name"=> "Gerencia",			"type"=>"select", "options"=> $gerencias, "CRUD"=> [0,1,1,1] ],
		        ["data"=> "nombre_area", 			"name"=> "Área",				"type"=>"select", "options"=> $areas,     "CRUD"=> [0,1,1,1] ],
		        ["data"=> "nombre_usuario", 		"name"=> "Usuario", 			"type"=>"select", "options"=> $usuarios,        "CRUD"=> [1,1,1,1] ],
		        ["data"=> "nombre_consultor", 		"name"=> "Consultor",			"type"=>"select", "options"=> $consultores,     "CRUD"=> [1,1,1,1] ],
		        ["data"=> "estado_requerimiento", 	"name"=> "Estado Req.",			"type"=>"select", "options"=> $estados,   "CRUD"=> [1,1,1,1] ],
		        ["data"=> "fecha_asignacion", 		"name"=> "Fecha Asignación",	"type"=>"date", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "fecha_estimada_entrega", "name"=> "Fecha Entrega",		"type"=>"date", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "fecha_cierre", 			"name"=> "Fecha Cierre",		"type"=>"date", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "observaciones", 			"name"=> "Observaciones",		"type"=>"text", "CRUD"=> [1,1,1,1] ],
		        
		        
		        ["data"=> "options",                    "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning" onclick="edit(event,this)" >Editar</button> ', "CRUD"=> [0,1,0,0] ],
    		),
    		'dataRoute' => "misSeguiemiento",
    		'dataSrc' => "datos",
    		'dist' => '4-cols',
    		'saveUrl' => 'agregarRequerimiento', //url donde se mandan a guardar los datos  
    		'editUrl' => 'editarRequerimiento',  // url donde se mandan a editar los datos
    		'getDataEdit' => 'datosEditarReqs',  // url donde se consultan los datos a editar
    		'idColumn' => 'id_requerimiento',  	// nombre de la columna que es id para los registros	
    		'buttons' => ['Estado'=>'mostrarCambiarEstado()']

    	);
    	
    	return new JsonResponse($data);
    }

    /** 
     * @Route("/editarRequerimiento", name="editarRequerimiento")
     */
     public function editarRequerimiento(Request $rq){
     	$id = $rq->get("id");

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->getConnection()->beginTransaction();
    	try{
	    	
	    	$req = $entityManager->find(Requerimiento::class,$id);
	    	$req->setNumeroRequerimiento($rq->get("numero_requerimiento"));
	    	$req->setDescripcion($rq->get("descripcion"));
	    	$req->setModuloId($rq->get("nombre_modulo"));
	    	//$req->setAplicacionId($rq->get("nombre_aplicacion"));
	    	//$req->setGerenciaId($rq->get("nombre_gerencia"));
	    	//$req->setAreaId($rq->get("nombre_area"));
	    	
	    	$req->setEstadoRequerimientosId($rq->get("estado_requerimiento"));
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_estimada_entrega"));
	    	$req->setFechaEntrega($objDT);
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_asignacion"));
	    	$req->setFechaAsigna($objDT);
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_cierre"));
	    	$req->setFechaCierre($objDT);
	    	$fecha=date("Y-m-d");

	    	$objDT = \DateTime::createFromFormat('Y-m-d', $fecha);
	    	//dd($objDT);
	    	$req->setFechaCreacion($objDT);
	    	$req->setObservacion($rq->get("observaciones"));
	    	$entityManager->persist($req);
	        $entityManager->flush();

	        $trazaRq = new TrazabilidadRequerimiento();
	        $trazaRq->setRequerimientoId($req->getId());
	        $trazaRq->setUsuarioId($rq->get("nombre_usuario"));
	        $trazaRq->setEstadoRequerimientoId($req->getEstadoRequerimientosId());
	        $trazaRq->setObservacion($req->getObservacion());
	        $entityManager->persist($trazaRq);

	        
	        $trazaRq = new TrazabilidadRequerimiento();
	        $trazaRq->setRequerimientoId($req->getId());
	        $trazaRq->setUsuarioId($rq->get("nombre_consultor"));
	        $trazaRq->setEstadoRequerimientoId($req->getEstadoRequerimientosId());
	        $trazaRq->setObservacion($req->getObservacion());
	        $entityManager->persist($trazaRq);
			
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
     * @Route("/datosEditarReqs", name="datosEditarReqs")
     */
    public function datosEditarReqs(Request $rq){
    	$id = $rq->get("id");
    	$entityManager = $this->getDoctrine()->getManager();
    	$data = $this->getDoctrine()->getRepository(Requerimiento::class)->getRequerimientosGridEdit($id);
    	return new JsonResponse(array('success'=>true, 'data' => $data));
    }

    /*

     
     * ("/agregarRequerimiento", name="agregarRequerimiento")
     
    public function agregarRequerimiento(Request $rq){
    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->getConnection()->beginTransaction();
    	try{
	    	
	    	$req = new Requerimiento();
	    	$req->setNumeroRequerimiento($rq->get("numero_requerimiento"));
	    	$req->setDescripcion($rq->get("descripcion"));
	    	$req->setModuloId($rq->get("nombre_modulo"));
	    	//$req->setAplicacionId($rq->get("nombre_aplicacion"));
	    	//$req->setGerenciaId($rq->get("nombre_gerencia"));
	    	//$req->setAreaId($rq->get("nombre_area"));
	    	
	    	$req->setEstadoRequerimientosId($rq->get("estado_requerimiento"));
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_estimada_entrega"));
	    	$req->setFechaEntrega($objDT);
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_asignacion"));
	    	$req->setFechaAsigna($objDT);
	    	$objDT = \DateTime::createFromFormat('Y-m-d', $rq->get("fecha_cierre"));
	    	$req->setFechaCierre($objDT);
	    	$fecha=date("Y-m-d");

	    	$objDT = \DateTime::createFromFormat('Y-m-d', $fecha);
	    	//dd($objDT);
	    	$req->setFechaCreacion($objDT);
	    	$req->setObservacion($rq->get("observaciones"));
	    	$entityManager->persist($req);
	        $entityManager->flush();

	        $trazaRq = new TrazabilidadRequerimiento();
	        $trazaRq->setRequerimientoId($req->getId());
	        $trazaRq->setUsuarioId($rq->get("nombre_usuario"));
	        $trazaRq->setEstadoRequerimientoId($req->getEstadoRequerimientosId());
	        $trazaRq->setObservacion($req->getObservacion());
	        $entityManager->persist($trazaRq);

	        
	        $trazaRq = new TrazabilidadRequerimiento();
	        $trazaRq->setRequerimientoId($req->getId());
	        $trazaRq->setUsuarioId($rq->get("nombre_consultor"));
	        $trazaRq->setEstadoRequerimientoId($req->getEstadoRequerimientosId());
	        $trazaRq->setObservacion($req->getObservacion());
	        $entityManager->persist($trazaRq);
			
	        $entityManager->flush();
	        $data = array('success' => true);
	        $entityManager->getConnection()->commit();
	    }catch(Exception $e){
	    	$entityManager->getConnection()->rollBack();
	    	$data = array('success'=>false);
	    }
    	return new JsonResponse($data);
    }*/
    

    /**
     * @Route("/misRequerimientosById/{id_usuario}", name="requerimientosById")
     */
    public function mis_requerimientos_by_id($id_usuario)
    {
    	$session =new  Session(new NativeSessionStorage(), new AttributeBag());
    	$id_usuario = $session->get('id_usuario');
    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->getRequerimientos($id_usuario);
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/misSeguiemiento", name="misSeguiemiento")
     */
    public function mis_requerimientos_by_id2()
    {
    	$session =new  Session(new NativeSessionStorage(), new AttributeBag());
    	$id_usuario = $session->get('id_usuario');//id_rol
    	$id_rol = $session->get('id_rol');//
    	
    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->getRequerimientosGrid($id_usuario,$id_rol);
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/misRequerimientosAll", name="requerimientosAll")
     */
    public function mis_requerimientos_all()
    {

    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->getRequerimientosAll();
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/filtroRequerimientosById/{id_usuario}/{id_area}/{id_requerimiento}/{id_estado}/{id_modulo}", name="filtroId")
     */
    public function filtro_requerimientos_by_id($id_usuario,$id_area,$id_requerimiento,$id_estado,$id_modulo){
    	$session =new  Session(new NativeSessionStorage(), new AttributeBag());
    	$id_usuario = $session->get('id_usuario');
    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->filtrarRequerimientos($id_usuario,$id_area,$id_requerimiento,$id_estado,$id_modulo);
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/filtroRequerimientosAll/{id_area}/{id_requerimiento}/{id_estado}/{id_modulo}", name="filtroAll")
     */
    public function filtro_requerimientos_all($id_area,$id_requerimiento,$id_estado,$id_modulo){
    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->filtrarRequerimientosAll($id_area,$id_requerimiento,$id_estado,$id_modulo);
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/verAvance/{id_usuario}", name="avanceRequerimientos")
     */
    public function ver_avance($id_usuario){
    	$empr = $this->getDoctrine()->getRepository(Requerimiento::class)->getRequerimientos($id_usuario);
	    try {
	    	 $arr = array(
	                'success' => true,
	                'msg' => 'Todo funciono correctamente',
	                'datos' => $empr,
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
     * @Route("/ListaEstados", name="ListaEstados")
     */
    public function obtenerEstados(){
    	$estados =  $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findEstadoRequerimientoOption();
    	return new JsonResponse(array('estados'=>$estados));
    }
    /**
     * @Route("/UpdateStates", name="UpdateStates")
     */
    public function updateState(Request $rq){
    	$id = $rq->get("estado");
    	$req = $rq->get("req");

    	$entityManager = $this->getDoctrine()->getManager();
    	$RQ = $this->getDoctrine()->getRepository(Requerimiento::class)->findOneBy(['numero_requerimiento' => $req]);
    	$RQ->setEstadoRequerimientosId($id);
    	$entityManager->flush();
    	return new JsonResponse($RQ);
    }
    /**
     * @Route("/readCsv", name="readCsv")
     */
    public function readCsv(){
    	$linea = 0;
		//Abrimos nuestro archivo
		$archivo = fopen($this->getParameter('kernel.project_dir').'/assets/req.csv', "r");
		//Lo recorremos
		//var_dump($archivo);
		$data = array();
		$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->getConnection()->beginTransaction();
    	try{
			while (($datos = fgetcsv($archivo,5000, ";")) == true) 
			{
			  $this->getDoctrine()->getRepository(Requerimiento::class)->uploadCsv($datos);
			}
			//Cerramos el archivo
			fclose($archivo);
			//echo $this->getParameter('kernel.project_dir').'/assets/req.csv';
			$entityManager->getConnection()->commit();
			return new JsonResponse(array('success'=>true));
	    }catch(Exception $e){
	    	$entityManager->getConnection()->rollBack();
	    	return new JsonResponse(array('success'=>false));
		}
		
    }
}
