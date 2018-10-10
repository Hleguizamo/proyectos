<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Empresa;
use App\Entity\Aplicaciones;
use App\Entity\Gerencia;
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
class GerenciasController extends AbstractController
{
    /**
     * @Route("/gerencias", name="gerencias")
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
     * @Route("/gerencias/crudDatas", name="gerencias/crudData")
     */
    public function getCrudData(){
        $data = array(
            'PageTitle' => 'Gerencia',
            'columns' => array(
                ["data"=> "nombre_gerencia",             "name" => "Nombre",     "type"=>"text"],
                
                
            ),
            'dataRoute' => "getGerencias",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarGerencia',
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("/agregarGerencia", name="agregarGerencia")
     */
    public function agregarGerencia(Request $rq){
        $nombre = $rq->get("nombre_gerencia");
       
        $entityManager = $this->getDoctrine()->getManager();
        $gerencia = new Gerencia();
        $gerencia->setNombre($nombre);
       
        $entityManager->persist($gerencia);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Gerencia insertada correctamente'
        ));


    }

    /**
     * @Route("/getGerencias", name="getGerencias")
     */
    public function getGerencias(){
        try {


            /*$areas = $this->getDoctrine()->getRepository(Empresas::class)->findAll();
            $serializer = $serializer = JMS\Serializer\SerializerBuilder::create()->build();
            $dat=$serializer->serialize($areas, "post");*/
            /*$encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);*/
            $gerencia = $this->getDoctrine()->getRepository(Gerencia::class)->findGerencias();
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
}
