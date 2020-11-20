<?php
    header('Content-type: application/json; charset=utf-8');
    //INVOCAR EL ARCHIVO DE CONEXION
    include 'header.php';
    $db = new mysqlDB;

    if($user_data['rol'] == "1"){//VALIDA LA LISTA DE CUPONES SI ES UN ADMIN EL QUE ESTA EN SESIÓN
        //CONSULTA EMPRESAS
        $mis = "SELECT * FROM vw_usuarios ORDER BY rol ASC";
    }else{
        //CONSULTA EMPRESAS
        $empresa = $user_data['empresa'];
        $mis = "SELECT * FROM vw_usuarios WHERE id_empresa = $empresa AND rol <> 'admin' ORDER BY rol ASC";
    }

    //EJECUTA LA CONSULTA
    $db->query($mis);

    //RESPUESTA
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $usuarios[] = $fila;

        }

        $json_data = json_encode($usuarios);
        
        echo $json_data;
    }