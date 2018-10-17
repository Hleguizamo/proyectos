<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Empresa;
use App\Entity\Aplicaciones;
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

class ModulosController extends AbstractController
{
    /**
     * @Route("/modulos", name="modulos")
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
     * @Route("/modulos/crudDatas", name="modulos/crudData")
     */
    public function getCrudData(){
         $aplicacion =$this->getDoctrine()->getRepository(Aplicacion::class)->findAplicacion();

        $data = array(
            'PageTitle' => 'Modulos',
            'columns' => array(
                ["data"=> "nombre_modulo",             "name" => "Nombre",     "type"=>"text", "CRUD"=> [1,1,1,1] ],
                ["data"=> "id_aplicacion",             "name" => "Aplicacion",    "type"=>"select", "options"=>$aplicacion, "CRUD"=> [1,1,1,1] ],
                
  
                
            ),
            'dataRoute' => "getModulos",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarModulos',
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("/agregarModulos", name="agregarModulos")
     */
    public function agregarModulos(Request $rq){
        $nombre = $rq->get("nombre_modulo");
        $aplicacion = $rq->get("id_aplicacion");
       
        $entityManager = $this->getDoctrine()->getManager();
        $mod = new Modulos();
        $mod->setNombre($nombre);
        $mod->setAplicacionId($aplicacion);
       
        $entityManager->persist($mod);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Modulo insertado correctamente'
        ));


    }

    /**
     * @Route("/showModulo", name="showModulo")
     */
    public function showModulo($id){
        $modulo = $this->getDoctrine()->getRepository(Modulo::class)->findModuloById($id);
        return new JsonResponse($modulo);
    }


    /**
     * @Route("/updateModulos", name="updateModulos")
     */
    public function updateModulos(Request $rq){
        $nombre = $rq->get("nombre_modulo");
        $aplicacion = $rq->get("id_aplicacion");
        $id_aplicacion = $rq->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $mod = $entityManager->find($id_aplicacion);
        $mod->setNombre($nombre);
        $mod->setAplicacionId($aplicacion);
       
        $entityManager->persist($mod);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Modulo actualizado correctamente'
        ));


    }

    /**
     * @Route("/getModulos", name="getModulos")
     */
    public function getModulos(){
        try {
            $modulos = $this->getDoctrine()->getRepository(Modulos::class)->findModulos();
           
            
            $arr = array(
                    'success' => true,
                    'msg' => 'todo funciono correctamente',
                    'datos' => $modulos,//json_decode($jsonContent),
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
?>