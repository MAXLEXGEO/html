<?php
	//INVOCAR EL ARCHIVO DE CONEXION
	date_default_timezone_set('America/Mexico_City');
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES
    $cupon = $_POST['cupon'];
    $fecha = date('Y-m-d');

	//CANJEAR CUPON
	$sql = "UPDATE cupon SET disponibles = disponibles - 1, canjeados = canjeados + 1,fecha_cobro = '$fecha' WHERE id_cupon = $cupon";
	$db->query($sql);

	//VERIFICAR SI YA NO HAY DISPONIBLES
	$sql_disponibles = "SELECT disponibles FROM cupon WHERE id_cupon = $cupon";
	$db->query($sql_disponibles);
	$disponibles = $db->fetch()['disponibles'];

	//SI YA NO HAY DISPONIBLES CADUCA EL CUPON
	if(intval($disponibles) == 0){
		$sql_caduca = "UPDATE cupon SET status = 'I'";
		$db->query($sql_caduca);
	}

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}