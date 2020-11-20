<?php
  //CONSULTAR MARCAS
  function get_marcas(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM marca ORDER BY marca ASC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $marcas[] = $fila;

        }

      return $marcas;
    }
  }

  //STATUS MARCAS
  function get_status_marca($status){

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
  function get_marca_det($marca){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM marca WHERE id_marca = $marca LIMIT 1";
    $db->query($mis);

    return $db->fetch();
  }