<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include '../../assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $presentacion    = $_POST['nombre_presentacion'];
    $id_presentacion = $_POST['id_presentacion'];
    $status   		 = $_POST['presentacion_status'];

	//ACTUALIZACION DE DATOS
	$sql = "UPDATE presentacion SET presentacion = '$presentacion', status = '$status' WHERE id_presentacion = $id_presentacion";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}