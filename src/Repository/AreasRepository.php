<?php

namespace App\Repository;

use App\Entity\Areas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Areas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Areas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Areas[]    findAll()
 * @method Areas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Areas::class);
    }

//    /**
//     * @return Areas[] Returns an array of Areas objects
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
    public function findOneBySomeField($value): ?Areas
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
