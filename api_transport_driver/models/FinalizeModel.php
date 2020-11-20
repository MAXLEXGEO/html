<?php
date_default_timezone_set('America/Mexico_City');
/**
* modelo del controlador del viaje
*/

class FinalizeModel
{

	//verificar el codigo del viaje
	function check_travel_code($params){

		global $db;

		$query = $db->select('md5(id::varchar)')
					->table('transport_travel')
					->where('md5(id::varchar)', $params['travel'])
					->where('code', $params['travel_code'])
					->get();

		if ($db->numRows() > 0) {
			
			return true;

		}else{

			return false;

		}

	}
	
	//finalizar el viaje > el pasajero no ha llegado a su destino
	function travel_finalize($params){
		
		global $db;

		//fecha y hora del final del viaje
		//$end_date = date('Y-m-d H:i:s');

		//ID viaje params
		$travel = $params['travel'];

		//datos del viaje
		$get_travel = FinalizeModel::getInTransitDetails($travel);

		//obtener ID del conductor
		$driver = FinalizeModel::getDriverID_EndTravel($params);

		###--------------------------------------------------------------------------------------------------------###
        #------------------- verifica si el recorrido ha sido mayor/menor y se recalcula el costo -------------------#
        ###--------------------------------------------------------------------------------------------------------###

		//distancia final recorrida y distancia estimada del viaje
        $end_distance 	 = $params['end_distance'];
        $travel_distance = $get_travel->distance;

        //fecha/hora final del viaje y la de inicio
        $start_date = new DateTime($get_travel->datetime_init);
        $end_date 	= new DateTime($params['end_date']);

        //tiempo de recorrido final en minutos
        $interval      = date_diff($start_date, $end_date);
        $end_duration  = $interval->h * 60;
        $end_duration += $interval->i;

        //tiempo estimado en minutos 
        $travel_duration = $get_travel->duration;        

		//en caso de ser mayor/menor la distancia final a la estimada
        if($end_distance > $travel_distance || $end_distance < $travel_distance){

            //verifica si tambien el tiempo final fue mayor/menor al estimado
            if($end_duration > $travel_duration || $end_duration < $travel_duration){

                //asignar la nueva duracion del viaje
                $travel_new_duration = $end_duration;
            
            }else{

                //asignar la nueva duracion del viaje
                $travel_new_duration = $travel_duration;
            }
			
            //asignar la nueva distancia del viaje
            $travel_new_distance = $end_distance;

            //recalcular el costo en tiempo real
            $travel_new_subtotal = (($travel_new_distance * $get_travel->cost_km)+($travel_new_duration * $get_travel->cost_min));

            //actualizar el costo del  viaje
            FinalizeModel::travel_cost_update($params,$travel_new_distance,$travel_new_duration,$travel_new_subtotal);

        }

        //en caso de ser mayor/menor el tiempo de recorrido final al estimado
        if($end_duration > $travel_duration || $end_duration < $travel_duration){

            //verifica si tambien la distancia final fue mayor/menor a la estimada
            if($end_distance > $travel_distance || $end_distance < $travel_distance){

                //asignar la nueva distancia del viaje
                $travel_new_distance = $end_distance;
            
            }else{

                //asignar la nueva distancia del viaje
                $travel_new_distance = $travel_distance;
            }
			
            //asignar la nueva distancia del viaje
            $travel_new_duration = $end_duration;

            //recalcular el costo en tiempo real
            $travel_new_subtotal = (($travel_new_distance * $get_travel->cost_km)+($travel_new_duration * $get_travel->cost_min));

            //actualizar el costo del  viaje
            FinalizeModel::travel_cost_update($params,$travel_new_distance,$travel_new_duration,$travel_new_subtotal);
			
        }

        ###--------------------------------------------------------------------------------------------------------###
        #------------------------------------------------------------------------------------------------------------#
        ###--------------------------------------------------------------------------------------------------------###
        
		//cambiar el status al conductor - des-occuped
		$status_driver = ['online' => 'T'];

		//actualizar status al conductor
		$query = $db->table('transport_user')
			->where('id',$driver->driver)
			->update($status_driver);
			
		//datos a actualizar del viaje
		$travel_end_data = [
			'status' 	  => 'F',
			'end_lat' 	  => $params['end_lat'],
			'end_long' 	  => $params['end_long'],
			'end_address' => $params['end_address'],
			'date_end' 	  => $params['end_date'],
			'code' 		  => 'ENDED'
		];

		//finalizar viaje
		return $query = $db->table('transport_travel')
				->where('md5(id::varchar)',$params['travel'])
				->where('md5(id_client_id::varchar)',$params['travel_passenger'])
				->where('id_driver_id',$driver->driver)
				->update($travel_end_data);

	}

	//finalizar el viaje > borrar trackeo del viaje
	function travel_clean($params){
		
		global $db;

		//elimina el perfil de la tabla
		return $db->table('transport_detail_travel')->where('md5(id_travel_id::varchar)',$params['travel'])->delete();

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del conductor
	private function getDriverID($request){

		global $db;		
		
		$driver = $db->select('id AS driver')
					->table('transport_user')
					->where('email',$request->driver_email)
					->where('phone',$request->driver_phone)
					->where('status','Active')
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $driver;
	
	}

	//consultar ID del conductor para finalizar el viaje
	private function getDriverID_EndTravel($params){

		global $db;		
		
		$driver = $db->select('id AS driver')
					->table('transport_user')
					->where('email',$params['driver_email'])
					->where('phone',$params['driver_phone'])
					->where('status','Active')
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $driver;
	
	}

	//consultar detalles de viajes finalizados
	private function getFinalizedDetails($travel){

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
						md5(u.id::varchar) AS passenger,
						concat(u.first_name,' ',u.last_name) AS passenger_name,
						r.rate_client AS passenger_rate,
						r.comments_client AS passenger_comments")
		->table('transport_travel AS t')
		->join('transport_user AS u', 'u.id', 't.id_client_id')
		->join('transport_rates AS r', 'r.travel_id', 't.id')
		->where('md5(t.id::varchar)',$travel)
		->where('t.status','F')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes solicitados
	private function getSolicitedDetails($travel){
		
		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						t.start_lat,
						t.start_long,
						t.suggested_lat,
						t.suggested_long,
						t.start_address,
						t.suggested_address,
						t.status,
						r.total,
						md5(t.id_client_id::varchar) AS travel_passenger,
						u.google_token AS travel_passenger_google")
		->table('transport_travel AS t')
		->join('transport_receipt AS r', 'r.travel_id', 't.id')
		->join('transport_user AS u', 'u.id', 't.id_client_id')
		->where('md5(t.id::varchar)',$travel)
		->where('t.status','S')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes pendientes - esperando al conductor
	private function getPendingDetails($travel){

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
						r.total")
		->table('transport_travel AS t')
		->join('transport_receipt AS r', 'r.travel_id', 't.id')
		->where('md5(t.id::varchar)',$travel)
		->where('t.status','P')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//consultar detalles de viajes en transito - el conductor ha llegado y ha iniciado el viaje
	private function getInTransitDetails($travel){
		
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
						\"timestamp\"(t.date_init) AS datetime_init,
						t.duration,
						t.status,
						t.distance,
						c.cost AS cost_km,
						c.cost_min,
						t.cost,
						tr.total AS cost_total")
		->table('transport_travel AS t')
		->join('transport_region AS r', 'r.id', 't.region_id')
		->join('transport_cost AS c', 'c.id_region_id', 'r.id')
		->join('transport_receipt AS tr', 'tr.travel_id', 't.id')
		->where('md5(t.id::varchar)',$travel)
		->where('t.status','T')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//verificar existencia del viaje
	private function checkTravel($request){

		global $db;

		$query = $db->select('md5(id::varchar)')
					->table('transport_travel')
					->where('md5(id::varchar)',$request->travel)
					->get();

		if ($db->numRows() > 0) {
			
			return true;

		}else{

			return false;

		}

	}

	//consultar ID del viaje
	private function getTravelID($request){

		global $db;		
		
		$travel = $db->select('id AS travel')
					->table('transport_travel')
					->where('md5(id::varchar)',$request->travel)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $travel;
	
	}

	//consultar las cuotas e impuestos internos
	private function getTaxes(){

		global $db;		
		
		$user = $db->select('
						id AS tax,
						name AS tax_name,
						percentage AS tax_percent')
					->table('transport_tax')
					->getAll();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'taxes not found', 400);

		}
		
		return $user;
	
	}

	//consultar ID del recibo
	private function getReceipt($params){
		
		global $db;

		$receipt = $db->select('id AS receipt')
					->table('transport_receipt')
					->where('md5(travel_id::varchar)',$params['travel'])
					->get();

		if ($db->numRows() > 0) {
			
			return $receipt;

		}else{

			return false;

		}

	}

	//verificar el codigo del viaje
	private function checkCodeTravel($request){

		global $db;

		$query = $db->select('md5(id::varchar)')
					->table('transport_travel')
					->where('md5(id::varchar)', $request->travel)
					->where('code', $request->travel_code)
					->get();

		if ($db->numRows() > 0) {
			
			return true;

		}else{

			return false;

		}

	}

	//actualizar el nuevo costo del viaje
	private function travel_cost_update($params,$travel_new_distance,$travel_new_duration,$travel_new_subtotal){

		global $db;

		//obtener cuotas e impuestos internos
		$taxes = FinalizeModel::getTaxes();

		//obtener el ID del recibo
		$receipt_id = FinalizeModel::getReceipt($params);

		//Total de impuestos
		$taxes_total = 0;

		//hacer calculos con los impuestos
		foreach($taxes AS $tax){

			//porcentaje del impuesto
			$tax_percent = $tax->tax_percent / 100;
			//importe del impuesto
			$tax_amount  = $travel_new_subtotal * $tax_percent;
			//totales de impuestos
			$taxes_total = $taxes_total + $tax_amount;
			//datos a actualizar de los impuestos
			$taxes_receipt_data = ['amount' => round($tax_amount,2)];

			//actualizar impuestos
			$update_receipt_tax = $db->table('transport_receipt_tax')
				->where('receipt_id', $receipt_id->receipt)
				->where('tax_id', $tax->tax)
				->update($taxes_receipt_data);

		}

		//total del viaje
		$total = $travel_new_subtotal + $taxes_total;

		//datos para actualizar el viaje
		$new_travel_data = ['distance' => $travel_new_distance, 'duration' => $travel_new_duration, 'cost' => round($travel_new_subtotal,2)];

		//actualizar datos del viaje
		$update_travel_data = $db->table('transport_travel')
				->where('md5(id::varchar)', $params['travel'])
				->update($new_travel_data);

		//datos para actualizar el recibo del viaje
		$new_receipt_data = ['subtotal' => round($travel_new_subtotal,2), 'total' => round($total,2)];

		//actualizar datos del recibo
		$update_receipt_data = $db->table('transport_receipt')
				->where('id', $receipt_id->receipt)
				->where('md5(travel_id::varchar)', $params['travel'])
				->update($new_receipt_data);		

	}

}