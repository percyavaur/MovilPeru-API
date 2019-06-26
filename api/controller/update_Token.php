<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../model/down/user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
$jwt = isset($data->jwt) ? $data->jwt : "";
$user->expoToken = $data->expoToken;

$array = [];
if ($jwt) {

    $decoded = JWT::decode($jwt, $key, array('HS256'));

    $user->idUsuario = $decoded->data->idUsuario;;

    if ($user->updateToken()) {

        $array["message"] = "Token Update";
        $array["success"] = true;

        echo json_encode($array);
    }else{
        $array["message"] = "ET : Acceso Denegado";
        $array["success"] = false;

        echo json_encode($array);
    }
} else {
    // http_response_code(401);

    $array["message"] = "JWT: Acceso Denegado.";
    $array["success"] = false;

    echo json_encode($array);
}
