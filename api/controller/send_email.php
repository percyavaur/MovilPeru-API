<?php
// required headers
header("Access-Control-Allow-Origin:  *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $data = json_decode(file_get_contents("php://input"));

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require('PHPMailer/Exception.php');
    require('PHPMailer/PHPMailer.php');
    require('PHPMailer/SMTP.php');

    $array = [];

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer();
    // $array["ticket"] = $data->ticket;
    // $array["destinatario"] = $data->destinatario;
    // echo json_encode($array);

        $mail->SMTPDebug = 0;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'movilperu.info@gmail.com';             // SMTP username
        $mail->Password   = 'jueves2706';                           // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('movilperu.info@gmail.com', 'Movil Peru');
        $mail->addAddress('oscarmbravoc@gmail.com', 'Usuario de Movil Peru');     // Add a recipient

        // Content
        $mail->Subject = 'Movil Peru - Su Reserva ha sido exitosa';
        $mail->Body    = 'Se ha realizado una Reserva de un Viaje con número de ticket ';
        $mail->isHTML(false);                                         // Set email format to HTML

        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo; 
            return false;
        } else {
            $error = 'Message sent!';
            return true;
        }