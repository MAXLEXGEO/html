<?php

/**
* controlador de verificacion del pin de inicio de sesion
*/

//require del modelo
require 'models/PinModel.php';

class Pin{
	
	//recibe por post los datos para verificar el pin
	public static function post($request){
		
		if ($request == 'check_pin') {
			
			return self::check_pin();
		
		} else {
		
			throw new ExceptionApi('error processing request', 'malformed url', 400);
		
		}
	
	}

	//recibe por post los datos para generar un nuevo código
	public static function get($request){
		
		if ($request == 'new_pin') {
			
			return self::new_pin();
		
		} else {
		
			throw new ExceptionApi('error processing request', 'malformed url', 400);
		
		}
	
	}

	//verificar el pin de verificación
	private static function check_pin(){
		
		//arreglo de respuesta y cuerpo de la peticion
		$response = [];
		$request  = json_decode(file_get_contents('php://input'));

		//verifica el cuerpo de la peticion
		if (is_null($request)) {
		
			throw new ExceptionApi('error', 'invalid request body', 400);
		
		}else{

			//instanciar el modelo
			$pinModel = new PinModel();

			//verificar el pin
			if ($pinModel -> check_pin($request)) {

				//consultar calificación del conductor
				$driverRate = $pinModel -> getRate($request);

				//generar token encriptado
				$generateToken = $pinModel -> generateToken($request);

				//array de respuesta
				$response   = array(
				   'api_token' 	 => $generateToken,
				   'driver_rate' => $driverRate->driver_rate
				);

				//envia la respuesta si el login ha sido con exito
				return ['status' => 'success', 'data' => $response];

			}else{

				throw new ExceptionApi('error', 'invalid pin', 400);

			}
		
		}

	}

	//generar y reenviar un nuevo pin
	private static function new_pin(){
		
		//arreglo de respuesta y cuerpo de la peticion
		$response = [];
		$request  = json_decode(file_get_contents('php://input'));

		//verifica el cuerpo de la peticion
		if (is_null($request)) {
		
			throw new ExceptionApi('error', 'invalid request body', 400);
		
		}else{

			//instanciar el modelo
			$pinModel = new PinModel();

			//envio del nuevo pin de verificacion
			$sms_send_pin = $pinModel -> send_new_pin($request);

			if($sms_send_pin){

				//envia la respuesta si el login ha sido con exito
				return ['status' => 'success', 'data' => 'pin generate successful'];

			}else{

				throw new ExceptionApi('error', 'error in generating pin', 400);

			}

		}

	}
}