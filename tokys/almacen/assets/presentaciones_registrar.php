<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include '../../assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $presentacion = $_POST['nombre_presentacion'];

	//REGISTRO DE DATOS
	$sql = "INSERT INTO presentacion (presentacion) VALUES ('$presentacion')";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}