<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador de usuario
*/

class UserModel
{
	//registro de usuario
	function user_register($request){
		
		global $db;
		
		//generar password encriptada - django
		$password = $request->password;
		$passCode = UserModel::password_encript($password);

		//verificar si existe el email
		$email = $request->email;

		if(UserModel::email_check($email)){

			//registro del usuario en la tabla
			$user_insert_data = [
				'password' 	 => $passCode,
				'first_name' => $request->first_name,
				'last_name'  => $request->last_name,
				'email' 	 => $request->email,
				'phone' 	 => $request->phone,
				'id_type_id' => 1,
				'status' 	 => 'Active',
				'gender' 	 => $request->gender,
				'created'    => date('Y-m-d H:i:s')];
			
			return $db->table('transport_user')->insert($user_insert_data);
		
		}else{

			throw new ExceptionApi('error', 'email already exists', 400);

		}

	}

	//actualizar datos de usuario - pasajero
	function user_update($request){

		global $db;

		$data = ['first_name' => $request->first_name, 'last_name' => $request->last_name];

		return $query = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone', $request->phone)
				->update($data);
	
	}

	/**
	* funciones privadas del modelo
	*/

	//encriptar password - django
	private function password_encript($password){
		
		$algorithm  = "pbkdf2_sha256";
	    $iterations = 10000;

	    $newSalt = mcrypt_create_iv(6, MCRYPT_DEV_URANDOM);
	    $newSalt = base64_encode($newSalt);

	    $hash 	 = hash_pbkdf2("SHA256", $password, $newSalt, $iterations, 0, true);    
	    $toDBStr = $algorithm ."$". $iterations ."$". $newSalt ."$". base64_encode($hash);

	    return $toDBStr;

	}

	//verificar existencia del email
	private function email_check($email){
		
		global $db;
		$query = $db->select('email')
					->table('transport_user')
					->where('email',$email)
					->where('id_type_id',1)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

}