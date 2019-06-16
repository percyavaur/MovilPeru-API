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

$user->username = $data->username;
$user->nombres = $data->nombres;
$user->apellidos = $data->apellidos;
$user->genero = $data->genero;
$user->fecNac = $data->fecNac;
$user->tipoDocumento = $data->tipoDocumento;
$user->numDocumento = $data->numDocumento;
$user->correoElectronico = $data->correoElectronico;
$user->direccion = $data->direccion;
$user->telefono = $data->telefono;
$user->imagen = $data->imagen;
$user->password = $data->password;

$array = [];
if ($jwt) {

    $decoded = JWT::decode($jwt, $key, array('HS256'));

    $user->idUsuario = $decoded->data->idUsuario;

    if (!$data->idEstado) {
        $data->idEstado = $decoded->data->idEstado;
    } else {
        $user->idEstado = $data->idEstado;
    }

    if (!$data->idRol) {
        $data->idRol = $decoded->data->idRol;
    } else {
        $user->idRol = $data->idRol;
    }

    if (!$user->usernameExists()) {
        try {
            if ($user->update()) {

                // Regenerar jwt

                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "data" => array(
                        "idUsuario" => $user->idUsuario,
                        "username" => $user->username,
                        "idRol" => $user->idRol,
                        "rol" => $user->rol,
                        "tipoDocumento" => $user->tipoDocumento,
                        "numDocumento" => $user->numDocumento,
                        "apellidos" => $user->apellidos,
                        "nombres" => $user->nombres,
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
                $jwt = JWT::encode($token, $key);
                http_response_code(200);

                $array["message"] = "Datos del usuario actualizado correctamente.";
                $array["success"] = true;
                $array["jwt"] = $jwt;

                echo json_encode($array);
            } else {
                // http_response_code(401);

                $array["message"] = "E: Ocurrió un error, al intentar actualizar los datos del usuario.";
                $array["success"] = false;

                echo json_encode($array);
            }
        } catch (Exception $e) {
            // http_response_code(401);

            $array["message"] = "C: Ocurrió un error, al intentar actualizar los datos del usuario.";
            $array["success"] = false;
            $array["error"] = $e->getMessage();

            echo json_encode($array);
        }
    } else {
        $array["message"] = "El nombre de usuario " . $data->username . " ya existe, intente otra vez.";
        $array["success"] = false;
        $array["data"] = $data->username;
        echo json_encode($array);
    }
} else {
    // http_response_code(401);

    $array["message"] = "Acceso Denegado.";
    $array["success"] = false;

    echo json_encode($array);
}
