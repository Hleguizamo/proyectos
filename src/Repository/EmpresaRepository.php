<?php

namespace App\Repository;

use App\Entity\Empresa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Empresa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empresa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empresa[]    findAll()
 * @method Empresa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpresaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Empresa::class);
    }

    public function findEmpresas(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT e.nombre nombre_empresa ,g.nombre nombre_gerencia
        FROM empresas e
        INNER JOIN gerencias g
        ON e.id=g.empresas_id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @return Empresa[] Returns an array of Empresa objects
     */
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nombre = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Empresa
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
