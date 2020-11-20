<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador de password
*/

class PasswordModel
{
	//verificar contraseña
	function check_pass($request){

		global $db;
		
		//obtener password del usuario
		$dbPass = PasswordModel::getPass($request);

		//verificar contraseña ingresada con la obtenida
		$pieces = explode("$", $dbPass->password);

		$iterations = $pieces[1];
	    $salt 		= $pieces[2];
	    $old_hash 	= $pieces[3];

	    $hash = hash_pbkdf2("SHA256", $request->password, $salt, $iterations, 0, true);
	    $hash = base64_encode($hash);
			
	    if ($hash == $old_hash) {
	    
	       return true;

	    }else {
	    
	       throw new ExceptionApi('error', 'invalid password', 400);
	    
	    }

	}

	//actualizar contraseña
	function password_update($request){

		global $db;

		//generar password encriptada - django
		$password = $request->new_password;
		$passCode = PasswordModel::password_encrypt($password);

		$data = ['password' => $passCode];

		$query = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone', $request->phone)
				->update($data);

		if($query){

			return $passCode;

		}else{

			throw new ExceptionApi('error', 'error updating password', 400);

		}
	
	}

	/**
	* funciones privadas del modelo
	*/

	//consultar password del usuario
	private function getPass($request){

		global $db;		
		
		$query = $db->select('password')
					->table('transport_user')
					->where('email',$request->email)
					->where('id_type_id',1)
					->where('phone',$request->phone)
					->where('status','A')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $query;
	
	}

	//encriptar password - django
	private function password_encrypt($password){
		
		$algorithm  = "pbkdf2_sha256";
	    $iterations = 10000;

	    $newSalt = mcrypt_create_iv(6, MCRYPT_DEV_URANDOM);
	    $newSalt = base64_encode($newSalt);

	    $hash 	 = hash_pbkdf2("SHA256", $password, $newSalt, $iterations, 0, true);    
	    $toDBStr = $algorithm ."$". $iterations ."$". $newSalt ."$". base64_encode($hash);

	    return $toDBStr;

	}

}