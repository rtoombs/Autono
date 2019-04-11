<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function testFilter($queryArray) {
        $em = $this->getEntityManager()->getConnection();
        $sql = "";
        if (count($queryArray) >= 1){
            $sql = '
            SELECT * FROM Vehicle v
            WHERE ';
            for ($i = 0; $i < count($queryArray); ++$i){
                if ($i == count($queryArray)-1) {
                    $sql .= $queryArray[$i] . ";";
                } else {
                    $sql .= $queryArray[$i] . " AND ";
                }
            }
        } else {
            $sql = '
            SELECT * FROM Vehicle v;';
        }

        $stmt = $em->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
}
