<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Area;

class AreasController extends AbstractController
{
    /**
     * @Route("/areas", name="areas")
     */
    public function index()
    {
        return $this->render('areas/index.html.twig', [
            'controller_name' => 'AreasController',
        ]);
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
