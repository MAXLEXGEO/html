<?php

/**
* modelo del controlador de viajes (realizados,cancelados)
*/

class TravelsModel
{

	//lista de viajes del usuario
	function travels_list($params){
        
		global $db;

		//extraer variables
		$email = $params['email'];
		$phone = $params['phone'];

		//obtener ID del usuario
		$user = TravelsModel::getUser($email,$phone);

		//consultar viajes
		$travels = $db->select("
						md5(t.id::varchar) AS travel,
						t.start_lat,
						t.start_long,
						t.start_address,
						t.end_lat,
						t.end_long,
						t.end_address,
						DATE(t.date_init) AS date_init,
						to_char(t.date_init::Time, 'HH12:MI:SS AM') AS time_init,
						concat(t.distance,' Km') AS distance,
						t.status,
						concat(c.brand,' ',c.model) AS driver_car")
		->table('transport_travel AS t')
		->join('transport_user AS u', 'u.id', 't.id_client_id')
		->join('transport_user AS d', 'd.id', 't.id_driver_id')
		->join('transport_driver_group AS g', 'g.driver_id', 'd.id')
		->join('transport_detail_car AS c', 'c.id', 'g.id_car_id')
		->where('u.id',$user->user)
		->in('t.status', ['F','C'])
		//->where('t.status', 'F')
		//->orwhere('t.status', 'C')
		->orderBy('t.id', 'DESC')
		->getAll();

		return ($db->numRows() > 0) ? $travels : [];

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del usuario
	private function getUser($email,$phone){

		global $db;		
		
		$user = $db->select('id AS user')
					->table('transport_user')
					->where('email',$email)
					->where('id_type_id',1)
					->where('phone',$phone)
					->where('status','Active')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $user;
	
	}

}