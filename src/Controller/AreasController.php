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
  
        return $this->render('requerimientos/requerimientos.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
            'js' => '',
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
                ["data"=> "nombre_area",             "name" => "Nombre",     "type"=>"text", "CRUD"=> [1,1,1,1] ],
                ["data"=> "gerencia_id",             "name" => "Gerencia",    "type"=>"select", "options"=>$gerencias, "CRUD"=> [1,1,1,1] ],
                
                
            ),
            'dataRoute' => "getAreas",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarArea',
        );
        return new JsonResponse($data);
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
        $area->setGerenciaId($gerencia);
       
        $entityManager->persist($area);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Area insertada correctamente'
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
