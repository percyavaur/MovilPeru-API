<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../model/down/email.php';

$data = json_decode(file_get_contents("php://input"));

$email = new Email($data->ticket,$data->destinatario);

$array = [];
if ($email->send_mail()) {
    $array["message"] = "El correo ha sido enviado correctamente";
    $array["success"] = true;
    echo json_encode($array);

} else {
    $array["message"] = "Error al enviar correo";
    $array["success"] = false;

    echo json_encode($array);
}