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
use App\Utils\OptionsBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class EstadosRequerimientosController extends AbstractController
{
    /**
     * @Route("/estados", name="estados")
     */
    public function index()
    {

        $areas = $this->getDoctrine()->getRepository(Area::class)->findAll();
        $req = $this->getDoctrine()->getRepository(Requerimiento::class)->findAll();
        $esta = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findAll();
        $mod = $this->getDoctrine()->getRepository(Modulos::class)->findAll();
        $optBuilder = new OptionsBuilder();
        $optBuilder->getOptions($this->getDoctrine());
        $session =new  Session(new NativeSessionStorage(), new AttributeBag());
        $id_rol=$session->get('id_rol');
        if($id_rol != 1){
            $permisoAgregar = $optBuilder->consultarPermiso($id_rol,1)!=null;
        }else{
            $permisoAgregar = true;
        }
        return $this->render('requerimientos/requerimientos.html.twig', [
            'controller_name' => 'RequerimientosController',
            'areas' => $areas,
            'requerimiento' => $req,
            'estado' => $esta,
            'modulo' => $mod,
            'js' => '',
            'permisoAgregar' => $permisoAgregar
        ]);
    }

    /**
     * @Route("/estados/crudDatas", name="estados/crudData")
     */
    public function getCrudData(){
        

        $data = array(
            'PageTitle' => 'Estados de requerimiento',
            'columns' => array(
                ["data"=> "nombre_estado",             "name" => "Nombre",     "type"=>"text", "CRUD"=> [1,1,1,1] ],
                ["data"=> "id_estado",       "name" => "id_estado",       "type"=>"number", "CRUD"=> [0,0,0,0] ],
                ["data"=> "options",                    "name"=> "Opciones" , "defaultContent"=> '<button class="editor_edit btn btn-warning" onclick="edit(event,this)" >Editar</button> ', "CRUD"=> [0,1,0,0] ],
    
                
            ),
            'dataRoute' => "getEstados",
            'dataSrc' => "datos",
            'dist' => '4-cols',
            'saveUrl' => 'agregarEstados',
            'editUrl' => 'updateEstados',  // url donde se mandan a editar los datos
            'getDataEdit' => 'showEstado',  // url donde se consultan los datos a editar
            'idColumn' => 'id_estado',   // nombre de la columna que es id para los registros    
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("/agregarEstados", name="agregarEstados")
     */
    public function agregarEstados(Request $rq){
        $nombre = $rq->get("nombre_estado");

       
        $entityManager = $this->getDoctrine()->getManager();
        $mod = new EstadoRequerimiento();
        $mod->setNombre($nombre);
    
       
        $entityManager->persist($mod);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Estado insertado correctamente'
        ));


    }
    /**
     * @Route("/updateEstados", name="updateEstados")
     */
    public function updateEstados(Request $rq){

        $nombre = $rq->get("nombre_estado");
        $id_estado=$rq->get('id');
       
        $entityManager = $this->getDoctrine()->getManager();
        $estado = $entityManager->find(EstadoRequerimiento::class,$id_estado);
        $estado->setNombre($nombre);
    
       
        $entityManager->persist($estado);
        $entityManager->flush();
        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Estado actualizado correctamente'
        ));


    }
      /**
     * @Route("/showEstado", name="showEstado")
     */

    public function showEstado(Request $rq){
        $id_estado=$rq->get('id');
        $estado = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findEstadoById($id_estado);
  
        return new JsonResponse(array(
            'success' => true,
            'data' => $estado,
        ));
    }

    /**
     * @Route("/getEstados", name="getEstados")
     */
    public function getEstados(){
        try {
            $estado = $this->getDoctrine()->getRepository(EstadoRequerimiento::class)->findEstados();
           
            
            $arr = array(
                    'success' => true,
                    'msg' => 'todo funciono correctamente',
                    'datos' => $estado,//json_decode($jsonContent),
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