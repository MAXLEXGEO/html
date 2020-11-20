<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include '../../assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $categoria    = $_POST['nombre_categoria'];
    $id_categoria = $_POST['id_categoria'];
    $status 	  = $_POST['categoria_status'];

	//REGISTRO DE DATOS
	$sql = "UPDATE categoria SET categoria = '$categoria', status = '$status' WHERE id_categoria = $id_categoria";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}