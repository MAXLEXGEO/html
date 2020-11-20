<?php
error_reporting(E_ERROR);
date_default_timezone_set('America/Mexico_City');
/**
* modelo del controlador del cotizador de viajes
*/

class EstimationFinalizeModel
{

	//estimacion del costo del viaje
	function travel_estimate_finalize($params){

		global $db;

		//detalles del viaje
		$getTravel = EstimationFinalizeModel::getTravelDetails($params);

		//calcular el tiempo recorrido
		//fecha/hora final del viaje y la de inicio
        $start_date = new DateTime($getTravel->datetime_init);
        $end_date 	= new DateTime($params['travel_date']);

        //tiempo de recorrido final en minutos
        $interval      = date_diff($start_date, $end_date);
        $end_duration  = $interval->h * 60;
        $end_duration += $interval->i;

		//verificar si viene el id de la region
		if(!is_null($params['region'])){
		
			//obtener los costos de la region
			$region_costs = EstimationFinalizeModel::getRegionCost($params['region']);

			//costos
			$cost_km  = $region_costs->cost_km;
			$cost_min = $region_costs->cost_min;

			//Subtotal - tarifa del viaje
			$subtotal = ( ($params['travel_distance'] * $cost_km) + ($end_duration * $cost_min) );

		}else{

			//Subtotal - tarifa del viaje
			$subtotal = ( ($params['travel_distance'] * $params['cost_km']) + ($end_duration * $params['cost_min']) );

		}
		
		//obtener cuotas e impuestos internos
		$taxes = EstimationFinalizeModel::getTaxes();

		//Total de impuestos
		$taxes_total = 0;

		//hacer calculos con los impuestos
		foreach($taxes AS $tax){

			//porcentaje del impuesto
			$tax_percent = $tax->tax_percent/100;
			//importe del impuesto
			$tax_amount  = $subtotal * $tax_percent;
			//total del impuesto
			$taxes_total = $taxes_total + $tax_amount;

		}

		//total del viaje
		$total = $subtotal + $taxes_total;

		//arreglo de datos
		return $travel_estimation   = array(
		   'travel_distance' => $params['travel_distance'],
		   'travel_duration' => $end_duration,
		   'travel_cost_km'	 => $params['cost_km'],
		   'travel_cost_min' => $params['cost_min'],
		   'travel_cost'  	 => round($subtotal,2),
		   'travel_tax'   	 => round($taxes_total,2),
		   'travel_total' 	 => round($total,2)
		);
		
	}

	/**
	* funciones privadas del modelo
	*/

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

	//consultar costos de la region
	private function getRegionCost($region){

		global $db;		
		
		$costs = $db->select('
						cost AS cost_km,
						cost_min')
					->table('transport_cost')
					->where('md5(id_region_id::varchar)',$region)
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'region costs not found', 400);

		}
		
		return $costs;
	
	}

	//consultar detalles de viajes en transito
	private function getTravelDetails($params){
		
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
		->where('md5(t.id::varchar)',$params['travel'])
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

}