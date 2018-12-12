<?php

namespace App\Repository;

use App\Entity\Aplicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Aplicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aplicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aplicacion[]    findAll()
 * @method Aplicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AplicacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Aplicacion::class);
    }
    public function findAplicacion(){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT a.nombre name, a.id value, a.id id_aplicacion
               FROM aplicaciones a
               
               WHERE a.estado <> 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public function findAplicacionesOptions(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT app.id value, app.nombre name
                FROM aplicaciones app";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAplicacionById($id_aplicacion){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT a.nombre name, a.id value, a.id id_aplicacion
               FROM aplicaciones a
               WHERE a.id = :id_aplicacion";
        $parametros = array('id_aplicacion'=>$id_aplicacion);
   
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

//    /**
//     * @return Aplicacion[] Returns an array of Aplicacion objects
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
    public function findOneBySomeField($value): ?Aplicacion
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
