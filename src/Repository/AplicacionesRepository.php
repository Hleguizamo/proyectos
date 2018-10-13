<?php

namespace App\Repository;

use App\Entity\Aplicaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Aplicaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aplicaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aplicaciones[]    findAll()
 * @method Aplicaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AplicacionesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Aplicaciones::class);
    }

    

//    /**
//     * @return Aplicaciones[] Returns an array of Aplicaciones objects
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
    public function findOneBySomeField($value): ?Aplicaciones
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
