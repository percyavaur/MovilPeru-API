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
        $html_adultos = "<div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
        <h3>Adultos</h3>";
        $contador_adultos = 0;
        $html_ninos = "<div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
        <h3>Niños</h3>";
        $contador_ninos = 0;
        $html_bebes = "<div style='display:flex; flex-direction:column; align-items: flex-start; padding: 0.75rem;width: 100%;'>
        <h3>Bebés</h3>";
        $contador_bebes = 0;
        $html_vuelta = "";

        $ida_origen_destino = '';
        $ida_fecha = '';
        $ida_hora = '';
        $vuelta_origen_destino = '';
        $vuelta_fecha = '';
        $vuelta_hora ='';
        $vuelta = false;

        foreach ($infopasajes as $value) {
            if($value["idTipoPasaje"] == 1){
                $ida_origen_destino = $value['idaOrigen']." - ".$value['idaDestino']; 
                $ida_fecha = $value['idaFecha'];
                $ida_hora = $value['idaHora'];
                if($value['vueltaOrigen'] != null){
                    $vuelta = true;
                    $vuelta_origen_destino = $value['vueltaOrigen']." - ".$value['vueltaDestino']; 
                    $vuelta_fecha = $value['vueltaFecha'];
                    $vuelta_hora = $value['vueltaHora'];
                }
                $html_adultos .= "
                    <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                        <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                            <div style='display:flex; flex-direction:row;width: 33%;'>
                                <label for=''>NOMBRES: </label>
                                <span style='margin-left: 0.75rem;'>".$value['nombres']."</span>
                                <label for=''>APELLIDOS: </label>
                                <span style='margin-left: 0.75rem;'>".$value['apellidos']."</span>
                                <label for=''>".$value['tipoDocumento']."</label>
                                <span style='margin-left: 0.75rem;'>".$value['numDocumento']."</span>
                            </div>
                        </div>
                    </div>
                </div>";
            }else if($value["idTipoPasaje"] == 2){
                $html_ninos .= "
                    <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                        <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                            <div style='display:flex; flex-direction:row;width: 33%;'>
                                <label for=''>NOMBRES: </label>
                                <span style='margin-left: 0.75rem;'>".$value['nombres']."</span>
                                <label for=''>APELLIDOS: </label>
                                <span style='margin-left: 0.75rem;'>".$value['apellidos']."</span>
                                <label for=''>".$value['tipoDocumento']."</label>
                                <span style='margin-left: 0.75rem;'>".$value['numDocumento']."</span>
                            </div>
                        </div>
                    </div>
                </div>";
            }else{
                $html_bebes .= "
                <div style='bg-tabs margin-bottom: 1rem; padding: 1.5rem;width: 100%; border-radius: 10px;'>
                    <div style='display:flex; flex-direction:row; justify-content-between align-items: center; margin-bottom: 1rem;'>
                        <div style='display:flex; flex-direction:row;width: 33%;'>
                            <label for=''>NOMBRES: </label>
                            <span style='margin-left: 0.75rem;'>".$value['nombres']."</span>
                            <label for=''>APELLIDOS: </label>
                            <span style='margin-left: 0.75rem;'>".$value['apellidos']."</span>
                            <label for=''>".$value['tipoDocumento']."</label>
                            <span style='margin-left: 0.75rem;'>".$value['numDocumento']."</span>
                        </div>
                    </div>
                </div>
            </div>";
            }
        }
        
        if($vuelta == true){
            $texto_ida_o_vuelta = "Solo Ida";
        }else{
            $texto_ida_o_vuelta = "Ida y Vuelta";
            $html_vuelta = "
            <div style='display:flex; flex-direction:column;width: 47%;'><span
                    title='$vuelta_origen_destino'
                    style='color: #dc3545; display:flex; flex-direction:row; align-items:center;width: 100%; max-height: 27px;'>
                    Vuelta:
                    <span style='color: black;' style='text-overflow: ellipsis; overflow: hidden;'>
                    $vuelta_origen_destino</span></span><span
                    style='color: #dc3545; display:flex; flex-direction:row; align-items:center'>
                    Hora de Vuelta:
                    <span style='color: black; padding-left: 0.75rem;'> $vuelta_hora</span></span>
            </div>";
        }
        
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
            $mail->Body    = "
            <center style='flex-direction:column; padding: 1.5rem; border-radius: 10px;'>
                    <h1 style='color:black;'> RESERVA N° $ticket</h1><br>
                    <h3 style='color:#dc3545'>$texto_ida_o_vuelta</h3>
                    <h3 style='color:#dc3545'>Fecha de Ida: $ida_fecha</h3><br>
                    <div style='display:flex; flex-direction:row; justify-content:space-between; margin-top: 1rem; margin-bottom: 1rem;'>
                        <div style='display:flex; flex-direction:column;width: 47%;'>
                            <span title='$ida_origen_destino'
                                style='display:flex; flex-direction:row; align-items:center; color: #dc3545;width: 100%; max-height: 27px;'>
                                Ida:
                                <span style='color: black;text-overflow: ellipsis; overflow: hidden;'>
                                $ida_origen_destino</span>
                            </span>
                            <span style='color: #dc3545; display:flex; flex-direction:row; align-items:center;'>
                                Hora de Ida:
                                <span style='color: black; padding-left: 0.75rem;'>$ida_hora</span></span>
                        </div>
                        $html_vuelta
                    </div><br>
                    <div style='flex-direction:column; align-items: flex-start;'>
                        <h2>Información de Pasajeros</h2>
                        $html_adultos<br>
                        $html_ninos<br>
                        $html_bebes<br>
                    </div>
                </center>";
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