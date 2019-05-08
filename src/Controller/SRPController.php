<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\VehicleRepository;
use App\Entity\Vehicle;

class SRPController extends AbstractController{

    public function go() {
        return $this->render('srp.html.twig');
    }

    public function GetAllInventory() {
        $entityManager = $this->getDoctrine()->getManager();
        $loadVehicle = $entityManager->getRepository(Vehicle::class)->findAll();
        //$ret = $this->renderData($loadVehicle);

        $vehicles = array();
        //TODO Implement a route if only one vehicle in database
        for ($i = 0; $i <= count($loadVehicle)-1; ++$i){
            $parse = $this->renderData($loadVehicle[$i]);
            array_push($vehicles, $parse);
        }
        //var_dump($vehicles);
        return new Response(json_encode($vehicles));
    }

    public function renderData(Vehicle $v){

        $create = array('stock_no' => $v->getStockNo(), 'vin' => $v->getVin(), 'new_used' => $v->getNewUsed(), 'veh_year' => $v->getVehYear(), 'veh_make' => $v->getVehMake(),
        'veh_class' => $v->getVehClass(), 'veh_model' => $v->getVehModel(), 'veh_trim' => $v->getVehTrim(), 'trans_type' => $v->getTransType(), 'wheelbase' => $v->getWheelbase(),
        'ext_color' => $v->getExtColor(), 'int_color' => $v->getIntColor(), 'miles' => $v->getMiles(), 'msrp_price' => $v->getMsrpPrice(), 'list_price' => $v->getListPrice(),
        'days' => $v->getDays(), 'cpo_flag' => $v->getCpoFlag(), 'veh_type' => $v->getVehType(), 'veh_style' => $v->getVehStyle(), 'engine_type' => $v->getEngineType());

        return ($create);
    }

    public function filterController() {
        $queryArray = array();
        $queryFlags = array();

        $queryFlags["statusFlag"] = $this->processVehicleStatus($_POST['vehicleStatus'], $queryArray);
        $queryFlags["makeFlag"] = $this->processVehicleMake($_POST['vehicleMake'], $queryArray);
        $queryFlags["priceFlag"] = $this->processPriceFilter($_POST['priceFilter'], $queryArray);
        $queryFlags["yearFlag"] = $this->processYearFilter($_POST['yearFilter'], $queryArray);
        $queryFlags["bodyFlag"] = $this->processBodyFilter($_POST['bodyFilter'], $queryArray);


        //var_dump($queryArray);
        $dbString = $this->getDoctrine()->getRepository(Vehicle::class)->testFilter($queryArray, $queryFlags);

        if (!empty($dbString)) {
            return new Response(json_encode($dbString));

        } else {
            //No Vehicles Match The Filters
            return new Response('NID');
        }
    }

    public function processVehicleStatus($object, &$queryArray) {
        $filter = "";
        $query = "";
        foreach ($object as $k => $v) {
            if ($v === 'true'){
                if (strlen($filter) == 0){
                    $filter = "('" . strtoupper($k[0]) . "'";
                } else {
                    $filter .= ',' . "'" . strtoupper($k[0]) . "'";
                }
            }
        }
        if (strlen($filter) != 0){
            $filter .= ')';
            $query = "v.new_used IN ".$filter;
            array_push($queryArray, $query);
            return true;
        } else {
            return false;
        }
    }

    public function processVehicleMake($object, &$queryArray) {
        $filter = "";
        $query = "";
        foreach ($object as $k => $v) {
            if ($v === 'true'){
                if (strlen($filter) == 0) {
                    $filter = "('" . $k . "'";
                } else {
                    $filter .= ',' . "'" . $k . "'";
                }
            }
        }
        if (strlen($filter) != 0) {
            $filter .= ')';
            $query = "v.veh_make IN ".$filter;
            array_push($queryArray, $query);
            return true;
        } else {
            return false;
        }
    }

    public function processBodyFilter($object, &$queryArray) {
        $query = "";
        $filter = "";
        if ($object["Count"] > 0) {
            foreach ($object as $k => $v) {
                if ($v === 'true') {
                    if (strlen($filter) == 0) {
                        $filter = "('" . $k . "'";
                    } else {
                        $filter .= ',' . "'" . $k . "'";
                    }
                }
            }
            $filter .= ')';
            $query = "v.veh_style IN ".$filter;
            array_push($queryArray, $query);
            return true;
        } else {
            return false;
        }
    }

    public function processPriceFilter($object, &$queryArray) {
        if ($object["maxPrice"] == $object["highPrice"] && $object["lowPrice"] == "0"){
            return false;
        } else {
            $query = "v.msrp_price >= " . $object["lowPrice"] . " and v.msrp_price <= " . $object["highPrice"];
            array_push($queryArray, $query);
            return true;
        }
    }

    public function processYearFilter($object, &$queryArray) {
        if ($object["maxYear"] == $object["highYear"] && $object["lowYear"] == $object["minYear"]){
            return false;
        } else {
            $query = "v.veh_year >= " . $object["lowYear"] . " and v.veh_year <= " . $object["highYear"];
            array_push($queryArray, $query);
            return true;
        }
    }


    public function getMaxPrice() {
        $price = $this->getDoctrine()->getRepository(Vehicle::class)->getPriceSliderMax();
        return new Response(json_encode($price));
    }

    public function getMaxYear() {
        $year = $this->getDoctrine()->getRepository(Vehicle::class)->getYearSliderMax();
        return new Response(json_encode($year));
    }

    public function getAllBodyTypes() {
        $body = $this->getDoctrine()->getRepository(Vehicle::class)->getAllBodyTypes();
        return new Response(json_encode($body));
    }
}