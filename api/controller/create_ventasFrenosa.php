<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/frenosa.php';
include_once '../config/core.php';

$database = new Database();
$db = $database->getConnection();
$frenosa = new Frenosa($db);

$array = [];

$frenosa->getCli();
$frenosa->getProd();
$frenosa->getDis();

    

    for ($j = 0; $j < count($frenosa->clientes); $j++) {
        $frenosa->Cod_Cli = $frenosa->clientes[$j];

        for ($k = 0; $k < count($frenosa->producto); $k++) {
            $frenosa->Cod_Pro = $frenosa->producto[$k];
            $frenosa->CanVen = rand(1, 20);
            $frenosa->CodDis = rand(1, 100);
            $frenosa->Cod_Emp = rand(1, 10);
            $frenosa->FEmCPC = $frenosa->fecha_aleatoria();
            $frenosa->create();
        }
    }
