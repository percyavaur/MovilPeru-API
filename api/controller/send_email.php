<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMAILER/Exception.php';
    require 'PHPMAILER/PHPMailer.php';
    require 'PHPMAILER/SMTP.php';

    $data = json_decode(file_get_contents("php://input"));
    $array = [];

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {

        $mail->SMTPDebug = 0;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'movilperu.info@gmail.com';             // SMTP username
        $mail->Password   = 'jueves2706';                           // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('movilperu.info@gmail.com', 'Movil Peru');
        $mail->addAddress($this->destinatario, 'Usuario de Movil Peru');     // Add a recipient

        // Content
        $mail->Subject = 'Movil Peru - Su Reserva ha sido exitosa';
        $mail->Body    = 'Se ha realizado una Reserva de un Viaje con número de ticket ' + $this->ticket;
        $mail->isHTML(true);                                         // Set email format to HTML

        $mail->send();
        $array["message"] = "El correo ha sido enviado correctamente";
        $array["success"] = true;
        echo json_encode($array);
    } catch (Exception $e) {
        $array["message"] = "Error al enviar correo";
        $array["success"] = false;
    
        echo json_encode($array);
    }