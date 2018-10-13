<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
	    		$session->set('id_rol',$data[0]['id_rol']);
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
