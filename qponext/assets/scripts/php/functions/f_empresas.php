<?php
  //CONSULTAR EMPRESAS
  function get_empresas(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM empresa ORDER BY nombre ASC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $empresas[] = $fila;

        }

      return $empresas;
    }
  }

  //STATUS EMPRESAS
  function get_status($status){

    switch ($status) {
      
      case 'A'://ACTIVA
          $status_text = '<b class="text-success">ACTIVA</b>';
          break;
      case 'I'://INACTIVA - DADA DE BAJA
          $status_text = '<b class="text-danger">INACTIVA</b>';
          break;
    }

    return $status_text;
  }