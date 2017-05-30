<?php
// var_dump($_POST);
require ('PHPMailer/PHPMailerAutoload.php') ;

$from       = 'info@lovestorytravels.com';
$fromName   = 'Love Story Travels';
$to         = $_POST['sendEmailTo'];
//$to = 'jmunoz.comunicacion@gmail.com';
$replyTo    = 'info@lovestorytravels.com';

$mail       = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP                                    // TCP port to connect to

$mail->From = $from;
$mail->FromName = $fromName;
$mail->addAddress($to, 'Info');     // Add a recipient             // Name is optional
$mail->addReplyTo($replyTo, 'Info from Love Story Travels');
$mail->addBCC('raul@wheretogo.com.mx');
$mail->addBCC('info@elitemgroup.com');


$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $_POST['sendEmailSubject'];
$mail->Body    = $_POST['sendEmailContent'];


if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'success';
}