<?php

namespace App\Repository;

use App\Entity\Modulos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Modulos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modulos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modulos[]    findAll()
 * @method Modulos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModulosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Modulos::class);
    }

    public function findModulos(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT 
                    m.nombre nombre_modulo,
                    m.id id_modulo,
                    m.aplicacion_id id_aplicacion          
        FROM modulos m";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

//    /**
//     * @return Modulos[] Returns an array of Modulos objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Modulos
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
