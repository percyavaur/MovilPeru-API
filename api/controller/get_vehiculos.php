<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/vehiculo.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$vehiculo = new Vehiculo($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$jwt = isset($data->jwt) ? $data->jwt : "";

$array = [];
$get_vehiculos = $vehiculo->getVehiculos();

if ($jwt && $get_vehiculos) {

    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $typeUser = $decoded->data->idRol;

        if ($typeUser == 1 || $typeUser == 2) {

            http_response_code(200);

            $array["success"] = true;
            $array["message"] = "Acceso Garantizado";
            $array["data"] = $vehiculo->vehiculos;

            echo json_encode($array);
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
