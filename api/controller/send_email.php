<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$data = json_decode(file_get_contents("php://input"));

$correoDestinatario = $data->destinatario;
$ticket = $data->ticket;

$array = [];
try {
    
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

    $mail->send();
    $array["message"] = "Error al enviar correo";
    $array["success"] = false;
    echo json_encode($array);

} catch (Exception $e) {
    $array["message"] = "El correo ha sido enviado correctamente: $mail->ErrorInfo";
    $array["success"] = true;

    echo json_encode($array);
}