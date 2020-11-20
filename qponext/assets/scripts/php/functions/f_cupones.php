<?php
  //CONSULTAR CUPONES
  function get_cupones_vw($empresa){
    
    $db = new mysqlDB;    
    if(empty($empresa)){
      $mis = "SELECT * FROM vw_cupones ORDER BY id_cupon DESC";
    }else{
      $mis = "SELECT * FROM vw_cupones WHERE id_empresa = $empresa ORDER BY id_cupon DESC";
    }
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cupones[] = $fila;

        }

      return $cupones;
    }
  }

  //CONSULTAR TOTALES
  function get_totales($empresa){

    $db = new mysqlDB;
    
    if(empty($empresa)){
    
      $mis ="SELECT * FROM vw_totales";
    
    }else{
      $mis ="SELECT 
              (SELECT COUNT(*) FROM cupon c INNER JOIN cuponera cu ON cu.id_cuponera = c.id_cuponera WHERE cu.id_empresa = $empresa) AS total_cupones,
              (SELECT COUNT(*) FROM cupon c INNER JOIN cuponera cu ON cu.id_cuponera = c.id_cuponera WHERE cu.id_empresa = $empresa AND c.status = 'A') AS total_vigentes,
              (SELECT SUM(c.canjeados) FROM cupon c INNER JOIN cuponera cu ON cu.id_cuponera = c.id_cuponera WHERE cu.id_empresa = $empresa) AS total_canjeados,
              (SELECT COUNT(*) FROM cupon c INNER JOIN cuponera cu ON cu.id_cuponera = c.id_cuponera WHERE cu.id_empresa = $empresa AND (c.status = 'I' OR c.disponibles = 0)) AS total_caducados";
    }
    
    //EJECUTA LA CONSULTA
    $db->query($mis);

    if($db->rows() > 0){
      return $db->fetch();
    }
  }

  //DESCUENTO CUPON
  function get_descuento($descuento,$tipo_descuento){

    switch ($tipo_descuento) {
      
      case 'PCT':
          $descuento_text = round($descuento).' %';
          break;
      case 'MND':
          $descuento_text = '$ '.$descuento;
          break;
    }

    return $descuento_text;
  }

  //STATUS CUPON
  function get_status($status){

    switch ($status) {
      
      case 'A'://VIGENTE - PENDIENTE DE CANJEO
          $status_text = '<b class="text-success">VIGENTE</b>';
          break;
      case 'C'://CANJEADO
          $status_text = '<b class="text-secondary">CANJEADO</b>';
          break;
      case 'I'://CADUCADO
          $status_text = '<b class="text-danger">CADUCADO</b>';
          break;
    }

    return $status_text;
  }

  //DETALLES DEL CUPON
  function get_cupon_det($cupon){

    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cupones WHERE id_cupon = $cupon";
    $db->query($mis);
    
    return $db->fetch();
  }