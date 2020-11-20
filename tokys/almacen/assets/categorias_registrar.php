<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include '../../assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $categoria = $_POST['nombre_categoria'];

	//REGISTRO DE DATOS
	$sql = "INSERT INTO categoria (categoria) VALUES ('$categoria')";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}