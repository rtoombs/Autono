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

    public function testFilter($queryArray, $queryFlags) {
        $em = $this->getEntityManager()->getConnection();
        $sql = "";
        if (count($queryArray) >= 1){
            $sql = '
            SELECT * FROM Vehicle v
            WHERE ';
            for ($i = 0; $i < count($queryArray); ++$i){
                if ($i == count($queryArray)-1) {
                    $sql .= $queryArray[$i];
                } else {
                    $sql .= $queryArray[$i] . " AND ";
                }
            }
        } else {
            $sql = '
            SELECT * FROM Vehicle v;';
        }
        if ($queryFlags['priceFlag']){
            $sql .= " ORDER BY v.msrp_price ASC";
        }

        $sql .= ";";
        $stmt = $em->prepare($sql);
        $stmt->execute();

        $bodyQuery = "";
        //if (!$queryFlags["bodyFlag"]){
            $bodyQuery = $this->getCurrentBodyTypes($sql);
        //}

        return $returnArray = [
            'bodyUpdate' => $bodyQuery,
            'vehicleList' => $stmt->fetchAll(),
        ];
    }

    public function getPriceSliderMax() {
        $em = $this->getEntityManager()->getConnection();
        $sql = 'select max(msrp_price) from vehicle;';
        $stmt = $em->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function getYearSliderMax() {
        $em = $this->getEntityManager()->getConnection();
        $sql = 'select max(veh_year) as max, min(veh_year) as min from vehicle;';
        $stmt = $em->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function getCurrentBodyTypes($sql) {
        $newQuery = str_replace("*", "distinct veh_style", $sql);
        $em = $this->getEntityManager()->getConnection();
        $stmt = $em->prepare($newQuery);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function getAllBodyTypes() {
        $em = $this->getEntityManager()->getConnection();
        $sql = 'select distinct veh_style from vehicle;';
        $stmt = $em->prepare($sql);
        $stmt->execute();
        return  $stmt->fetchAll();
    }
}
