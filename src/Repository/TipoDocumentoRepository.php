<?php

namespace App\Repository;

use App\Entity\TipoDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TipoDocumento|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoDocumento|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoDocumento[]    findAll()
 * @method TipoDocumento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoDocumento::class);
    }
    public function findDocumento(){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT 
                    t.nombre name,
                    t.id value                    
        FROM tipo_documentos t";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

//    /**
//     * @return TipoDocumento[] Returns an array of TipoDocumento objects
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
    public function findOneBySomeField($value): ?TipoDocumento
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
