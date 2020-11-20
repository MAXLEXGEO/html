<?php
  //INICIO DE SESIÓN DEL USUARIO
  function user_login($usuario,$password){
    
    $pass = sha1($password);
    $db   = new mysqlDB; 
    
    $mis  = "SELECT * FROM usuario WHERE login = '$usuario' AND pass = '$pass' LIMIT 1";
    $db->query($mis);

    if($db->rows() > 0){

      if(user_active($usuario)){
        
        $db->fetch();
        
        //INICIAR LA SESIÓN
        session_start();
        $user_data = array(
          "usuario"   => $db->row['id_usuario'],
          "email"     => $db->row['email'],
          "nombre"    => $db->row['nombre'],
          "apellidos" => $db->row['apellidos'],
          "rol"       => $db->row['id_rol']
          );

        //VARIABLE DONDE SE GUARDA LA INFORMACION DEL USUARIO
        $_SESSION['user_data'] = $user_data;

        //REDIRECCIONAR AL PANEL
        switch ($user_data['rol']){

          case '1':
            header("Location: cupones.php");
            die();
          break;

          case '2':
            header("Location: cupones.php");
            die();
          break;

          case '3':
            header("Location: cupones_canjear.php");
            die();
          break;
        }
      
      }else{
      
        $response = 'inactive';
      }
      
    }else{
      
      $response = 'nodata';
    }

    return $msg;
  }

  function user_active($usuario){
    
    $db  = new mysqlDB; 
    $mis = "SELECT status FROM usuario WHERE login = '$usuario' LIMIT 1";
    
    $db->query($mis);
    $db->fetch();

    if ($db->row['status'] == 'A'){
      
      return true;
    
    }else{
    
      return false; 
    }
  } 