<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador del recibo del viaje
*/

class ReceiptModel
{

	//estimacion del costo del viaje
	function receipt_register($request){

		global $db;

		//verificar si existe el recibo
		if(ReceiptModel::checkReceipt($request)){
			
			//obtener cuotas e impuestos internos
			$taxes = ReceiptModel::getTaxes();

			//obtener detalles del viaje
			$travel_details = ReceiptModel::getTravelDetails($request);

			//tarifa del viaje
			$subtotal = $travel_details->cost_travel;

			//Total de impuestos
			$taxes_total = 0;

			//arreglo de datos del recibo
			$receipt_insert_data = [
				'subtotal' 	   => $subtotal,
				'total' 	   => $travel_details->cost_travel,
				'payment_form' => 'E',
				'status'	   => 'N',
				'travel_id'    => $travel_details->travel
			];

			//ID del recibo
			$receipt_id = $db->table('transport_receipt')->insert($receipt_insert_data);

			//hacer calculos con los impuestos
			foreach($taxes AS $tax){

				//porcentaje del impuesto
				$tax_percent = $tax->tax_percent/100;
				
				//importe del impuesto
				$tax_amount  = $subtotal * $tax_percent;

				//totales de impuestos
				$taxes_total = $taxes_total + $tax_amount;
				
				//arreglo de datos del los impuestos
				$tax_insert_data = [
					'amount' 	 => round($tax_amount,2),
					'receipt_id' => $receipt_id,
					'tax_id' 	 => $tax->tax
				];

				//registro de los impuestos
				$db->table('transport_receipt_tax')->insert($tax_insert_data);
				
			}

			//total del viaje
			$total = $subtotal + $taxes_total;

			//datos para actualizar el recibo del viaje
			$receipt_data = ['subtotal' => round($subtotal,2), 'total' => round($total,2)];
			
			//actualizar datos del recibo
			$update_receipt_data = $db->table('transport_receipt')
				->where('id', $receipt_id)
				->where('md5(travel_id::varchar)', $request->travel)
				->update($receipt_data);

			if($update_receipt_data){

				return true;

			}else{

				throw new ExceptionApi('error', 'error in receipt register', 400);

			}

		}else{

			throw new ExceptionApi('error', 'receipt already exists', 400);
		
		}

	}

	//detalles del recibo del viaje
	function receipt_details($params){

		global $db;

		$receipt = $db->select('
							md5(r.id::varchar) AS receipt,
							DATE(t.date_end) AS date_travel,
							t.status AS travel_status,
							r.payment_form,
							r.subtotal,
							r.total,
							r.status')
					->table('transport_receipt AS r')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->where('md5(t.id::varchar)',$params['travel'])
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'receipt not found', 400);

		}

		return $receipt;

	}

	//impuestos/cuotas del recibo del viaje
	function receipt_taxes($params){

		global $db;

		$receipt_taxes = $db->select('
							tt.name,
							tt.percentage,
							rt.amount')
					->table('transport_tax AS tt')
					->join('transport_receipt_tax AS rt', 'rt.tax_id', 'tt.id')
					->join('transport_receipt AS r', 'r.id', 'rt.receipt_id')
					->join('transport_travel AS t', 't.id', 'r.travel_id')
					->where('md5(t.id::varchar)',$params['travel'])
					->getAll();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'receipt not found', 400);

		}

		return $receipt_taxes;

	}

	//pago del recibo del viaje
	function receipt_payment($request){

		global $db;

		//datos para registrar las calificaciones
		$receipt_data = ['status' => 'P'];

		$query = $db->table('transport_receipt')
				->where('md5(travel_id::varchar)',$request->travel)
				->update($receipt_data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error updating receipt payment', 400);

		}
	
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

	//verificar existencia del recibo
	private function checkReceipt($request){

		global $db;

		$query = $db->select('md5(id::varchar)')
					->table('transport_receipt')
					->where('md5(travel_id::varchar)',$request->travel)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

	//consultar detalles del viaje
	private function getTravelDetails($request){
		
		global $db;		
		
		//consultar detalles del viaje
		$details = $db->select("
							t.id AS travel,
							t.cost AS cost_travel,
							t.distance,
							c.cost AS cost_km")
		->table('transport_travel AS t')
		->join('transport_region AS r','r.id','t.region_id')
		->join('transport_cost AS c','c.id_region_id','r.id')
		->where('md5(t.id::varchar)',$request->travel)
		->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'travel not found', 400);

		}
		
		return $details;
	
	}

}