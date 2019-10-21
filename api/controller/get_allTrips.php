<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/viaje.php';

$database = new Database();
$db = $database->getConnection();
$viaje = new Viaje($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$jwt = isset($data->jwt) ? $data->jwt : "";

$array = [];

$viaje->idOrigen = $data->idOrigen;
$viaje->idDestino = $data->idDestino;
$viaje->cantPasajeros = $data->cantPasajeros;
$viaje->fechaSalida = $data->fechaSalida;
$get_viaje = $viaje->getAllTrips();


if ($jwt && $get_viaje) {

    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $typeUser = $decoded->data->idRol;

        if ($typeUser == 1 || $typeUser == 2) {

            http_response_code(200);

            $array["success"] = true;
            $array["message"] = "Acceso Garantizado";
            $array["data"] = $viaje->viajes;

            echo json_encode($array);
        } else {
            $array["success"] = false;
            $array["message"] = "E1: Acceso Denegado";

            echo json_encode($array);
        }
    } catch (\Throwable $th) {


        $array["success"] = false;
        $array["message"] = "C: Acceso Denegado";

        echo json_encode($array);
    }
} else {

    $array["success"] = false;
    $array["message"] = "E2: Acceso Denegado";

    echo json_encode($array);
}
