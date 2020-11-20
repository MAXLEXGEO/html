<?php
  //CONSULTAR PRODUCTOS
  function get_productos(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM producto";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $productos[] = $fila;

        }

      return $productos;
    }
  }

  //STATUS PRODUCTOS
  function get_status_producto($status){

    switch ($status) {
      
      case 'A'://ACTIVA
          $status_text = '<button type="button" class="btn btn-success btn-round btn-xs waves-effect waves-classic">ACTIVO</button>';
          break;
      case 'I'://INACTIVA - DADA DE BAJA
          $status_text = '<button type="button" class="btn btn-danger btn-round btn-xs waves-effect waves-classic">INACTIVO</button>';
          break;
    }

    return $status_text;
  }