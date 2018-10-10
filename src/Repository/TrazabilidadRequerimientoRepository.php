<?php

namespace App\Repository;

use App\Entity\TrazabilidadRequerimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrazabilidadRequerimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrazabilidadRequerimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrazabilidadRequerimiento[]    findAll()
 * @method TrazabilidadRequerimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrazabilidadRequerimientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrazabilidadRequerimiento::class);
    }

//    /**
//     * @return TrazabilidadRequerimiento[] Returns an array of TrazabilidadRequerimiento objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrazabilidadRequerimiento
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
