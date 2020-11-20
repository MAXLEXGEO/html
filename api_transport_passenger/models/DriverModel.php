<?php

/**
* modelo del controlador del conductor
*/

date_default_timezone_set('America/Mexico_City');

class DriverModel
{

	//perfil del conductor
	function driver_profile($params){
		
		global $db;

		//fecha actual
		$today = date('Y-m-d');

		//consultar pefil
		$query = $db->select("
						u.first_name,
						u.last_name,
						u.email,
						u.phone,
						u.google_token,
						(TRUNC(months_between(date(u.created), '$today')/12::numeric,1)) AS length_years,
						CONCAT(c.brand,' ',c.model) AS car,
						concat('https://tso.maxlexgeo.com/media/',u.photo) AS photo,
						c.plate AS car_plate")
					->table('transport_user AS u')
					->join('transport_driver_group AS g', 'g.driver_id', 'u.id')
					->join('transport_detail_car AS c', 'c.id', 'g.id_car_id')
					->where('md5(u.id::varchar)',$params['driver'])
					->where('u.status','Active')
					->where('c.status','Active')
					->where('u.online','T')
					->get();

					//echo "<pre>"; var_dump($db->getQuery()); die();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $query;

	}

	//consultar calificacion del conductor
	function driver_rate($params){

		global $db;

		//consultar calificacion
		$query = $db->select('ROUND(AVG(r.rate_driver), 2) AS driver_rate')
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->join('transport_user AS u', 'u.id', 't.id_driver_id')
					->where('t.status','F')
					->where('md5(u.id::varchar)',$params['driver'])
					->get();

		if (is_null($query->driver_rate)) {
			
        	return $query = array('driver_rate' => '0.0');

		}else{

			return $query;
		}

	}

	//consultar comentarios del conductor
	function driver_comments($params){

		global $db;

		//fecha actual
		$today = date('Y-m-d');

		//consultar comentarios
		$query = $db->select("
						r.comments_driver AS comments,
						age(timestamp '$today', date(t.date_end)) AS how_long")
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->join('transport_user AS u', 'u.id', 't.id_driver_id')
					->where('t.status','F')
					->where('md5(u.id::varchar)',$params['driver'])
					->orderBy('t.date_end', 'DESC')
					->getAll();

		/*if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver comments not found', 400);

		}*/
		
		return $query;
	
	}

	//actualizar calificacion del conductor
	function update_rate($request){

		global $db;

		$data = ['rate_driver' => $request->rate];

		return $query = $db->table('transport_rates')
				->where('md5(id_travel_id::varchar)',$request->travel)
				->update($data);
	
	}

}