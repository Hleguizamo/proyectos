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
            'js' => '',
            ]);
    }

    /**
     * @Route("/empresas/crudDatas", name="empresas/crudData")
     */
    public function getCrudData(){
    	$data = array(
    		'PageTitle' => 'Empresas',
    		'columns' => array(
    			["data"=> "nombre_empresa", 			"name" => "Nombre",		"type"=>"text", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "pais", 						"name"=> "Pais",		"type"=>"text", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "codigo_empresa", 			"name"=> "Código",		"type"=>"text", "CRUD"=> [1,1,1,1] ],
		        ["data"=> "estado", 					"name"=> "Estado",		"type"=>"select", 
		        										"options"=> 
		        											array(
		        												['value'=>'1','name'=>'Activo'],
		        												['value'=>'0','name'=>'Inactivo'])
		        , "CRUD"=> [1,0,0,0] ],
                ["data"=> "id_empresa",       "name" => "id_empresa",       "type"=>"number", "CRUD"=> [0,0,0,0] ],
                ["data"=> "options",                    "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning" onclick="edit(event,this)" >Editar</button> ', "CRUD"=> [0,1,0,0] ],
    		),
    		'dataRoute' => "getEmpresas",
    		'dataSrc' => "datos",
    		'dist' => '4-cols',
    		'saveUrl' => 'agregarEmpresa',
            'editUrl' => 'updateEmpresas',  // url donde se mandan a editar los datos
            'getDataEdit' => 'showEmpresas',  // url donde se consultan los datos a editar
            'idColumn' => 'id_empresa',   // nombre de la columna que es id para los registros    
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
     * @Route("/showEmpresas", name="showEmpresas")
     */
    public function showEmpresas(Request $rq){
        $id_empresa = $rq->get("id");
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findEmpresaById($id_empresa);
        return new JsonResponse(array(
            'success' =>    true,
            'data' => $empresa
        ));
    }

    /**
     * @Route("/updateEmpresas", name="updateEmpresas")
     */
    public function updateEmpresas(Request $rq){
        $nombre = $rq->get("nombre_empresa");
        $codigo = $rq->get("codigo_empresa");
        $pais   = $rq->get("pais");
        $estado = $rq->get("estado");
        $id_empresa = $rq->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $empresa = $entityManager->find(Empresa::class,$id_empresa);
        $empresa->setNombre($nombre);
        $empresa->setCodigo($codigo);
        $empresa->setPais($pais);
        $empresa->setEstado($estado);
        $entityManager->persist($empresa);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Empresa actualizada correctamente'
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
