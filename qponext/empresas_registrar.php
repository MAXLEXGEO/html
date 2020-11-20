<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $empresa   = $_POST['empresa'];
    $domicilio = $_POST['domicilio'];
    $telefono  = $_POST['telefono'];

    if ($_FILES['logo']['size'] > 1000000) {
            
            echo "LIMIT"; die();

    }else{

		//REGISTRO DE DATOS
		$sql = "INSERT INTO empresa (nombre,domicilio,telefono) VALUES ('$empresa','$domicilio','$telefono')";
		$db->query($sql);
		
		//ID DE LA EMPRESA REGISTRADA
		$empresa_id = $db->insertid();
		
		//COPIA EL LOGO A LA RUTA ESPECIFICADA
		$file_logo = basename($_FILES["logo"]["name"]);
        $file_type_logo = pathinfo($file_logo,PATHINFO_EXTENSION);
        $file_logo = $empresa_id.".".$file_type_logo;
        $ruta = "assets/images/empresa-logo/$file_logo";
        copy($_FILES['logo']['tmp_name'],$ruta);
        
        //ACTUALIZA LA RUTA DEL LOGO
        $sql = "UPDATE empresa SET logo = '$file_logo' WHERE id_empresa = $empresa_id";
		$db->query($sql);

		//VALIDA EL REGISTRO PARA LA RESPUESTA
		if(!$db->error()){
		
			echo "SUCCESS";
		
		}else{
		
			echo "FAILED";
		}
	}