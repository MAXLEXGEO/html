<?php

/**
* modelo del controlador de verificacion del pin de inicio de sesion
*/

//include de la libreria para envio de sms
require_once('sms/httpPHPAltiria.php');

class PinModel{

	//verificar el pin de inicio de sesión
	function check_pin($request){

		global $db;		
		
		$query = $db->select('phone_pin')
					->table('transport_user')
					->where('email',$request->email)
					->where('phone',$request->phone)
					->where('phone_pin',$request->code)
					->where('id_type_id',1)
					->where('status','Active')
					->get();
		
		if (!$db->numRows() > 0) {
			
			return false;
		
		}else{

			return true;

		}
	
	}

	//consultar calificacion del usuario
	function getRate($request){

		global $db;

		$query = $db->select('ROUND(AVG(r.rate_client), 2) AS user_rate')
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->join('transport_user AS u', 'u.id', 't.id_client_id')
					->where('t.status','F')
					->where('u.email',$request->email)
					->where('u.phone',$request->phone)
					->get();

		if (is_null($query->user_rate)) {
			
        	return $query = array('user_rate' => '0.0');

		}else{

			return $query;
		}
	
	}

	//generar token de sesion - header Authorization
	function generateToken($request){
		
		global $db;
		
		//token inicial - momento del inicio de sesion y el email del usuario
		$token = date('Y-m-dH:i:s').'&'.$request->email;

		//encriptar el token usando el metodo de django - python
		$algorithm  = "pbkdf2_sha256";
	    $iterations = 10000;

	    $newSalt = mcrypt_create_iv(6, MCRYPT_DEV_URANDOM);
	    $newSalt = base64_encode($newSalt);
	    $hash 	 = hash_pbkdf2("SHA256", $token, $newSalt, $iterations, 0, true);    
	    
	    //token ya encriptado
	    $api_token = $algorithm ."$". $iterations ."$". $newSalt ."$". base64_encode($hash);

	    //update api token
		$data_token = ['user_token' => $api_token, 'online' => 'T'];
		$query_token = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone', $request->phone)
				->update($data_token);

		//borrar pin de verificación
		$pin_data = ['phone_pin' => 'NULL'];
		$query_pin = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone', $request->phone)
				->update($pin_data);

		return $api_token;
	
	}

	//generar y enviar el nuevo pin de verificacion
	function send_new_pin($request){

		global $db;

		//generar codigo de seguridad para el viaje
		//$login_code = substr(str_shuffle("0123456789AEGLMOX"), 0, 5);
		$login_code = '12345';

		//instanciar la libreria
		//$altiriaSMS = new AltiriaSMS();
		//$altiriaSMS->setUrl("http://www.altiria.net/api/http");
		//datos de la cuenta
		//$altiriaSMS->setDomainId('test');
		//$altiriaSMS->setLogin('mirsha.rojas92@gmail.com');
		//$altiriaSMS->setPassword('7fca9npb');

		//$altiriaSMS->setDebug(true);

		//telefono del usuario
		//$sDestination = '+52'.$userData->phone;
		
		//No es posible utilizar el remitente en América pero sí en España y Europa
		//$response = $altiriaSMS->sendSMS($sDestination, "PIN DE INICIO DE SESION: $login_code");
		//Utilizar esta llamada solo si se cuenta con un remitente autorizado por Altiria
		//$response = $altiriaSMS->sendSMS($sDestination, "Mensaje de prueba", "remitente");

		//if (!$response){
		
		//  return false;
		
		//}else{

			$login_code_data = ['phone_pin' => $login_code];

			return $query = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone',$request->phone)
				->update($login_code_data);
		
		//}

	}

}