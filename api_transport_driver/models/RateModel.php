<?php

/**
* modelo del controlador de calificacion del viaje
*/

class RateModel
{

	//cambiar el status - En Servicio
	function rate_passenger($request){

		global $db;

		//datos para registrar las calificaciones
		$rate_data = ['rate_client' => $request->passenger_rate, 'comments_client' => $request->passenger_comments];

		$query = $db->table('transport_rates')
				->where('md5(travel_id::varchar)',$request->travel)
				->update($rate_data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error change ondrive status', 400);

		}
	
	}

}