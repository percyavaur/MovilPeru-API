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

include_once '../config/database.php';
include_once '../model/down/pasaje.php';
// Load Composer's autoloader
require '../vendor/autoload.php';

$database = new Database();
$db = $database->getConnection();
$pasaje = new Pasaje($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$pasaje->idVenta = $data->ticket;
$usuario = $data->destinatario;
$ticket = $data->ticket;

$array = [];

if($pasaje->tripInfoPassenger()){

    try {
        http_response_code(200);
        $infopasajes = $pasaje->pasajesa;
        $html = '';

        // foreach ($infopasajes as $value) {
        //     $html .= $value;
        // }
        
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'movilperu.info@gmail.com';                     // SMTP username
            $mail->Password   = 'movilperu';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to
            
            //Recipients
            $mail->setFrom('movilperu.info@gmail.com','Movil Perú');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress($usuario);               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
            
            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Ha realizado una reserva con Movil Perú';
            $mail->Body    = "Usted ha realizado una reserva con el número de ticket: <b style='color:red;'>$ticket</b><br>
            $html";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            // Activo condificacción utf-8
            $mail->CharSet = 'UTF-8';
            $mail->send();
            var_dump($infopasajes);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {

        $array["success"] = false;
        $array["message"] = "Acceso Denegado";

        echo json_encode($array);
    }    
}else{
    $array["success"] = false;
    $array["message"] = "Acceso Denegado";

    echo json_encode($array);
}