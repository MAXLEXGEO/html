<?php

/**
* controlador de authenticacion
*/

//require del modelo
require 'models/AuthModel.php';

class Auth{
	
	//recibe por post los datos para el login
	public static function post($request){
		
		if ($request == 'login') {
			
			return self::login();
		
		} else {
		
			throw new ExceptionApi('error processing request', 'malformed url', 400);
		
		}
	
	}

	//recibe los datos para el cierre de sesión
	public static function delete($request){
		
		if ($request == 'logout') {

			//covertir parametros
            $params = [];
            parse_str($_SERVER['QUERY_STRING'], $params);
		
			return self::logout($params);
		
		} else {
		
			throw new ExceptionApi('error processing request', 'malformed url', 400);
		
		}
	
	}

	//recibe los datos para resgitrar el token de firebase - google
	public static function put($request){
		
		if ($request == 'token_google') {
		
			if (Auth::authorize()) {
		
				return self::token_google();
        
            }
		
		} else {
		
			throw new ExceptionApi('error processing request', 'malformed url', 400);
		
		}
	
	}

	//inicio de sesion
	private static function login(){
		
		//arreglo de respuesta y cuerpo de la peticion
		$response = [];
		$request  = json_decode(file_get_contents('php://input'));

		//verifica el cuerpo de la peticion
		if (is_null($request)) {
		
			throw new ExceptionApi('error', 'invalid request body', 400);
		
		}else{

			//datos recibidos
			$email    = $request->email;
			$password = $request->password;

			//instanciar el modelo
			$authModel = new AuthModel();
			
			//consultar datos del conductor
			$driverPass = $authModel -> getPass($email);
			$driverData = $authModel -> getDriver($email);

			//verificacion del password
			$dbPass	  	 = $driverPass->password;
			$pass_verify = $authModel -> password_verify($dbPass,$password);

			if ($pass_verify == true) {

				//envio de pin de cerificacion de télefono
				$sms_send_pin = $authModel -> send_pin($driverData);

				//consultar calificación del conductor
				//$driverRate = $authModel -> getRate($email,$dbPass);
				
				//generar token encriptado
				//$generateToken = $authModel -> generateToken($email, $dbPass);

				//array de respuesta
				$response   = array(
					   //'api_token' => $generateToken,
					   'driver_data' => $driverData,
					   //'driver_rate' => $driverRate
				);

				//envia la respuesta si el login ha sido con exito
				return ['status' => 'success', 'data' => $response];
			
			}else{
				
				throw new ExceptionApi('error', 'email or password invalid', 400);
			
			}

		}

	}

	//funcion para actualizar el token_push
	private static function token_google(){
		
		//arreglo de respuesta y cuerpo de la peticion
		$response = [];
		$request = json_decode(file_get_contents('php://input'));

		//verifica el cuerpo de la peticion
		if (is_null($request)) {
		
			throw new ExceptionApi('error', 'invalid request body', 400);
		
		}else{

			//datos recibidos
			$email 	   = $request->email;
			$google_id = $request->google_id;

			//instanciar el modelo
			$authModel = new AuthModel();

			//actualiza el token de google en la tabla
			$updateTokenGoogle = $authModel -> update_token_push($email,$google_id);

			//verifica el registro del token de google
			if ($updateTokenGoogle) {
				
				//array de respuesta
				$response = array(
					'driver' => 'google_id registered'
				);

				//envia la respuesta si el token se registro con exito
				return ['status' => 'success', 'data' => $response];

			}else{
				
				throw new ExceptionApi('error', 'error in google_id register', 400);
			
			}

		}

	}  

	//funcion del cierre de sesion
	private static function logout($params){
		
		$response = [];
		$request  = json_decode(file_get_contents('php://input'));

		//verifica el cuerpo de la peticion
		if (is_null($params)) {
		
			throw new ExceptionApi('error', 'invalid request body', 400);
		
		}else{
			//datos recibidos
			$email = $params['email'];
			$phone = $params['phone'];

			//instancia de authmodel para realizar las peticiones
			$authModel = new AuthModel();

			//se borran los datos de sesion - logout
			$deleteToken = $authModel -> delete_token($email,$phone);

			//verifica que se hayan borrado los datos de sesion
			if ($deleteToken) {
			
				//array de respuesta
				$response = array(
					'driver' => 'session logout successful'
				);

				//envia la respuesta si se cerro correctamente la sesion
				return ['status' => 'success', 'data' => $response];

			}else{
				
				throw new ExceptionApi('error', 'error logging out', 400);
			
			}
		
		}
	
	}  

	//funcion que verifica el token en cada peticion
	public static function authorize(){
		
		//cacha los headers en la peticion REST
		$headers = apache_request_headers();

		//verifica que tengan datos
		if (isset($headers["Authorization"])) {

			//asignacion del token a variable para verificar
			$apiToken = $headers["Authorization"];

			//instancia de authmodel para realizar las peticiones
			$authModel = new AuthModel();

			//verificar el token en la tabla
			if (!empty($authModel -> checkToken($apiToken))) {
			
				return true;
			
			}else{
			
				throw new ExceptionApi('error','invalid or expired token','401');
			
			}
		
		} else {
			
			throw new ExceptionApi('error','authorization header not found','401');
			//throw new ExceptionApi(['status' => 'error' , 'token'=>'invalid token'],'authorization token not found');
		}

	}
	
}