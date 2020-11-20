<?php
	header('Content-type: application/json; charset=utf-8');
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $empresa 		 = $_POST['empresa'];
    $codigo  		 = $_POST['codigo'];
    $fecha_inicio 	 = $_POST['inicio'];
    $fecha_caducidad = $_POST['caducidad'];

	//REGISTRO DE DATOS
	$sql = "INSERT INTO cuponera (id_empresa,codigo,inicio,caducidad) VALUES ($empresa,'$codigo','$fecha_inicio','$fecha_caducidad')";
	$db->query($sql);
	$cuponera = $db->insertid();

	//RESPUESTA
	$cuponera_data = [
        "cuponera" => $db->insertid(),
        "res" 	   => "SUCCESS"
    ]; 

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo json_encode($cuponera_data);
	
	}else{
	
		echo "FAILED";
	}