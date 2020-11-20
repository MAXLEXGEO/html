<?php
    header('Content-type: application/json; charset=utf-8');
    //INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

    //CONSULTA EMPRESAS
    $mis = "SELECT * FROM empresa ORDER BY nombre ASC";
    $db->query($mis);

    //RESPUESTA
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $empresas[] = $fila;

        }

        $json_data = json_encode($empresas);
        
        echo $json_data;

    }