<?php
error_reporting(E_ERROR);
date_default_timezone_set('America/Mexico_City');
/**
* modelo del controlador del cotizador de viajes
*/

class EstimationModel
{

	//estimacion del costo del viaje
	function travel_estimate($params){

		global $db;

		//verificar si viene el id de la region
		if(!is_null($params['region'])){
		
			//obtener los costos de la region
			$region_costs = EstimationModel::getRegionCost($params['region']);

			//costos
			$cost_km  = $region_costs->cost_km;
			$cost_min = $region_costs->cost_min;

			//Subtotal - tarifa del viaje
			$subtotal = ( ($params['distance'] * $cost_km) + ($params['estimation'] * $cost_min) );

		}else{

			//Subtotal - tarifa del viaje
			$subtotal = ( ($params['distance'] * $params['cost_km']) + ($params['estimation'] * $params['cost_min']) );

		}
		
		//obtener cuotas e impuestos internos
		$taxes = EstimationModel::getTaxes();

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
		$travel_estimation   = array(
		   'travel_distance' => $params['distance'],
		   'travel_duration' => $params['estimation'],
		   'travel_cost_km'	 => $params['cost_km'],
		   'travel_cost_min' => $params['cost_min'],
		   'travel_cost'  	 => round($subtotal,2),
		   'travel_tax'   	 => round($taxes_total,2),
		   'travel_total' 	 => round($total,2)
		);
		
		return $travel_estimation;

	}

	function log_estimation($params,$token_user){


		global $db;

		$data = [
					'cost_min' => $params['cost_min'],
					'cost_km' => $params['cost_km'],
					'distance' => $params['distance'],
					'time' => $params['estimation'],
					'date' => date('Y-m-d H:i:s'),
					'token' => $token_user
				];

		return $db->table('transport_debug_estimation')->insert($data);

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

}