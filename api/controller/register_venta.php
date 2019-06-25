<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/venta.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$venta = new Venta($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));
// set product property values

$jwt = isset($data->jwt) ? $data->jwt : "";

$venta->idViajeIda = $data->idViajeIda;
$venta->cantAdultos = $data->cantAdultos;
$venta->cantNinos = $data->cantNinos;
$venta->cantBebes = $data->cantBebes;
$venta->passangersData = $data->passangersData;

if (!$data->idViajeVuelta) {
    $venta->idViajeVuelta = 0;
} else {
    $venta->idViajeVuelta = $data->idViajeVuelta;
}

if ($jwt) {
    $decoded = JWT::decode($jwt, $key, array('HS256'));
    $venta->idUsuario = $decoded->data->idUsuario;
} else {
    $venta->idUsuario = 0;
}

$cantPasajeros = $venta->cantAdultos + $venta->cantNinos + $venta->cantBebes;

$array = [];

if ($venta->registerVenta()) {

    try {
        // set response code
        for ($i = 0; $i < $cantPasajeros; $i++) {

            $venta->idVenta;
            $venta->nombres = $venta->passangersData[$i]->nombres;
            $venta->apellidos = $venta->passangersData[$i]->apellidos;
            $venta->tipoDocumento = $venta->passangersData[$i]->tipoDocumento;
            $venta->numDocumento = $venta->passangersData[$i]->numDocumento;
            $venta->idTipoPasaje = $venta->passangersData[$i]->idTipoPasaje;
            $venta->registerPasajes();
        }
    } catch (\Throwable $th) {

        $array["message"] = "C: Error, please try again.";
        $array["success"] = false;
        echo json_encode($array);
    }
    http_response_code(200);
    $array["success"] = true;
    echo json_encode($array);
} else {

    http_response_code(400);

    $array["message"] = "E: Error, please try again.";
    $array["success"] = false;

    echo json_encode($array);
}
