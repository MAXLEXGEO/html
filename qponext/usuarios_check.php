<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $login 	   = $db->real_escape(strip_tags($_POST['usuario'],ENT_QUOTES));
    $empresa   = $_POST['empresa'];

	//REGISTRO DE DATOS
	$sql = "SELECT * FROM usuario WHERE id_empresa = $empresa AND login = '$login'";
	$db->query($sql);
	$res = $db->fetch();

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(empty($res)){
	
		echo "CONTINUE";
	
	}else{
	
		echo "USER_ALREADY_EXISTS";
	}