<?php

    use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

    require '../assets/PHPMailer/src/Exception.php';
    require '../assets/PHPMailer/src/PHPMailer.php';
    require '../assets/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //SMTP::DEBUG_OFF                     //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';  //Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'rodrigobarriossaavedra19@gmail.com'; //SMTP username
        $mail->Password   = 'zvodmflxidyelkbp';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('rodrigobarriossaavedra19@gmail.com', 'FABRIDEV');
        $mail->addAddress('fabriziobarrios22@gmail.com', 'RFBS');     //Add a recipient

        //Attachments
        /*
        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        */

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'DETALLES DE SU COMPRA';

        $cuerpo= '<h4>GRACIAS POR SU COMPRA</h4>';
        $cuerpo .= '<p>PRODUCTOS QUE COMPRO <b>'. $id_transaccion . '</b></p>';

        $mail->Body    = utf8_decode($cuerpo);
        $mail->AltBody = 'le enviamos los detalles de su compra.';

        $mail->setLanguage('es', '../assets/PHPMailer/language/phpmailer.lang-es.php');
        
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
        exit;
    }
        

?>