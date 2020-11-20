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
		$driver = TravelsModel::getDriverID($email,$phone)[0];

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
						t.duration AS duration_minutes,
						t.status,
						r.rate_client AS passenger_rate")
		->table('transport_travel AS t')
		->join('transport_user AS d', 'd.id', 't.id_driver_id')
		->join('transport_rates AS r', 'r.travel_id', 't.id')
		->where('d.id',$driver->driver)
		->where('t.status', 'F')
		->orwhere('t.status', 'C')
		->orderBy('t.id', 'DESC')
		->getAll();

		return ($db->numRows() > 0) ? $travels : [];

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del conductor
	private function getDriverID($email,$phone){

		global $db;

		$driver = $db->query("
						SELECT id AS driver
						FROM transport_user
						WHERE email = '$email'
						AND phone = '$phone' 
						AND status = 'Active'
						AND (id_type_id = 2 OR id_type_id = 3)");	
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $driver;
	
	}

}