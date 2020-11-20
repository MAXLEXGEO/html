<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador del recibo del viaje
*/

class ReceiptModel
{

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