<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador de las facturas
*/

class StampModel
{

	//estimacion del costo del viaje
	function stamp_invoice_profile($request){

		global $db;

		//obtener el ID del pasajero
		$user = StampModel::getPassenger($request);

		//obtener perfil de facturacion
		$profile = $db->select('
							md5(i.id::varchar) AS invoice_profile,
							i.tipo_facturacion')
					->table('transport_user_invoice_data AS i')
					->join('transport_user AS u', 'u.id', 'i.user_id')
					->where('i.forma_pago', $request->payment_form)
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'taxes not found', 400);

		}
		
		return $profile;

	}

	//verificar que esta en tiempo para timbrar factura
	function travel_check_time($request){

		global $db;

		$today = date('Y-m-d H:i:s');

		$lengh_travel = $db->select("DATE_PART('day', '$today' - date_end) AS lengh_travel")
					->table('transport_travel')
					->where('md5(id::varchar)', $request->travel)
					->get();

		if ($lengh_travel->lengh_travel > 0) {
			
			throw new ExceptionApi('error', 'time to stamp invoice has expired', 400);

		}else{

			return true;

		}

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar las cuotas e impuestos internos
	private function getPassenger($request){

		global $db;

		
		$user = $db->select('u.id AS user')
					->table('transport_user AS u')
					->join('transport_travel AS t', 't.id_client_id', 'u.id')
					->where('md5(t.id::varchar)', $request->travel)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'taxes not found', 400);

		}
		
		return $user;
	
	}

}