<?php

/**
* modelo del controlador de calificacion del viaje
*/

class RateModel
{

	//calificacion del viaje - chofer
	function rate_driver($request){

		global $db;

		//datos para registrar las calificaciones
		$rate_data = ['rate_driver' => $request->driver_rate, 'comments_driver' => $request->driver_comments];

		$query = $db->table('transport_rates')
				->where('md5(travel_id::varchar)',$request->travel)
				->update($rate_data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error register rates', 400);

		}
	
	}

}