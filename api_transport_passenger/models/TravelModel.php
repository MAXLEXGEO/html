<?php
date_default_timezone_set('America/Mexico_City');
/**
* modelo del controlador del viaje
*/

class TravelModel
{

	//registro del viaje
	function travel_register($request){
		
		global $db;

		//obtener ID del usuario
		$user = TravelModel::getUser($request);

		//obtener ID del conductor
		$driver = TravelModel::getDriver($request);

		//obtener ID de la region
		$region = TravelModel::getRegion($request);

		//costos de la region
		$region_costs = TravelModel::getRegionCosts($request);

		//costos
		$cost_km  = $region_costs->cost_km;
		$cost_min = $region_costs->cost_min;

		//Subtotal - tarifa del viaje
		$subtotal = ( ($request->travel_distance * $cost_km) + ($request->travel_duration * $cost_min) );

		//fecha y hora - pendiente en caso de que se mande desde la APP
		$date_init = date('Y-m-d H:i:s');

		//generar codigo de seguridad para el viaje
		$travel_code = substr(str_shuffle("0123456789ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 5);

		//registro del viaje en la tabla - SOLICITUD DE VIAJE
		$travel_insert_data = [
			'start_lat' 	 	=> $request->start_lat,
			'start_long' 		=> $request->start_long,
			'suggested_lat'  	=> $request->suggested_lat,
			'suggested_long' 	=> $request->suggested_long,
			'start_address' 	=> $request->start_address,
			'suggested_address' => $request->suggested_address,
			'date_init' 	 	=> $date_init,
			'distance'    		=> $request->travel_distance,
			'duration' 			=> $request->travel_duration,
			'id_client_id' 		=> $user->user,
			'id_driver_id' 		=> $driver->driver,
			'cost' 				=> $subtotal,
			'region_id' 		=> $region->region,
			'status' 			=> 'S',
			'code' 				=> $travel_code
		];

		return $db->table('transport_travel')->insert($travel_insert_data);

	}

	//registro del viaje
	function travel_cancel($request){
		
		global $db;

		//obtener ID del usuario
		$user = TravelModel::getUser($request);

		//fecha y hora - pendiente en caso de que se mande desde la APP
		$date_cancel = date('Y-m-d H:i:s');

		//verificar el viaje
		if(TravelModel::check_pending_travel($request)){

			$get_travel_details = TravelModel::get_pending_details($request);

			//datos para cancelar el viaje - EN STATUS "P" ESPERANDO AL CONDUCTOR
			$travel_cancel_data = [
				'status'   	  => 'C',
				'distance' 	  => '0',
				'cost'	   	  => '0.00',
				'end_address' => $get_travel_details->start_address,
				'end_lat' 	  => $get_travel_details->start_lat,
				'end_long' 	  => $get_travel_details->start_long,
				'date_end' 	  => $date_cancel
			];

			$query = $db->table('transport_travel')
				->where('md5(id::varchar)',$request->travel)
				->update($travel_cancel_data);

			//cancelar el recibo del viaje
			$travel_receipt_cancel_data = [
				'subtotal' => '0',
				'total'    => '0',
				'status'   => 'P'
			];

			$query_receipt = $db->table('transport_receipt')
				->where('md5(travel_id::varchar)',$request->travel)
				->update($travel_receipt_cancel_data);

			//impuestos y cuotas del viaje a 0
			$db->query("UPDATE transport_receipt_tax rt
						SET    amount = '0.00'
						FROM   transport_receipt r
						JOIN   transport_travel t ON t.id = r.travel_id
						WHERE  r.id = rt.receipt_id
						AND md5(t.id::VARCHAR) = '$request->travel'");

			if($query && $query_receipt){

				return true;

			}else{

				throw new ExceptionApi('error', 'error in cancel travel', 400);

			}

		}else{

			throw new ExceptionApi('error', 'travel not found', 400);

		}

	}

	//detalles del viaje seleccionado
	function travel_details($params){
        
        global $db;

		//valida el status del viaje para consultar los detalles
		switch ($params['status']) {
			case 'F':
				$details = TravelModel::getFinalizedDetails($params);//viaje finalizado correctamente
				break;

			case 'S':
				$details = TravelModel::getSolicitedDetails($params);//viaje solicitado, falta confirmacion del conductor
				break;

			case 'P':
				$details = TravelModel::getPendingDetails($params);//viaje pendiente, esperando al conductor
				break;

			case 'T':
				$details = TravelModel::getInTransitDetails($params);//viaje pendiente, esperando al conductor
				break;

			case 'C':
				$details = TravelModel::getCanceledDetails($params);//viaje finalizado correctamente
				break;
			
			default:
				$details = TravelModel::getFinalizedDetails($params);//viaje finalizado correctamente
				break;
		}

		return $details;

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del usuario
	private function getUser($request){

		global $db;		
		
		$user = $db->select('id AS user')
					->table('transport_user')
					->where('email',$request->email)
					->where('id_type_id',1)
					->where('phone',$request->phone)
					->where('status','Active')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $user;
	
	}

	//consultar ID del conductor
	private function getDriver($request){

		global $db;		
		
		$driver = $db->select('id AS driver')
					->table('transport_user')
					->where('md5(id::varchar)',$request->driver)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $driver;
	
	}

	//consultar ID de la region
	private function getRegion($request){

		global $db;		
		
		$region = $db->select('id AS region')
					->table('transport_region')
					->where('md5(id::varchar)',$request->region)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'region not found', 400);

		}
		
		return $region;
	
	}

	//consultar detalles de viajes finalizados
	private function getFinalizedDetails($params){

		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						t.start_lat,
						t.start_long,
						t.end_lat,
						t.end_long,
						t.start_address,
						t.end_address,
						DATE(t.date_init) AS date_init,
						to_char(t.date_init::Time, 'HH12:MI:SS AM') AS time_init,
						DATE(t.date_end) AS date_end,
						to_char(t.date_end::Time, 'HH12:MI:SS AM') AS time_end,
						concat(t.distance,' Km') AS distance,
						(date_end- date_init) AS duration_travel,
						t.status,
						md5(u.id::varchar) AS driver,
						concat(u.first_name,' ',u.last_name) AS driver_name,
						r.rate_driver AS driver_rate,
						r.comments_driver AS driver_comments,
						concat(c.brand,' ',c.model) AS driver_car")
		->table('transport_travel AS t')
		->join('transport_user AS u', 'u.id', 't.id_driver_id')
		->join('transport_rates AS r', 'r.travel_id', 't.id')
		->join('transport_driver_group AS g', 'g.driver_id', 't.id_driver_id')
		->join('transport_detail_car AS c', 'c.id', 'g.id_car_id')
		->where('md5(t.id::varchar)',$params['travel'])
		->where('t.status','F')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes solicitados
	private function getSolicitedDetails($params){
		
		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						start_lat,
						start_long,
						suggested_lat,
						suggested_long,
						start_address,
						suggested_address,
						DATE(date_init) AS date_init,
						to_char(date_init::Time, 'HH12:MI:SS AM') AS time_init,
						status,
						code AS travel_code,
						cost")
		->table('transport_travel')
		->where('md5(id::varchar)',$params['travel'])
		->where('status','S')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes pendientes - esperando al conductor
	private function getPendingDetails($params){

		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						start_lat,
						start_long,
						suggested_lat,
						suggested_long,
						start_address,
						suggested_address,
						DATE(date_init) AS date_init,
						to_char(date_init::Time, 'HH12:MI:SS AM') AS time_init,
						status,
						code AS travel_code,
						cost,
						md5(id_driver_id::varchar) AS driver")
		->table('transport_travel')
		->where('md5(id::varchar)',$params['travel'])
		->where('status','P')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes en transito - el conductor ha llegado y ha iniciado el viaje
	private function getInTransitDetails($params){
		
		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						t.start_lat,
						t.start_long,
						t.suggested_lat,
						t.suggested_long,
						t.start_address,
						t.suggested_address,
						DATE(t.date_init) AS date_init,
						to_char(t.date_init::Time, 'HH12:MI:SS AM') AS time_init,
						t.status,
						t.distance,
						c.cost AS cost_km,
						t.cost,
						rec.total AS cost_total")
		->table('transport_travel AS t')
		->join('transport_region AS r', 'r.id', 't.region_id')
		->join('transport_cost AS c', 'c.id_region_id', 'r.id')
		->join('transport_receipt AS rec', 'rec.travel_id', 't.id')
		->where('md5(t.id::varchar)',$params['travel'])
		->where('t.status','T')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes cancelados
	private function getCanceledDetails($params){

		global $db;

		//consultar detalles del viaje
		$details = $db->select("
						t.start_lat,
						t.start_long,
						t.end_lat,
						t.end_long,
						t.start_address,
						t.end_address,
						DATE(t.date_init) AS date_init,
						to_char(t.date_init::Time, 'HH12:MI:SS AM') AS time_init,
						DATE(t.date_end) AS date_end,
						to_char(t.date_end::Time, 'HH12:MI:SS AM') AS time_end,
						concat(t.distance,' Km') AS distance,
						(date_end- date_init) AS duration_travel,
						t.status,
						md5(u.id::varchar) AS driver,
						concat(u.first_name,' ',u.last_name) AS driver_name,
						'Viaje Cancelado' AS driver_rate,
						'Viaje Cancelado' AS driver_comments,
						concat(c.brand,' ',c.model) AS driver_car")
		->table('transport_travel AS t')
		->join('transport_user AS u', 'u.id', 't.id_driver_id')
		->join('transport_driver_group AS g', 'g.driver_id', 't.id_driver_id')
		->join('transport_detail_car AS c', 'c.id', 'g.id_car_id')
		->where('md5(t.id::varchar)',$params['travel'])
		->where('t.status','C')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}


	//consultar ID del usuario
	private function check_pending_travel($request){

		global $db;		
		
		$travel = $db->select('id AS travel')
					->table('transport_travel')
					->where('md5(id::varchar)',$request->travel)
					->where('status','P')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}else{

			return true;
		}
	
	}

	//consultar detalles del viaje pendiente - cuando se quiera cancelar
	private function get_pending_details($request){

		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						start_lat,
						start_long,
						start_address")
		->table('transport_travel')
		->where('md5(id::varchar)',$request->travel)
		->where('status','P')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar costos de la region
	private function getRegionCosts($request){

		global $db;		
		
		$costs = $db->select('
						cost AS cost_km,
						cost_min')
					->table('transport_cost')
					->where('md5(id_region_id::varchar)',$request->region)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'region costs not found', 400);

		}
		
		return $costs;
	
	}

}