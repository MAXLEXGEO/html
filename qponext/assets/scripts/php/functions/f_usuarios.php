<?php
  //CONSULTAR ROLES
  function get_roles_select($rol){
    
    $db = new mysqlDB;    
    
    if($rol == '1'){
      $mis = "SELECT * FROM rol ORDER BY rol ASC";
    }else{
      $mis = "SELECT * FROM rol WHERE id_rol <> 1 ORDER BY rol ASC";
    }
    $db->query($mis);
    
    if($db->rows() > 0){
        
        while ($fila = $db->fetch()){

          $roles[] = $fila;

        }

      return $roles;
    }
  }

  //CONSULTAR EMPRESAS SELECT
  function get_empresas_usuarios_select(){
    
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