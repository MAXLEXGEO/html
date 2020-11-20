<?php
    //ZONA HORARIA MX
    date_default_timezone_set('America/Mexico_City');
    header('Content-type: application/json; charset=utf-8');

	//VALIDAR SESION
    include 'header.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES
    $cupon    = $_POST['cupon'];
    $cuponera = $_POST['cuponera'];
    $empresa  = $user_data['empresa'];
    $fecha    = date('Y-m-d');

    //VALIDA EL TAMAÑO DE LA IMAGEN CARGADA
    if (empty($_POST) || empty($cupon) || empty($cuponera)) {
            
        //ARREGLO DE LA RESPUESTA
        $response = [
            "res" => "EMPTY_POST"
        ];
       
        //ENVIA LA RESPUESTA
        echo json_encode($response);

    }else{

        //OBTENER DETALLES DEL CUPÓN
    	$mis = "SELECT * FROM vw_cupones WHERE SHA1(codigo_cupon) = '$cupon' AND SHA1(id_cuponera) = '$cuponera' AND status_cupon = 'A' AND disponibles > 0 AND id_empresa = $empresa AND caducidad >= '$fecha'";
        $db->query($mis);
        $result = $db->fetch();
    	
        //VALIDA SI HUBO ERRORES
    	if(!$db->error()){

            //VALIDA EL RESULTADO DE LA CONSULTA
            if(!is_null($result)){

                //ARREGLO DE LA RESPUESTA
                $response = [
                    "cupon_data" => $result,
                    "res" => "SUCCESS"
                ];
            
            }else{

                //ARREGLO DE LA RESPUESTA
                $response = [
                    "res" => "EXPIRED"
                ];
            
            }
    	   
            //ENVIA LA RESPUESTA
    		echo json_encode($response);
    	
    	}else{
    	
    		echo "FAILED";
    	}
    }