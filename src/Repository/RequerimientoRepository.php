<?php

namespace App\Repository;

use App\Entity\Requerimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
    public function getRequerimientosGrid(){
        $conn = $this->getEntityManager()->getConnection();
        $sql='  SELECT 
                RQ.fecha_creacion,
                RQ.numero_requerimiento,
                RQ.descripcion,
                APP.nombre nombre_aplicacion,
                MD.nombre nombre_modulo,
                gerencias.nombre nombre_gerencia,
                areas.nombre nombre_area,
                RQ_ST.nombre estado_requerimiento,
                RQ.fecha_asignacion,
                RQ.fecha_estimada_entrega,
                RQ.fecha_cierre,
                RQ.observaciones
                FROM requerimientos RQ
                INNER JOIN modulos MD ON RQ.modulo_id = MD.id
                INNER JOIN aplicaciones APP ON MD.aplicacion_id = APP.id
                INNER JOIN areas ON APP.area_id = areas.id
                INNER JOIN gerencias ON areas.gerencia_id = gerencias.id
                INNER JOIN empresas EMP ON gerencias.empresas_id = EMP.id
                INNER JOIN estado_requerimientos RQ_ST ON RQ.estado_requerimientos_id = RQ_ST.id';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRequerimientos($id_usuario){
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
