<?php
  //CONSULTAR CATEGORIAS
  function get_categorias(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM categoria ORDER BY categoria ASC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $categorias[] = $fila;

        }

      return $categorias;
    }
  }

  //STATUS CUPONERAS
  function get_status_cat($status){

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
  function get_categoria_det($categoria){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM categoria WHERE id_categoria = $categoria LIMIT 1";
    $db->query($mis);

    return $db->fetch();
  }