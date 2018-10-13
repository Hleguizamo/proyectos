<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function Login($cc){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT e.nombres nombre_usuario ,e.apellidos apellido_usuario, roles.nombre nombre_rol, roles.id id_rol, e.id id_usuario
        FROM usuarios e
        INNER JOIN roles ON e.rol_id=roles.id
        WHERE numero_documento=:cc';
        $stmt = $conn->prepare($sql);
        $stmt->execute(["cc"=>$cc]);
        return $stmt->fetchAll();

    }
    public function SetToken($token,$cc){
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'UPDATE usuarios
        SET token=:token
        WHERE numero_documento=:cc';
        $stmt = $conn->prepare($sql);
        $stmt->execute(["token"=>$token,"cc"=>$cc]);
        return $stmt;
    }

    public function getUsersByRolOption($id_rol){
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT 
        concat_ws(' ', nombres, apellidos ) name,
        id value
        FROM usuarios WHERE rol_id = :id_rol";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["id_rol"=>$id_rol]);
        return $stmt->fetchAll();

    }

//    /**
//     * @return Usuario[] Returns an array of Usuario objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
