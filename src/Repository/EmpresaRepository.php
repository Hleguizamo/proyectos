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
        $sql = "SELECT 
                    e.nombre nombre_empresa ,
                    e.codigo codigo_empresa,
                    e.id id_empresa,
                    e.pais pais, 
                    CASE e.estado 
                        WHEN '1' THEN 'Activo'
                        ELSE 'Inactivo'
                    END estado
        FROM empresas e
        ";
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

    public function findEmpresaById($id){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT 
                    e.nombre nombre_empresa ,
                    e.codigo codigo_empresa, 
                    e.pais pais,
                    e.id id_empresa,
                    CASE e.estado 
                        WHEN '1' THEN 'Activo'
                        ELSE 'Inactivo'
                    END estado
        FROM empresas e
        WHERE e.id = :id_empresa";
       
        $parametros = array('id_empresa'=>$id);
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
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
