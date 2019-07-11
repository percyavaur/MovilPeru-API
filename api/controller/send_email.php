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
            $mail->Body    = "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <meta http-equiv='X-UA-Compatible' content='ie=edge'>
                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
            </head>
            <body>
                <div style='display:flex; flex-direction:column; padding: 1.5rem; border-radius: 10px;'>
                    <div style='display:flex; flex-direction:row; justify-content:space-between;'>
                        <h1 style='color: #dc3545;'>Ida y Vuelta</h1>
                        <h1 style='color: black;'>RESERVA N° $ticket</h1>
                        <h1 style='color: #dc3545;'>Fecha de Ida y de Vuelta</h1>
                    </div>
                    <div style='display:flex; flex-direction:row; justify-content:space-between; margin-top: 1rem; margin-bottom: 1rem;'>
                        <div style='display:flex; flex-direction:column;width: 47%;'>
                            <span title='Lima, Los Olivos, Terminal los olivos-Piura, Mancora, Terminal Mancora'
                                style='display:flex; flex-direction:row; align-items:center; color: #dc3545;width: 100%; max-height: 27px;'>
                                Ida:
                                <span style='color: black;text-overflow: ellipsis; overflow: hidden;'>Lima, Los Olivos,
                                    Terminal los olivos - Piura, Mancora, Terminal Mancora</span>
                            </span>
                            <span style='color: #dc3545; display:flex; flex-direction:row; align-items:center;'>
                                Hora de Ida:
                                <span style='color: black; padding-left: 0.75rem;'> 16:00:00</span></span>
                        </div>
                        <div style='display:flex; flex-direction:column;width: 47%;'><span
                                title='Piura, Mancora, Terminal Mancora-Lima, Los Olivos, Terminal los olivos'
                                style='color: #dc3545; display:flex; flex-direction:row; align-items:center;width: 100%; max-height: 27px;'>
                                Vuelta:
                                <span style='color: black;' style='text-overflow: ellipsis; overflow: hidden;'>Piura, Mancora,
                                    Terminal Mancora - Lima, Los Olivos, Terminal los olivos</span></span><span
                                style='color: #dc3545; display:flex; flex-direction:row; align-items:center'>
                                Hora de Vuelta:
                                <span style='color: black; padding-left: 0.75rem;'> 15:00:00</span></span></div>
                    </div>
                    <div style='display:flex; flex-direction:column; align-items: flex-start;'>
                        <h2>Información de Pasajeros</h2>
                        <div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
                            <h3>Adultos: 1</h3>
                            <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                                <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>NOMBRES: </label>
                                        <span style='margin-left: 0.75rem;'>NOMBRES</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>APELLIDOS: </label>
                                        <span style='margin-left: 0.75rem;'>APELLIDOS</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>DOCUMENTO: </label>
                                        <span style='margin-left: 0.75rem;'>NUMERO DE DOCUMENTO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
                            <h3>Niños: 1</h3>
                            <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                                <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>NOMBRES: </label>
                                        <span style='margin-left: 0.75rem;'>NOMBRES</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>APELLIDOS: </label>
                                        <span style='margin-left: 0.75rem;'>APELLIDOS</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>DOCUMENTO: </label>
                                        <span style='margin-left: 0.75rem;'>NUMERO DE DOCUMENTO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
                            <h3>Bebés: 1</h3>
                            <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                                <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>NOMBRES: </label>
                                        <span style='margin-left: 0.75rem;'>NOMBRES</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>APELLIDOS: </label>
                                        <span style='margin-left: 0.75rem;'>APELLIDOS</span>
                                    </div>
                                    <div style='display:flex; flex-direction:row;width: 33%;'>
                                        <label for=''>DOCUMENTO: </label>
                                        <span style='margin-left: 0.75rem;'>NUMERO DE DOCUMENTO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            
            </html>";
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