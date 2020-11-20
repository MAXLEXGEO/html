<?php
    error_reporting(E_ERROR);
    
    //SESSION START
    session_start();
    $_SESSION['hora'] = time();
    $user_data        = $_SESSION['user_data'];

    //VALIDAR DATOS DE LA SESION
    if(!is_array($user_data)){ session_destroy(); header("Location: index.php"); die; }

    //CONECCION A LA BASE DE DATOS
    require_once 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

    //MENU DEL PANEL
    switch ($user_data['rol']){
        case '1':
            $menu = 'menu.php';
        break;

        case '2':
            $menu = 'menu-gerente.php';
        break;

        case '3':
            $menu = 'menu-staff.php';
        break;
    }