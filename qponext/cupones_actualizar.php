<?php
    //ZONA HORARIA MX
    date_default_timezone_set('America/Mexico_City');
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    include 'assets/extra-libs/phpqrcode/qrlib.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $cupon          = $_POST['cupon_id'];
    $cuponera 		= $_POST['cuponera_id'];
    $titulo 		= $_POST['titulo'];
    $codigo  		= $_POST['codigo_cupon'];
    $descripcion 	= $_POST['descripcion'];
    $tipo_descuento = $_POST['tipo_descuento'];
    $descuento 		= $_POST['descuento'];
    $disponibles 	= $_POST['disponibles'];
    $restricciones  = $_POST['restricciones'];
    $cupon_qr_form  = $_POST['cupon_qr'];

    //VALIDA EL TAMAÑO DE LA IMAGEN CARGADA
    if ($_FILES['img_cupon']['size'] > 1000000) {
            
            echo "LIMIT"; die();

    }else{

        //VALIDA SI HAY RESTRICCIONES CAPTURADAS
        if(empty($restricciones)){ $restricciones = ''; }

        //BORRA EL ANTIGUO QR DEL CUPON
        unlink($cupon_qr_form);

        //GENERA EL NUEVO CODIGO QR DEL CUPON
        $cupon_qr = 'assets/images/cupones_qr/'.$cuponera.'_'.$codigo.'.png';
        
        //GUARDA EL CODIGO QR
        QRcode::png('cuponera='.sha1($cuponera).'&cupon='.sha1($codigo), $cupon_qr, 'M', '3', 2);

        //GUARDA LA IMAGEN DEL CUPON - HEADER
        if ($_FILES['img_cupon']['size'] > 1) {
            
            $file_img      = basename($_FILES['img_cupon']['name']);
            $file_type_img = pathinfo($file_img,PATHINFO_EXTENSION);
            $file_img      = $cupon.'_'.$codigo.'.'.$file_type_img;
            $ruta          = 'assets/images/cupones/'.$file_img;
            copy($_FILES['img_cupon']['tmp_name'],$ruta);

        }else{
            //EN CASO DE NO HABER CARGADO UNA IMAGEN SE MANTIENE LA DEFAULT
            $file_img = 'default.jpg';
        }

    	//ACTUALIZACION DE DATOS
    	$sql = "UPDATE cupon
                SET
                    id_cuponera    = $cuponera,
                    titulo         = '$titulo',
                    codigo         = '$codigo' ,
                    descripcion    = '$descripcion' ,
                    descuento      = '$descuento',
                    tipo_descuento = '$tipo_descuento',
                    disponibles    = $disponibles,
                    restricciones  = '$restricciones',
                    qr             = '$cupon_qr',
                    img            = '$file_img'
                WHERE
                    id_cupon = $cupon";
        
        $db->query($sql);

    	//VALIDA EL REGISTRO PARA LA RESPUESTA
    	if(!$db->error()){
    	
    		echo "SUCCESS";
    	
    	}else{
    	
    		echo "FAILED";
    	}
    }