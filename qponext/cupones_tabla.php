<?php
	header('Content-type: application/json; charset=utf-8');
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

    $cuponera = $_POST['cuponera'];

	//CONSULTA CUPONERAS
	$mis = "SELECT * FROM vw_cupones WHERE id_cuponera = $cuponera ORDER BY id_cupon DESC";
    $db->query($mis);

    //RESPUESTA
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cuponeras[] = $fila;

        }

        $json_data = json_encode($cuponeras);
		
		echo $json_data;

    }