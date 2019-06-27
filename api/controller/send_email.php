<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../model/down/email.php';

$email = new Email();

$data = json_decode(file_get_contents("php://input"));

$email->destinatario = $data->destinatario;
$email->ticket = $data->ticket;

$array = [];
if ($email->send_mail()) {
    $array["success"] = true;
    echo json_encode($array);

} else {

    $array["message"] = "Error al enviar correo";
    $array["success"] = false;

    echo json_encode($array);
}
