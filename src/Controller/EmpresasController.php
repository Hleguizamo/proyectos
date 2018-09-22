<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Empresa;
use App\Entity\Aplicaciones;
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
        return $this->render('empresas/index.html.twig', [
            'controller_name' => 'EmpresasController',
        ]);
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
