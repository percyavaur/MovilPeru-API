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
$viaje = new viaje($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$array = [];
$get_origenes = $viaje->getOrigenes();

if ($get_origenes) {

    try {
        http_response_code(200);

        $array["success"] = true;
        $array["message"] = "Acceso Garantizado";
        $array["data"] = $viaje->origenes;

        echo json_encode($array);
    } catch (Exception $e) {

        $array["success"] = false;
        $array["error"] = $e;
        $array["message"] = "C: Internal error";

        echo json_encode($array);
    }
} else {

    $array["success"] = false;
    $array["message"] = "E: Internal error";

    echo json_encode($array);
}
