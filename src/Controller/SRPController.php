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
        'days' => $v->getDays(), 'cpo_flag' => $v->getCpoFlag());

        return ($create);
    }

    public function filterController() {
        $queryArray = array();
        $this->processVehicleStatus($_POST['vehicleStatus'], $queryArray);
        $this->processVehicleMake($_POST['vehicleMake'], $queryArray);

        $dbString = $this->getDoctrine()->getRepository(Vehicle::class)->testFilter($queryArray);
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
        }
    }
}