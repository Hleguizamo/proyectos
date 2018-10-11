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
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
class UsuariosController extends AbstractController
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
     * @Route("/usuarios", name="usuario")
     */
    public function usuarios()
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
     * @Route("/usuarios/crudDatas", name="areaaplicaciones/crudData")
     */
    public function getCrudData(){
        $tipo_documento =  $this->getDoctrine()->getRepository(Area::class)->findAreasOption();
        $rol= $this->getDoctrine()->getRepository(Rol::class)->findRol();
        $data = array(
            'PageTitle' => 'Usuarios',
            'columns' => array(
            	["data"=> "nombre_documento",               "name" => "Tipo_documento",    "type"=>"text"],
                ["data"=> "numero_documento",             "name" => "Numero_documento",     "type"=>"number"],
                ["data"=> "nombre_usuario",             		  "name" => "Nombre",     "type"=>"text"],
                ["data"=> "apellido_usuario",             		  "name" => "Apellido",     "type"=>"text"],
                ["data"=> "celular",             		  "name" => "Celular",     "type"=>"text"],
                ["data"=> "telefono",             		  "name" => "Telefono",     "type"=>"text"],
                ["data"=> "email",             			  "name" => "Email",     "type"=>"email"],
                ["data"=> "nombre_rol",             	  "name" => "nombre_rol",    "type"=>"text"],
                ["data"=> "estado",             		  "name" => "Estado",     "type"=>"text"],
                
                
            ),
            'dataRoute' => "getUsuarios",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarUsuario',
        );
        return new JsonResponse($data);
    }


    /**
     * @Route("/getUsuarios", name="getUsuarios")
     */
    public function getUsuarios(){
        try {


            /*$areas = $this->getDoctrine()->getRepository(Empresas::class)->findAll();
            $serializer = $serializer = JMS\Serializer\SerializerBuilder::create()->build();
            $dat=$serializer->serialize($areas, "post");*/
            /*$encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);*/
            $gerencia = $this->getDoctrine()->getRepository(Usuario::class)->findUsuario();
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
}
