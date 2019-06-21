<?php
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/viaje.php';

$data = json_decode(file_get_contents("php://input"));
$database = new Database();
$db = $database->getConnection();
$viaje = new viaje($db);

$viaje->idConductor = 1;
$viaje->idVehiculo = 1;
$viaje->horaSalida = $data->horaSalida;
$viaje->precio = 25;

$viaje->fechaSalida = $data->fechaSalida;

for ($i = 1; $i < 10; $i++) {
    $viaje->idOrigen = $i;
    for ($y = 1; $y < 10; $y++) {
        $viaje->idDestino = $y;
        if ($y!= $i) {
            if ($viaje->createViaje()) {
                $array["message"] = $i . "-" . $y;
                echo json_encode($array);
            } else {
                $array["message"] = "error";
                echo json_encode($array);
            }
        }
    }
}
