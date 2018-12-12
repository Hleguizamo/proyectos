<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Empresa;
use App\Entity\Aplicacion;
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

class AplicacionController extends AbstractController
{
    /**
     * @Route("/aplicaciones", name="aplicaciones")
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
     * @Route("/aplicaciones/crudDatas", name="aplicaciones/crudData")
     */
    public function getCrudData(){
        $area =  $this->getDoctrine()->getRepository(Area::class)->findAreasOption();

        $data = array(
            'PageTitle' => 'Aplicaciones',
            'columns' => array(
                ["data"=> "id_aplicacion",       "name" => "Id Aplicación",       "type"=>"number", "CRUD"=> [0,1,0,0] ],
                ["data"=> "name",             "name" => "Nombre",     "type"=>"text", "CRUD"=> [1,1,1,1] ],
                
                
                ["data"=> "options",  "width"=>"200px",                  "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning btn-sm" onclick="edit(event,this)" >Editar</button>   <button type="button" class="btn btn-danger btn-sm" onclick="deleteReg(event,this)"> Eliminar </button>', "CRUD"=> [0,1,0,0] ],
                
            ),
            'dataRoute' => "getAplicacion",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarAplicacion',
            'editUrl' => 'updateAplicacion',  // url donde se mandan a editar los datos
            'deleteUrl' => 'eliminarAplicacion', // Url donde se manda a eliminar un registro
            'getDataEdit' => 'showAplicacion',  // url donde se consultan los datos a editar
            'idColumn' => 'id_aplicacion',   // nombre de la columna que es id para los registros
        );
        return new JsonResponse($data);
    }

    /** 
     * @Route("/eliminarAplicacion", name="eliminarAplicacion")
     */
    public function eliminarAplicacion(Request $rq){
        $id_aplicacion = $rq->get("id");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try{
            
            $aplicacion = $entityManager->find(Aplicacion::class,$id_aplicacion);
            $aplicacion->setEstado(0);
            
            $entityManager->persist($aplicacion);
            
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
     * @Route("/agregarAplicacion", name="agregarAplicacion")
     */
    public function agregarAplicacion(Request $rq){
        $nombre = $rq->get("name");
        //$area = $rq->get("area_id");
       
        $entityManager = $this->getDoctrine()->getManager();
        $aplicacion = new Aplicacion();
        $aplicacion->setNombre($nombre);
        //$aplicacion->setAreaId($area);
        $aplicacion->setEstado(1);
       
        $entityManager->persist($aplicacion);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Aplicacion insertada correctamente'
        ));


    }

    /**
     * @Route("/showAplicacion", name="showAplicacion")
     */
    public function showAplicacion(Request $rq){
        $id=$rq->get("id");
        $aplicacion = $this->getDoctrine()->getRepository(Aplicacion::class)->findAplicacionById($id);
        return new JsonResponse(array(
            'success' => true,
            'data' => $aplicacion,
        ));
    }

    /**
     * @Route("/updateAplicacion", name="updateAplicacion")
     */
    public function updateAplicacion(Request $rq){
        $nombre = $rq->get("name");
        //$area = $rq->get("area_id");
        $id_aplicacion=$rq->get("id");
       
        $entityManager = $this->getDoctrine()->getManager();
        $aplicacion = $entityManager->find(Aplicacion::class,$id_aplicacion);
        $aplicacion->setNombre($nombre);
        //$aplicacion->setAreaId($area);
       
        $entityManager->persist($aplicacion);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Aplicacion actualizada correctamente'
        ));


    }

    /**
     * @Route("/getAplicacion", name="getAplicacion")
     */
    public function getAplicacion(){
        try {
            $result = $this->getDoctrine()->getRepository(Aplicacion::class)->findAplicacion();
            
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
