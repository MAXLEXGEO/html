<?php
  //CONSULTAR PRESENTACIONES
  function get_presentaciones(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM presentacion";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $presentaciones[] = $fila;

        }

      return $presentaciones;
    }
  }

  //STATUS PRESENTACIONES
  function get_status_presentacion($status){

    switch ($status) {
      
      case 'A'://ACTIVA
          $status_text = '<button type="button" class="btn btn-success btn-round btn-xs waves-effect waves-classic">ACTIVA</button>';
          break;
      case 'I'://INACTIVA - DADA DE BAJA
          $status_text = '<button type="button" class="btn btn-danger btn-round btn-xs waves-effect waves-classic">INACTIVA</button>';
          break;
    }

    return $status_text;
  }

  //CONSULTAR DETALLES
  function get_presentacion_det($presentacion){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM presentacion WHERE id_presentacion = $presentacion LIMIT 1";
    $db->query($mis);

    return $db->fetch();
  }