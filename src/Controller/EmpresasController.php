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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
class EmpresasController extends AbstractController
{
    /**
     * @Route("/empresas", name="empresas")
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
     * @Route("/empresas/crudData", name="empresas")
     */
    public function getCrudData(){
    	$data = array(
    		'PageTitle' => 'Empresas',
    		'columns' => array(
    			["data"=> "nombre_empresa", 			"name" => "Nombre",		"type"=>"text"],
		        ["data"=> "pais", 						"name"=> "Pais",		"type"=>"text"],
		        ["data"=> "codigo_empresa", 			"name"=> "Código",		"type"=>"text"],
		        ["data"=> "estado", 					"name"=> "Estado",		"type"=>"select", 
		        										"options"=> 
		        											array(
		        												['value'=>'1','name'=>'Activo'],
		        												['value'=>'0','name'=>'Inactivo'])
		        ]
    		),
    		'dataRoute' => "getEmpresas",
    		'dataSrc' => "datos",
    		'dist' => '4-cols',
    		'saveUrl' => 'agregarEmpresa',
    	);
    	return new JsonResponse($data);
    }

    /**
     * @Route("/agregarEmpresa", name="agregarEmpresa")
     */
    public function agregarEmpresa(Request $rq){
    	$nombre = $rq->get("nombre_empresa");
    	$codigo = $rq->get("codigo_empresa");
    	$pais   = $rq->get("pais");
    	$estado = $rq->get("estado");
    	$entityManager = $this->getDoctrine()->getManager();
    	$empresa = new Empresa();
    	$empresa->setNombre($nombre);
    	$empresa->setCodigo($codigo);
    	$empresa->setPais($pais);
    	$empresa->setEstado($estado);
    	$entityManager->persist($empresa);
    	$entityManager->flush();
    	return new JsonResponse(array(
    		'success' => true,
    		'msg' => 'Empresa insertada correctamente'
    	));


    }

    /**
     * @Route("/getEmpresas", name="empresa")
     */
    public function getEmpresas(){
    	try {


    		/*$areas = $this->getDoctrine()->getRepository(Empresas::class)->findAll();
	        $serializer = $serializer = JMS\Serializer\SerializerBuilder::create()->build();
			$dat=$serializer->serialize($areas, "post");*/
			/*$encoders = array(new XmlEncoder(), new JsonEncoder());
			$normalizers = array(new ObjectNormalizer());

			$serializer = new Serializer($normalizers, $encoders);*/
			$empr = $this->getDoctrine()->getRepository(Empresa::class)->findEmpresas();
			//dd($empr);
			
			//$jsonContent = $serializer->serialize($empr, 'json');
			//$data = $serializer->deserialize($inputStr, $typeName, $format);
	    	//$entityManager = $this->getDoctrine()->getManager();
	        //$empresa = new Empresas;
	        //$empresa->setNombre("pepito");
	        $arr = array(
	                'success' => true,
	                'msg' => 'todo funciono correctamente',
	                'datos' => $empr,//json_decode($jsonContent),
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
