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

class RequerimientosController extends AbstractController
{
    /**
     * @Route("/requerimientos", name="requerimientos")
     */
    public function index()
    {

    	$areas = $this->getDoctrine()->getRepository(Area::class)->findAll();
    	$req = $this->getDoctrine()->getRepository(Requerimiento::class)->findAll();
    	$esta = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findAll();
    	$mod = $this->getDoctrine()->getRepository(Modulos::class)->findAll();
  
        return $this->render('requerimientos/index.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
        ]);
    }
    

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
}
