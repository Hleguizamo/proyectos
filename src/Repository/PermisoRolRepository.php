<?php

namespace App\Repository;

use App\Entity\PermisoRol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PermisoRol|null find($id, $lockMode = null, $lockVersion = null)
 * @method PermisoRol|null findOneBy(array $criteria, array $orderBy = null)
 * @method PermisoRol[]    findAll()
 * @method PermisoRol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermisoRolRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PermisoRol::class);
    }

//    /**
//     * @return PermisoRol[] Returns an array of PermisoRol objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PermisoRol
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
