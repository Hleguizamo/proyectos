<?php

namespace App\Repository;

use App\Entity\EstadoRequerimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstadoRequerimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoRequerimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoRequerimiento[]    findAll()
 * @method EstadoRequerimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoRequerimientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstadoRequerimiento::class);
    }

    public function findEstadoRequerimientoOption(){
        $conn = $this->getEntityManager()->getConnection();
        $sql ="SELECT rq_st.nombre name, rq_st.id value
               FROM estado_requerimientos rq_st";
              
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

//    /**
//     * @return EstadoRequerimiento[] Returns an array of EstadoRequerimiento objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstadoRequerimiento
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
