<?php
date_default_timezone_set('America/Mexico_City');
/**
* modelo del controlador del trackeo del viaje
*/

class TrackModel
{
	//detalles del viaje
	function get_travel_details($request){
		
		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
						t.status,
						t.distance AS travel_distance,
						\"timestamp\"(t.date_init) AS travel_start,
						t.duration AS travel_duration,
						c.cost AS cost_km,
						c.cost_min")
		->table('transport_travel AS t')
		->join('transport_region AS r', 'r.id', 't.region_id')
		->join('transport_cost AS c', 'c.id_region_id', 'r.id')
		->join('transport_receipt AS rec', 'rec.travel_id', 't.id')
		->where('md5(t.id::varchar)',$request->travel)
		->where('t.status','T')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	//registro del trackeo del viaje
	function tracking_register($request){
		
		global $db;

		//obtener ID del viaje
		$travel = TrackModel::getTravel($request);

		//registro de los datos de tracking en la tabla
		$tracking_insert_data = [
			'c_lat' 	   => $request->tracking_lat,
			'c_long' 	   => $request->tracking_long,
			'distance'     => $request->tracking_distance,
			'date' 		   => $request->tracking_date,
			'id_travel_id' => $travel->travel
		];

		return $db->table('transport_detail_travel')->insert($tracking_insert_data);

	}

	//actualizar el nuevo costo del viaje
	function travel_cost_update($request,$travel_new_distance,$travel_new_duration,$travel_new_subtotal){

		global $db;

		//obtener cuotas e impuestos internos
		$taxes = TrackModel::getTaxes();

		//obtener el ID del recibo
		$receipt_id = TrackModel::getReceipt($request);

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
				->where('md5(id::varchar)', $request->travel)
				->update($new_travel_data);

		//datos para actualizar el recibo del viaje
		$new_receipt_data = ['subtotal' => round($travel_new_subtotal,2), 'total' => round($total,2)];
		//actualizar datos del recibo
		$update_receipt_data = $db->table('transport_receipt')
				->where('id', $receipt_id->receipt)
				->where('md5(travel_id::varchar)', $request->travel)
				->update($new_receipt_data);

		if($update_receipt_data){

			return true;

		}else{

			throw new ExceptionApi('error', 'error updating new cost in travel', 400);

		}
	
	}

	//costos del viaje actualizados
	function get_travel_new_costs($request){
		
		global $db;		

		//consultar detalles del viaje
		$details = $db->select("
						t.status,
						t.distance,
						t.cost,
						rec.total AS cost_total")
		->table('transport_travel AS t')
		->join('transport_receipt AS rec', 'rec.travel_id', 't.id')
		->where('md5(t.id::varchar)',$request->travel)
		->where('t.status','T')
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del viaje
	private function getTravel($request){

		global $db;		
		
		$travel = $db->select('id AS travel')
					->table('transport_travel')
					->where('md5(id::varchar)',$request->travel)
					->where('status','T')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

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
	private function getReceipt($request){
		
		global $db;

		$receipt = $db->select('id AS receipt')
					->table('transport_receipt')
					->where('md5(travel_id::varchar)',$request->travel)
					->get();

		if ($db->numRows() > 0) {
			
			return $receipt;

		}else{

			return false;

		}

	}

}