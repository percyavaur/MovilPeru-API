<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../libs/PHPMAILER/PHPMailer.php';
require_once '../libs/PHPMAILER/SMTP.php';
require_once '../libs/PHPMAILER/Exception.php';
require_once '../libs/PHPMAILER/OAuth.php';

$email = new PHPMailer\PHPMailer\PHPMailer();

$mail->isSMTP();

$data = json_decode(file_get_contents("php://input"));

$correoDestinatario = $data->destinatario;
$ticket = $data->ticket;

$mail->SMTPDebug = 2;                                       // Enable verbose debug output
$mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
$mail->Port       = 587;                                    // TCP port to connect to
$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'movilperu.info@gmail.com';             // SMTP username
$mail->Password   = 'jueves2706';                           // SMTP password

//Recipients
$mail->setFrom('movilperu.info@gmail.com', 'Movil Peru');
$mail->addAddress($correoDestinatario, 'Usuario de Movil Peru');     // Add a recipient

// Content
$mail->Subject = 'Movil Peru - Su Reserva ha sido exitosa';
$mail->Body    = 'Se ha realizado una Reserva de un Viaje con número de ticket ' + $ticket;
$mail->isHTML(true);                                         // Set email format to HTML

$array = [];
if(!$mail->send()){
    $array["message"] = "Error al enviar correo";
    $array["success"] = false;

    echo json_encode($array);
}else{
    $array["message"] = "El correo ha sido enviado correctamente";
    $array["success"] = true;

    echo json_encode($array);
}