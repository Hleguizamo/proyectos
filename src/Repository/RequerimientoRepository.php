<?php

namespace App\Repository;

use App\Entity\Requerimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
/*use App\Utils\CsvReaderInterface;*/
/**
 * @method Requerimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requerimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requerimiento[]    findAll()
 * @method Requerimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequerimientoRepository extends ServiceEntityRepository 
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Requerimiento::class);
    }

   

    public function getRequerimientosGrid($id_usuario,$id_rol){
        $conn = $this->getEntityManager()->getConnection();
        $sql="  SELECT 
                RQ.id id_requerimiento,
                Cast(RQ.fecha_creacion As Date) as fecha_creacion,
                RQ.numero_requerimiento,
                RQ.descripcion,
                APP.nombre nombre_aplicacion,
                MD.nombre nombre_modulo,
                gerencias.nombre nombre_gerencia,
                areas.nombre nombre_area,
                RQ_ST.nombre estado_requerimiento,
                Cast(RQ.fecha_asignacion As Date) as fecha_asignacion,
                Cast(RQ.fecha_estimada_entrega As Date) as fecha_estimada_entrega,
                Cast(RQ.fecha_cierre As Date) as fecha_cierre,
     
                RQ.observaciones,
                emp_cons.nombre empresa_consultor,
                concat_ws(' ', consultor.nombres, consultor.apellidos )  nombre_consultor,
                concat_ws(' ', usureq.nombres, usureq.apellidos )  nombre_usuario
                FROM requerimientos RQ
                INNER JOIN modulos MD ON RQ.modulo_id = MD.id
                INNER JOIN aplicaciones APP ON MD.aplicacion_id = APP.id
                INNER JOIN areas ON APP.area_id = areas.id
                INNER JOIN gerencias ON areas.gerencia_id = gerencias.id
                INNER JOIN empresas EMP ON gerencias.empresas_id = EMP.id
                INNER JOIN estado_requerimientos RQ_ST ON RQ.estado_requerimientos_id = RQ_ST.id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id, usuarios.empresa_id empresa_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 2
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) consultor ON consultor.requermiento_id = RQ.id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 3
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) usureq ON usureq.requermiento_id = RQ.id 
                LEFT JOIN empresas emp_cons ON emp_cons.id = consultor.empresa_id
                WHERE RQ.estado <> 0

                ";
        //Si es un conultor se filtran los proyectos donde aparezca como consultor        
        if($id_rol == 2){
            $sql = $sql . " WHERE consultor.usuario_id = :id_usuario";
        }else if($id_rol == 3){
            //Si es un usuario se filtran solo los requerimientos que el usuario tenga
            $sql = $sql . " WHERE usureq.usuario_id = :id_usuario";
        }
        $sql = $sql . " ORDER BY RQ.id DESC";
        //$sql = $this->addOptionsToGrid($sql);
        //Si es un administrador muestra todo
        $stmt = $conn->prepare($sql);
        //Cuando el rol es administrador no se pasa usario para que muestre todos los requerimientos
        if($id_rol == 1){
            $stmt->execute();
        }else{
            $stmt->execute(['id_usuario'=> $id_usuario]);    
        }
        

        return $stmt->fetchAll();
    }

    public function getRequerimientosGridEdit($id_req){
        $conn = $this->getEntityManager()->getConnection();
        $sql="  SELECT 
                RQ.id id_requerimiento,
                RQ.fecha_creacion,
                RQ.numero_requerimiento,
                RQ.descripcion,
                APP.id nombre_aplicacion,
                MD.id nombre_modulo,
                gerencias.id nombre_gerencia,
                areas.id nombre_area,
                RQ_ST.id estado_requerimiento,
                RQ.fecha_asignacion,
                RQ.fecha_estimada_entrega,
                RQ.fecha_cierre,
                RQ.observaciones,
                consultor.usuario_id  nombre_consultor,
                usureq.usuario_id  nombre_usuario
                FROM requerimientos RQ
                INNER JOIN modulos MD ON RQ.modulo_id = MD.id
                INNER JOIN aplicaciones APP ON MD.aplicacion_id = APP.id
                INNER JOIN areas ON APP.area_id = areas.id
                INNER JOIN gerencias ON areas.gerencia_id = gerencias.id
                INNER JOIN empresas EMP ON gerencias.empresas_id = EMP.id
                INNER JOIN estado_requerimientos RQ_ST ON RQ.estado_requerimientos_id = RQ_ST.id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 2
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) consultor ON consultor.requermiento_id = RQ.id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 3
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) usureq ON usureq.requermiento_id = RQ.id 

                ";
        
        $sql = $sql . "WHERE RQ.id = :id_req ORDER BY RQ.id DESC";
        //$sql = $this->addOptionsToGrid($sql);
        //Si es un administrador muestra todo
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_req'=> $id_req]);    
       
        

        return $stmt->fetchAll();
    }

    public function addOptionsToGrid($sql){
        $sql = "SELECT A.*, '<span class=\"glyphicon glyphicon-edit\" >' as options FROM ($sql) A ";
        return $sql;
    }

    public function getRequerimientos($id_usuario){
        dd("hola");
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT 
                usuarios.numero_documento documento_usuario,
                usuarios.nombres nombre_usuario,
                usuarios.apellidos apellido_usuario,
                RQ.numero_requerimiento,
                RQ.descripcion,
                
                RQ.fecha_asignacion,
                RQ.fecha_inicio,
                RQ.fecha_estimada_entrega,
                RQ.fecha_cierre,
                RQ.observaciones,
                RQ.avance_porcentual,
                RQ.fecha_creacion,
                RQ.fecha_actualizacion,
                RQ_STAT.nombre nombre_estado,
                RQ_STAT.id estado_id,
                modulos.nombre nombre_modulo,
                modulos.id modulo_id,
                areas.id area_id,
                areas.nombre nombre_area,
                consultor.nombres nombre_consultor,
                consultor.apellidos apellidos_consultor,
                usureq.nombres nombres_usuario,
                usureq.apellidos apellidos_usuario
                FROM usuarios 
                INNER JOIN trazabilidad_requerimmientos TR_RQ ON TR_RQ.usuario_id = usuarios.id
                INNER JOIN requerimientos RQ ON RQ.id = TR_RQ.requermiento_id
                INNER JOIN estado_requerimientos RQ_STAT ON RQ_STAT.id = RQ.estado_requerimientos_id
                INNER JOIN modulos ON modulos.id = RQ.modulo_id
                INNER JOIN roles ON usuarios.rol_id = roles.id
                INNER JOIN areas ON areas.id = usuarios.area_id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 2
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) consultor ON consultor.requermiento_id = RQ.id
                LEFT JOIN (
                    SELECT usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                    FROM trazabilidad_requerimmientos
                    INNER JOIN usuarios ON usuarios.id = usuario_id
                    WHERE usuarios.rol_id = 2
                    GROUP BY usuario_id, requermiento_id,usuarios.nombres,usuarios.apellidos,usuarios.rol_id
                ) usureq ON usureq.requermiento_id = RQ.id 
                where usuarios.id = :id_usuario
                ORDER BY RQ.id DESC ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_usuario'=> $id_usuario]);

        return $stmt->fetchAll();

    }



     public function getRequerimientosAll(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT 
            usuarios.numero_documento documento_usuario,
            usuarios.nombres nombre_usuario,
            usuarios.apellidos apellido_usuario,
            roles.id id_rol,
            roles.nombre nombre_rol,
            RQ.numero_requerimiento,
            RQ.descripcion,
            RQ.fecha_asignacion,
            RQ.fecha_inicio,
            RQ.fecha_estimada_entrega,
            RQ.fecha_cierre,
            RQ.observaciones,
            RQ.avance_porcentual,
            RQ.fecha_creacion,
            RQ.fecha_actualizacion,
            RQ_STAT.nombre nombre_estado,
            RQ_STAT.id estado_id,
            modulos.nombre nombre_modulo,
            modulos.id modulo_id,
            areas.id area_id,
            areas.nombre nombre_area
            FROM usuarios 
            INNER JOIN trazabilidad_requerimmientos TR_RQ ON TR_RQ.usuario_id = usuarios.id
            INNER JOIN requerimientos RQ ON RQ.id = TR_RQ.requermiento_id
            INNER JOIN estado_requerimientos RQ_STAT ON RQ_STAT.id = RQ.estado_requerimientos_id
            INNER JOIN modulos ON modulos.id = RQ.modulo_id
            INNER JOIN roles ON usuarios.rol_id = roles.id
            INNER JOIN areas ON areas.id = usuarios.area_id
            ORDER BY RQ.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();

    }


    public function filtrarRequerimientos($id_usuario,$id_area,$id_requerimiento,$id_estado,$id_modulo){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT 
                usuarios.numero_documento documento_usuario,
                usuarios.nombres nombre_usuario,
                usuarios.apellidos apellido_usuario,
                RQ.numero_requerimiento,
                RQ.descripcion,
                RQ.fecha_asignacion,
                RQ.fecha_inicio,
                RQ.fecha_estimada_entrega,
                RQ.fecha_cierre,
                RQ.observaciones,
                RQ.avance_porcentual,
                RQ.fecha_creacion,
                RQ.fecha_actualizacion,
                RQ_STAT.nombre nombre_estado,
                RQ_STAT.id estado_id,
                modulos.nombre nombre_modulo,
                modulos.id modulo_id,
                areas.id area_id,
                areas.nombre nombre_area
                FROM usuarios 
                INNER JOIN trazabilidad_requerimmientos TR_RQ ON TR_RQ.usuario_id = usuarios.id
                INNER JOIN requerimientos RQ ON RQ.id = TR_RQ.requermiento_id
                INNER JOIN estado_requerimientos RQ_STAT ON RQ_STAT.id = RQ.estado_requerimientos_id
                INNER JOIN modulos ON modulos.id = RQ.modulo_id
                INNER JOIN roles ON usuarios.rol_id = roles.id
                INNER JOIN areas ON areas.id = usuarios.area_id
                where usuarios.id = :id_usuario ';
                $parametros=Array();
                if($id_area!='null'){
                    $sql=$sql."AND areas.id = :id_area ";
                    $parametros[ 'id_area'] = $id_area;
                }
                if($id_requerimiento!='null'){
                    $sql=$sql."AND RQ.numero_requerimiento = :id_requerimiento ";
                    $parametros[ 'id_requerimiento'] = $id_requerimiento;
                }
                if($id_estado!='null'){
                     $sql=$sql."AND RQ_STAT.id = :id_estado ";
                     $parametros[ 'id_estado'] = $id_estado;
                }
                if($id_modulo!='null'){
                    $sql=$sql."AND modulos.id = :id_mod ";
                    $parametros[ 'id_mod'] = $id_modulo;
                }

                $sql=$sql.'ORDER BY RQ.id DESC';
                
                
                
                $parametros[ 'id_usuario'] = $id_usuario;
                
        //$d = array ('sql'=>$sql,'params'=>$parametros);
        //dd($d);
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();



    }




    public function filtrarRequerimientosAll($id_area,$id_requerimiento,$id_estado,$id_modulo){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT 
                usuarios.numero_documento documento_usuario,
                usuarios.nombres nombre_usuario,
                usuarios.apellidos apellido_usuario,
                roles.id id_rol,
                roles.nombre nombre_rol,
                RQ.numero_requerimiento,
                RQ.descripcion,
                RQ.fecha_asignacion,
                RQ.fecha_inicio,
                RQ.avance_porcentual,
                RQ.fecha_estimada_entrega,
                RQ.fecha_cierre,
                RQ.observaciones,
                RQ.fecha_creacion,
                RQ.fecha_actualizacion,
                RQ_STAT.nombre nombre_estado,
                RQ_STAT.id estado_id,
                modulos.nombre nombre_modulo,
                modulos.id modulo_id,
                areas.id area_id,
                areas.nombre nombre_area
                FROM usuarios 
                INNER JOIN trazabilidad_requerimmientos TR_RQ ON TR_RQ.usuario_id = usuarios.id
                INNER JOIN requerimientos RQ ON RQ.id = TR_RQ.requermiento_id
                INNER JOIN estado_requerimientos RQ_STAT ON RQ_STAT.id = RQ.estado_requerimientos_id
                INNER JOIN modulos ON modulos.id = RQ.modulo_id
                INNER JOIN roles ON usuarios.rol_id = roles.id
                INNER JOIN areas ON areas.id =  usuarios.area_id';
               
                $parametros=Array();
                $cont=0;
                if($id_area!='null'){
                    $sql=$sql." WHERE areas.id = :id_area ";
                    $parametros[ 'id_area'] = $id_area;
                    $cont=1;
                }
                if($id_requerimiento!='null'){
                    if($cont==0){
                        $sql=$sql." WHERE RQ.numero_requerimiento = :id_requerimiento ";
                        $parametros[ 'id_requerimiento'] = $id_requerimiento;
                        $cont=1;

                    }else{
                        $sql=$sql."AND RQ.numero_requerimiento = :id_requerimiento ";
                        $parametros[ 'id_requerimiento'] = $id_requerimiento;

                    }
                    
                }
                if($id_estado!='null'){

                    if($cont==0){
                        $sql=$sql." WHERE RQ_STAT.id = :id_estado ";
                        $parametros[ 'id_estado'] = $id_estado;
                        $cont=1;

                    }else{
                        $sql=$sql." AND RQ_STAT.id = :id_estado ";
                        $parametros[ 'id_estado'] = $id_estado;
                
                    }
                    
                }
                if($id_modulo!='null'){
                     if($cont==0){
                        $sql=$sql." WHERE modulos.id = :id_mod ";
                        $parametros[ 'id_mod'] = $id_modulo;
                        $cont=1;

                    }else{
                        $sql=$sql." AND  modulos.id = :id_mod ";
                        $parametros[ 'id_mod'] = $id_modulo;
                    
                    }
                 
                }

                $sql=$sql.'ORDER BY RQ.id DESC';
                
                
                
            
                
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();



    }



//    /**
//     * @return Requerimiento[] Returns an array of Requerimiento objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Requerimiento
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
