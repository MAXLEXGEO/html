<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include '../../assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $marca    = $_POST['nombre_marca'];
    $id_marca = $_POST['id_marca'];
    $status   = $_POST['marca_status'];

	//ACTUALIZACION DE DATOS
	$sql = "UPDATE marca SET marca = '$marca', status = '$status' WHERE id_marca = $id_marca";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}