<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador del conductor
*/

class DriverModel
{
	//registro del conductor
	function driver_register($request){
				
		global $db;
		
		//generar password encriptada - django
		$password = $request->password;
		$passCode = DriverModel::password_encript($password);

		//verificar si existe el email
		$email = $request->email;

		if(DriverModel::email_check($email)){			

			//id del conductor nuevo
			$driver = DriverModel::driver_id_register($request,$passCode);

			//verificar si la placa del taxi existe ya
			if(DriverModel::taxi_plate_check($request)){

				//no existe el taxi registrado , se registra y se envia mail
				//registrar taxi
				$taxi 	= DriverModel::taxi_id_register($driver,$request);

				return true;
				

			}else{

				//codigo de que se registro el taxista y que su taxi ya se encuentra registrado
				return true;

			}
	
		}else{

			throw new ExceptionApi('error', 'email already exists', 400);

		}

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
					->where('id_type_id',2)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

	//registrar al conductor y obtener el ID
	private function driver_id_register($request,$passCode){
		
		global $db;
		
		//registro del usuario en la tabla
		$user_insert_data = [
			'password' 	 => $passCode,
			'first_name' => $request->first_name,
			'last_name'  => $request->last_name,
			'email' 	 => $request->email,
			'phone' 	 => $request->phone,
			'id_type_id' => 2,
			'status' 	 => 'Inactive',
			'gender' 	 => $request->gender,
			'car_plate_app' => $request->car_plate,
			'created'    => date('Y-m-d H:i:s')];
		
		$db->table('transport_user')->insert($user_insert_data);

		//id del conductor nuevo
		return $db->insertId();

	}

	//verificar existencia del taxi
	private function taxi_plate_check($request){
		
		global $db;
		$query = $db->select('id')
					->table('transport_detail_car')
					->where('plate',$request->car_plate)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

	//registrar al taxi  y obtener el ID
	private function taxi_id_register($driver,$request){
		
		global $db;
		
		//registro del taxi en la tabla
		$taxi_insert_data = [
			'brand' 	   => 'app register',
			'model' 	   => 'app register',
			'year_car'     => date('Y'),
			'plate' 	   => $request->car_plate,
			'status' 	   => 'Inactive',
			'id_driver_id' => $driver,
			'color' 	   => 'app register',
			'submited' 	   => date('Y-m-d H:i:s'),
			'e_number'     => 'app register'];

		$db->table('transport_detail_car')->insert($taxi_insert_data);

		//id del conductor nuevo
		return $db->insertId();

	}

}