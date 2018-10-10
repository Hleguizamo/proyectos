<?php

namespace App\Repository;

use App\Entity\Repositorio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Repositorio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repositorio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repositorio[]    findAll()
 * @method Repositorio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepositorioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Repositorio::class);
    }

//    /**
//     * @return Repositorio[] Returns an array of Repositorio objects
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
    public function findOneBySomeField($value): ?Repositorio
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
