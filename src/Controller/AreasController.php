<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Empresa;
use App\Entity\Aplicaciones;
use App\Entity\Requerimiento;
use App\Entity\Area;
use App\Entity\EstadoRequerimiento;
use App\Entity\Modulos;
use App\Entity\Usuario;
use App\Entity\Gerencia;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Utils\OptionsBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class AreasController extends AbstractController
{
    /**
     * @Route("/areas", name="areas")
     */
    public function index()
    {

        $areas = $this->getDoctrine()->getRepository(Area::class)->findAll();
        $req = $this->getDoctrine()->getRepository(Requerimiento::class)->findAll();
        $esta = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findAll();
        $mod = $this->getDoctrine()->getRepository(Modulos::class)->findAll();
        $optBuilder = new OptionsBuilder();
        $optBuilder->getOptions($this->getDoctrine());
        $session =new  Session(new NativeSessionStorage(), new AttributeBag());
        $id_rol=$session->get('id_rol');
        if($id_rol != 1){
            $permisoAgregar = $optBuilder->consultarPermiso($id_rol,1)!=null;
        }else{
            $permisoAgregar = true;
        }
        return $this->render('requerimientos/requerimientos.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
            'js' => '',
            'permisoAgregar' => $permisoAgregar
        ]);
    }

    /**
     * @Route("/areas/crudDatas", name="areas/crudData")
     */
    public function getCrudData(){
        $gerencias = $this->getDoctrine()->getRepository(Gerencia::class)->findGerenciasOptions();

        $data = array(
            'PageTitle' => 'Areas',
            'columns' => array(
                ["data"=> "id_area",       "name" => "Id Area",       "type"=>"number", "CRUD"=> [0,1,0,0] ],
                ["data"=> "nombre_area",             "name" => "Nombre",     "type"=>"text", "CRUD"=> [1,1,1,1] ],
                
                
                ["data"=> "options",  "width"=>"200px",                  "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning btn-sm" onclick="edit(event,this)" >Editar</button>   <button type="button" class="btn btn-danger btn-sm" onclick="deleteReg(event,this)"> Eliminar </button>', "CRUD"=> [0,1,0,0] ],
                
            ),
            'dataRoute' => "getAreas",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarArea',
            'editUrl' => 'updateArea',  // url donde se mandan a editar los datos
            'deleteUrl' => 'eliminarArea',
            'getDataEdit' => 'showArea',  // url donde se consultan los datos a editar
            'idColumn' => 'id_area',   // nombre de la columna que es id para los registros    
        );
        return new JsonResponse($data);
    }

    /** 
     * @Route("/eliminarArea", name="eliminarArea")
     */
    public function eliminarArea(Request $rq){
        $id_area = $rq->get("id");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try{
            
            
            $area = $entityManager->find(Area::class,$id_area);
            $area->setEstado(0);
           
           
            $entityManager->persist($area);
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
     * @Route("/agregarArea", name="agregarArea")
     */
    public function agregarArea(Request $rq){
        $nombre = $rq->get("nombre_area");
        $gerencia = $rq->get("gerencia_id");
       
        $entityManager = $this->getDoctrine()->getManager();
        $area = new Area();
        $area->setNombre($nombre);
        //$area->setGerenciaId($gerencia);
        $area->setEstado(1);
       
        $entityManager->persist($area);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Area insertada correctamente'
        ));


    }
    /**
     * @Route("/showArea", name="showArea")
     */
    public function showArea(Request $rq){
        $id_area=$rq->get('id');
        $area = $this->getDoctrine()->getRepository(Area::class)->findAreaById($id_area);
  
        return new JsonResponse(array(
            'success' => true,
            'data' => $area,
        ));
    }

    /**
     * @Route("/updateArea", name="updateArea")
     */
    public function updateArea(Request $rq){
        $nombre = $rq->get("nombre_area");
        $gerencia = $rq->get("gerencia_id");
        $id_area=$rq->get('id');
       
        $entityManager = $this->getDoctrine()->getManager();
        $area = $entityManager->find(Area::class,$id_area);
        $area->setNombre($nombre);
        //$area->setGerenciaId($gerencia);
       
        $entityManager->persist($area);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Area actualizada correctamente'
        ));
    }

    /**
     * @Route("/getAreas", name="getAreas")
     */
    public function getAreas(){
        try {
            $result = $this->getDoctrine()->getRepository(Area::class)->findAreas();
            
            $arr = array(
                    'success' => true,
                    'msg' => 'todo funciono correctamente',
                    'datos' => $result,//json_decode($jsonContent),
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
     * @Route("/getAreasByUser/{id_usuario}", name="AreasByUser")
     */
    public function get_areas_by_user($id_usuario){
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
}
