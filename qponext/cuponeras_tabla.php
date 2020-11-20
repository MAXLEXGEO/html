<?php
	header('Content-type: application/json; charset=utf-8');
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'header.php';
    $db = new mysqlDB;

    if($user_data['rol'] == "1"){//VALIDA LA LISTA DE CUPONES SI ES UN ADMIN EL QUE ESTA EN SESIÓN
        //CONSULTA CUPONERAS
        $mis = "SELECT * FROM vw_cuponeras ORDER BY id_cuponera DESC";
    }else{
        //CONSULTA CUPONERAS
        $mis = "SELECT * FROM vw_cuponeras WHERE id_empresa =".$user_data['empresa']." ORDER BY id_cuponera DESC";
    }

	//EJECUTA LA CONSULTA
    $db->query($mis);

    //RESPUESTA
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cuponeras[] = $fila;

        }

        $json_data = json_encode($cuponeras);
		
		echo $json_data;

    }