<?php

namespace App\Repository;

use App\Entity\Gerencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gerencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gerencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gerencia[]    findAll()
 * @method Gerencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GerenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gerencia::class);
    }

//    /**
//     * @return Gerencia[] Returns an array of Gerencia objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gerencia
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
