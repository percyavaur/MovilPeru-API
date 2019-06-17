<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../model/down/user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));
// set product property values
$user->username = $data->username;
$user->password = $data->password;
$user->nombres = $data->nombres;
$user->apellidos = $data->apellidos;
$user->genero = $data->genero;
$user->fecNac = $data->fecNac;
$user->tipoDocumento = $data->tipoDocumento;
$user->numDocumento = $data->numDocumento;
$user->correoElectronico = $data->correoElectronico;
$user->telefono = $data->telefono;

$array = [];


if (!$data->idRol) {
    $user->idRol = 3;
} else {
    $user->idRol = $data->idRol;
}

if (!$data->idEstado) {
    $user->idEstado = 1;
} else {
    $user->idEstado = $data->idEstado;
}


// create the user

if (!$user->usernameExists()) {
    if ($user->create()) {

        // set response code
        http_response_code(200);

        // display message: user was created
        $array["message"] = "Usuario creado exitosamente.";
        $array["success"] = true;
        $array["data"] = $data;
        echo json_encode($array);
        // message if unable to create user

    } else {

        // set response code
        http_response_code(400);

        // display message: unable to create user
        $array["message"] = "Error, please try again.";
        $array["success"] = false;

        echo json_encode($array);
    }
} else {

    // set response code
    // http_response_code(400);

    // display message: unable to create user
    $array["message"] = "El nombre de usuario " . $data->username . " ya existe, intente otra vez.";
    $array["success"] = false;
    $array["data"] = $data->username;

    echo json_encode($array);
}
