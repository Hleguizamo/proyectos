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
  
        return $this->render('requerimientos/requerimientos.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
        ]);
    }

    /**
     * @Route("/aplicaciones/crudData", name="areaaplicaciones/crudData")
     */
    public function getCrudData(){
        $area =  $this->getDoctrine()->getRepository(Area::class)->findAreasOption();

        $data = array(
            'PageTitle' => 'Aplicaciones',
            'columns' => array(
                ["data"=> "nombre_aplicacion",             "name" => "Nombre",     "type"=>"text"],
                ["data"=> "area_id",             "name" => "Area",    "type"=>"select", "options"=>$area],
                
                
            ),
            'dataRoute' => "getAplicacion",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarAplicacion',
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("/agregarAplicacion", name="agregarAplicacion")
     */
    public function agregarArea(Request $rq){
        $nombre = $rq->get("nombre_aplicacion");
        $area = $rq->get("area_id");
       
        $entityManager = $this->getDoctrine()->getManager();
        $aplicacion = new Aplicacion();
        $aplicacion->setNombre($nombre);
        $aplicacion->setAreaId($area);
       
        $entityManager->persist($aplicacion);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Aplicacion insertada correctamente'
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
