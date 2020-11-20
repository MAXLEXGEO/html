<?php
require '../PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//safe-correo and pass
$username = 'amirsha@rayosxvillanueva.com';
$password = 'Desarrollo2@&';

if(strlen($_POST['lock']) > 0){
	echo "error";
}else{
	//safe
	$mail->isSMTP();   
	$mail->SMTPDebug  = 0;
	$mail->Host = "smtp.ionos.mx";
	$mail->SMTPAuth = true;
	$mail->Username = $username;   
	$mail->Password = $password;                        
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->CharSet = 'UTF-8';

	//cabecera del email-destinatario
	$mail->SetFrom('contacto@e-stamp.com', 'Contacto MAXLEX GEO');
	$mail->AddReplyTo('contacto@e-stamp.com', 'Contacto MAXLEX GEO');
	$mail->addAddress('maxlexgeo@gmail.com');
	$mail->addAddress('contacto@rayosxvillanueva.com');
	$mail->addBCC('maxlexgeo@gmail.com');                     

	//adjunta el XML y PDF
	$mail->WordWrap = 50;
	//$mail->addAttachment('emitidas/'.$route_file.'.pdf');
	//$mail->addAttachment('emitidas/'.$route_file.'.xml');
	$mail->isHTML(true);

	//asunto, cuerpo y envio de email
	$mail->Subject = $_POST['subject'].' | Mensaje de contacto desde rayosxvillanueva.com';
	$mail->Body    = 'Nombre. '.$_POST['name'].'<br>E-Mail. '.$_POST['email'].'<br><br>Mensaje.<br>'.$_POST['message'];
	$mail->AltBody = 'Mensaje de contacto';

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit;
	}

	echo 'OK';
}