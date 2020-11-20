<?php
  //CONSULTAR CUPONERAS
  function get_cuponeras_vw(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cuponeras";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cuponeras[] = $fila;

        }

      return $cuponeras;
    }
  }

  //STATUS CUPONERAS
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

  //CONSULTAR EMPRESAS SELECT
  function get_empresas_select(){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT id_empresa,nombre FROM empresa WHERE status = 'A' ORDER BY nombre ASC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $empresas[] = $fila;

        }

      return $empresas;
    }
  }

  //COL CUPONES
  function get_cupones_col($cuponera){

    $db = new mysqlDB;

    $mis = "SELECT COUNT(*) AS num_cupones FROM cupon WHERE id_cuponera = $cuponera";
    $db->query($mis);
    
    $num_cupones     = $db->fetch();
    $num_cupones_int = intval($num_cupones['num_cupones']);

    switch ($num_cupones_int) {
        case $num_cupones_int <= 4:
            $col = 'col-md-6';
            break;

        case $num_cupones_int > 4 && $num_cupones_int <= 9:
            $col = 'col-md-4';
            break;

        case $num_cupones_int > 9:
            $col = 'col-md-3r';
            break;
    }

    return $col;
  }

  //COL CUPONES
  function get_cupones_col_view($cuponera){

    $db = new mysqlDB;

    $mis = "SELECT COUNT(*) AS num_cupones FROM cupon WHERE SHA1(id_cuponera) = '$cuponera'";
    $db->query($mis);
    
    $num_cupones     = $db->fetch();
    $num_cupones_int = intval($num_cupones['num_cupones']);

    switch ($num_cupones_int) {
        case $num_cupones_int <= 4:
            $col = 'col-md-6';
            break;

        case $num_cupones_int > 4 && $num_cupones_int <= 9:
            $col = 'col-md-4';
            break;

        case $num_cupones_int > 9:
            $col = 'col-md-3';
            break;
    }

    return $col;
  }

  //CONSULTAR CUPONES
  function get_cupones_per($cuponera){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cupones WHERE id_cuponera = $cuponera ORDER BY id_cupon DESC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cupones[] = $fila;

        }

      return $cupones;
    }
  }

  //CONSULTAR CUPONES - VIEW
  function get_cupones_view($cuponera){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cupones WHERE SHA1(id_cuponera) = '$cuponera' AND disponibles > 0 AND status_cupon = 'A'  ORDER BY id_cupon DESC";
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $cupones[] = $fila;

        }

      return $cupones;
    }
  }

  //DESCUENTO CUPON
  function get_descuento_per($descuento,$tipo_descuento){

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

  //CONSULTAR CUPONERA DETALLES
  function get_cuponera_detalles($cuponera){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cuponeras WHERE id_cuponera = $cuponera";
    $db->query($mis);
    return $db->fetch();
  }

  //CONSULTAR CUPONERA DETALLES - VIEW
  function get_cuponera_detalles_view($cuponera){
    
    $db = new mysqlDB;    
    
    $mis = "SELECT * FROM vw_cuponeras WHERE SHA1(id_cuponera) = '$cuponera'";
    $db->query($mis);
    return $db->fetch();
  }