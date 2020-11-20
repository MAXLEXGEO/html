<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $nombre    = $db->real_escape(strip_tags($_POST['nombre'],ENT_QUOTES));
    $apellidos = $db->real_escape(strip_tags($_POST['apellidos'],ENT_QUOTES));
    $email     = $db->real_escape(strip_tags($_POST['email'],ENT_QUOTES));
    $login 	   = $db->real_escape(strip_tags($_POST['usuario'],ENT_QUOTES));
    $pass 	   = $db->real_escape(strip_tags(sha1($_POST['password']),ENT_QUOTES));
    $rol 	   = $_POST['rol'];
    $empresa   = $_POST['empresa'];

	//REGISTRO DE DATOS
	$sql = "INSERT INTO usuario (id_empresa,id_rol,nombre,apellidos,email,login,pass) VALUES ($empresa,$rol,'$nombre','$apellidos','$email','$login','$pass')";
	$db->query($sql);
	
	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}