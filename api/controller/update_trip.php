<?php
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/viaje.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;


$data = json_decode(file_get_contents("php://input"));
$database = new Database();
$db = $database->getConnection();
$viaje = new viaje($db);

$viaje->idViaje = $data->idViaje;
$viaje->idConductor = $data->idConductor;
$viaje->idVehiculo = $data->idVehiculo;
$viaje->departureDate = $data->fechaSalida ." ". $data->horaSalida;
$viaje->arriveDate = $data->fechaLlegada ." ". $data->horaLlegada;
$viaje->idOrigen = $data->idOrigen;
$viaje->idDestino = $data->idDestino;
$viaje->precio = $data->precio;

$viaje->departure = $data->departure;
$viaje->arrive = $data->arrive;

$data = json_decode(file_get_contents("php://input"));

$jwt = isset($data->jwt) ? $data->jwt : "";

$array = [];

if ($jwt) {

    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $typeUser = $decoded->data->idRol;

        if ($typeUser == 1 || $typeUser == 2) {
            if ($data->idOrigen != $data->idDestino) {
                if ($viaje->updateTrip()) {
                    http_response_code(200);

                    $array["success"] = true;
                    $array["message"] = "Viaje actualizado";
                    $array["testviaje"] = $viaje;
                    $array["testdata"] = $data;
                    echo json_encode($array);
                } else {
                    $array["success"] = false;
                    $array["message"] = "Error al actualizar viaje";
                    $array["testviaje"] = $viaje;
                    $array["testdata"] = $data;
                    echo json_encode($array);
                }
            } else {
                $array["success"] = false;
                $array["message"] = "El destino y origen deben ser diferentes";

                echo json_encode($array);
            }
        } else {
            $array["success"] = false;
            $array["message"] = "Acceso Denegado";

            echo json_encode($array);
        }
    } catch (Exception $e) {

        $array["success"] = false;
        $array["message"] = "Acceso Denegado";

        echo json_encode($array);
    }
} else {

    $array["success"] = false;
    $array["message"] = "Acceso Denegado";

    echo json_encode($array);
}
