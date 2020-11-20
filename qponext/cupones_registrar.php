<?php
	//INVOCAR EL ARCHIVO DE CONEXION
    include 'assets/scripts/php/connection.php';
    include 'assets/extra-libs/phpqrcode/qrlib.php';
    $db = new mysqlDB;

	//RECIBE LOS VALORES DEL FORMULARIO
    $cuponera 		= $_POST['cuponera_id'];
    $titulo 		= $_POST['titulo'];
    $codigo  		= $_POST['codigo_cupon'];
    $descripcion 	= $_POST['descripcion'];
    $tipo_descuento = $_POST['tipo_descuento'];
    $descuento 		= $_POST['descuento'];
    $disponibles 	= $_POST['disponibles'];
    $restricciones  = $_POST['restricciones'];

    //VALIDA SI HAY RESTRICCIONES CAPTURADA
    if(empty($restricciones)){$restricciones = '';}

    //GENERA EL CODIGO QR DEL CUPON
    $cupon_qr = 'assets/images/cupones_qr/'.$cuponera.'_'.$codigo.'.png';
    
    //GUARDA EL CODIGO QR
    QRcode::png('cuponera='.sha1($cuponera).'&cupon='.sha1($codigo), $cupon_qr, 'M', '3', 2);

	//REGISTRO DE DATOS
	$sql = "INSERT INTO cupon (id_cuponera,titulo,codigo,descripcion,descuento,tipo_descuento,disponibles,restricciones,qr,img) VALUES ($cuponera,'$titulo','$codigo','$descripcion','$descuento','$tipo_descuento',$disponibles,'$restricciones','$cupon_qr','default.jpg')";
	$db->query($sql);

	//VALIDA EL REGISTRO PARA LA RESPUESTA
	if(!$db->error()){
	
		echo "SUCCESS";
	
	}else{
	
		echo "FAILED";
	}