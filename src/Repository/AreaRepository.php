<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Area::class);
    }

    public function getAreasByUser($id_usuario){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT 
                usuarios.id usuario_id,
                usuarios.nombres nombres_usuario,
                usuarios.apellidos apellidos_usuarios,
                areas.id area_id,
                areas.nombre nombre_area
                FROM usuarios
                INNER JOIN areas ON areas.id = usuarios.area_id
                WHERE usuarios.id = :id_usuario";
        $parametros = array('id_usuario'=>$id_usuario);
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();

    }

    public function findAreas(){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT a.nombre nombre_area, g.nombre gerencia_id, a.id id_area
               FROM areas a
               INNER JOIN gerencias g ON a.gerencia_id = g.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    public function findAreasOption(){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT a.nombre name, a.id value
               FROM areas a";
              
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function findAreaById($id_area){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT a.nombre nombre_area, g.nombre gerencia_id, a.id id_area
               FROM areas a
               INNER JOIN gerencias g ON a.gerencia_id = g.id
               WHERE a.id = :id_area";
        $parametros = array('id_area'=>$id_area);
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

//    /**
//     * @return Area[] Returns an array of Area objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Area
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
