<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require 'pdf.php';

$mail = new PHPMailer(true);

$fileName = 'Makeup_storage.pdf';
$pdf = new Pdf();
$pdf->printPdf('F', 'Makeup_storage.pdf');

try {
    //Server settings                    
    $mail->isSMTP();                                            
    $mail->Host       = 'localhost';                     
    $mail->SMTPAuth   = false;                                  
    //$mail->Username   = 'user@example.com';                     
    //$mail->Password   = 'secret';                               
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 1025;                                   

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     
    $mail->addAddress('ellen@example.com');               
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('Makeup_storage.pdf');          

    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}