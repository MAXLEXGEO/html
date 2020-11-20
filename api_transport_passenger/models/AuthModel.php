<?php

/**
* modelo del controlador de authenticacion
*/

//include de la libreria para envio de sms
require_once('sms/httpPHPAltiria.php');

class AuthModel{

	//consultar contraseña del usuario - pasajero
	function getPass($email){
		
		global $db;		
		
		$query = $db->select('password')
					->table('transport_user')
					->where('email',$email)
					->where('id_type_id',1)
					->where('status','Active')
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $query;
	
	}

	//consultar datos de usuario - pasajero
	function getUser($email){
		
		global $db;		
		
		$query = $db->select('
						initcap(first_name) AS first_name,
						initcap(last_name) AS last_name,
						email,
						concat(\'https://tso.maxlexgeo.com/media/\',photo) AS photo,
						phone')
					->table('transport_user')
					->where('email',$email)
					->where('id_type_id',1)
					->where('status','Active')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $query;
	
	}

	//consultar calificacion del usuario
	function getRate($email,$dbPass){

		global $db;

		$query = $db->select('ROUND(AVG(r.rate_client), 2) AS user_rate')
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.id_travel_id')
					->join('transport_user AS u', 'u.id', 't.id_client_id')
					->where('t.status','F')
					->where('u.email',$email)
					->where('u.password',$dbPass)
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $query;
	
	}

	//verificar passwords mediante la encriptacion django - python
	function password_verify($dbPass,$password){

		$pieces = explode("$", $dbPass);

		$iterations = $pieces[1];
	    $salt 		= $pieces[2];
	    $old_hash 	= $pieces[3];

	    $hash = hash_pbkdf2("SHA256", $password, $salt, $iterations, 0, true);
	    $hash = base64_encode($hash);

	    if ($hash == $old_hash) {
	    
	       return true;

	    }else {
	    
	       return false;
	    
	    }

	}

	//generar token de sesion - header Authorization
	function generateToken($email,$dbPass){
		
		global $db;
		
		//token inicial - momento del inicio de sesion y el email del usuario
		$token = date('Y-m-dH:i:s').'&'.$email;

		//encriptar el token usando el metodo de django - python
		$algorithm  = "pbkdf2_sha256";
	    $iterations = 10000;

	    $newSalt = mcrypt_create_iv(6, MCRYPT_DEV_URANDOM);
	    $newSalt = base64_encode($newSalt);
	    $hash 	 = hash_pbkdf2("SHA256", $token, $newSalt, $iterations, 0, true);    
	    
	    //token ya encriptado
	    $api_token = $algorithm ."$". $iterations ."$". $newSalt ."$". base64_encode($hash);

	    //update api token
		$data = ['user_token' => $api_token, 'online' => 'T'];

		$query = $db->table('transport_user')
				->where('email', $email)
				->where('password', $dbPass)
				->update($data);

		return $api_token;
	
	}

	//actualizar el token de google - maps tracker
	function update_token_push($email,$google_id){
		
		global $db;

		$data = ['google_token' => $google_id];

		return $query = $db->table('transport_user')
				->where('email', $email)
				->update($data);
	
	}

	//verificar token en la tabla
	function checkToken($apiToken){		
		
		global $db;
		
		$query = $db->select('user_token')
				->table('transport_user')
				->where('user_token',$apiToken)
				->get();
		
		return ($db->numRows() > 0) ? $query : [];
	
	}

	//borrar tokens de usuario - cierre de sesion
	function delete_token($email,$phone){
		
		global $db;
		$data = ['user_token' => 'NULL', 'google_token' => 'NULL', 'online' => 'F'];

		return $query = $db->table('transport_user')
				->where('email', $email)
				->where('phone',$phone)
				->update($data);
	}

	//generar y enviar el pin de verificacion de telefono
	function send_pin($userData){

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
				->where('email', $userData->email)
				->where('phone',$userData->phone)
				->update($login_code_data);
		
		//}

	}

}