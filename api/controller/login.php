<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/user.php';
//librerias para jwt
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->username = $data->username;
$username_exists = $user->usernameExists();

// check if username exists and if password is correct
$array = [];
if ($username_exists && password_verify($data->password, $user->password)) {

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "idUsuario" => $user->idUsuario,
            "idRol" => $user->idRol,
            "rol" => $user->rol,
            "username" => $user->username,
            "tipoDocumento" => $user->tipoDocumento,
            "numDocumento" => $user->numDocumento,
            "apellidos" => $user->apellidos,
            "nombres" => $user->nombres,
            "genero" => $user->genero,
            "fecNac" => $user->fecNac,
            "correoElectronico" => $user->correoElectronico,
            "direccion" => $user->direccion,
            "telefono" => $user->telefono,
            "estadoCivil" => $user->estadoCivil,
            "imagen" => $user->imagen,
            "idEstado" => $user->idEstado,
            "estado" => $user->estado
        )
    );

    // set response code
    http_response_code(200);
    // generate jwt
    $jwt = JWT::encode($token, $key);

    $array["message"] = "Ingresó correctamente.";
    $array["success"] = true;
    $array["jwt"] = $jwt;

    echo json_encode($array);
} else {
    // http_response_code(401);

    $array["message"] = "Usuario y/o Contraseña Incorrecta.";
    $array["success"] = false;

    echo json_encode($array);
}
