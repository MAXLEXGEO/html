<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador del pasajero
*/

class PassengerModel
{
	//perfil del pasajero
	function passenger_profile($params){

		global $db;

		//fecha actual
		$today = date('Y-m-d');

		//consultar pefil
		$query = $db->select("
						first_name,
						last_name,
						email,
						phone,
						concat('https://tso.maxlexgeo.com/media/',photo) AS photo,
						(TRUNC(months_between(date(created), '$today')/12::numeric,1)) AS length_years")
					->table('transport_user')
					->where('md5(id::varchar)',$params['passenger'])
					->where('status','Active')
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $query;

	}

	//consultar calificacion del pasajero
	function passenger_rate($params){

		global $db;

		//consultar calificacion
		$query = $db->select('ROUND(AVG(r.rate_client), 2) AS passenger_rate')
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->join('transport_user AS u', 'u.id', 't.id_client_id')
					->where('t.status','F')
					->where('md5(u.id::varchar)',$params['passenger'])
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'passenger rate not found', 400);

		}
		
		return $query;
	
	}

	//consultar comentarios del pasajero
	function passenger_comments($params){

		global $db;

		//fecha actual
		$today = date('Y-m-d');

		//consultar comentarios
		$query = $db->select("
						r.comments_client AS comments,
						age(timestamp '$today', date(t.date_end)) AS how_long")
					->table('transport_rates AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->join('transport_user AS u', 'u.id', 't.id_client_id')
					->where('t.status','F')
					->where('md5(u.id::varchar)',$params['passenger'])
					->orderBy('t.date_end', 'DESC')
					->getAll();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'passenger comments not found', 400);

		}
		
		return $query;
	
	}

	//actualizar calificacion del pasajero
	function update_rate($request){

		global $db;

		$data = ['rate_client' => $request->rate];

		return $query = $db->table('transport_rates')
				->where('md5(id_travel_id::varchar)',$request->travel)
				->update($data);
	
	}

}