<?php

/**
* modelo del controlador de tarjetas de credito/debito
*/

class CardsModel
{

	//lista de tarjetas
	function cards_list($params){

		global $db;

		//extraer variables
		$email = $params['email'];
		$phone = $params['phone'];

		//obtener ID del usuario
		$user = CardsModel::getUser($email,$phone);

		//consultar tarjetas
		$cards = $db->select('
						md5(c.id::varchar) AS card,
						c.holder_name,
						c.holder_lastname,
						c.card_number,
						c.exp_month,
						c.exp_year')
		->table('transport_card AS c')
		->join('transport_user AS u', 'c.user_id', 'u.id')
		->where('u.id',$user->user)
		->getAll();

		return ($db->numRows() > 0) ? $cards : [];

	}

	//registro de tarjetas
	function cards_register($request){

		global $db;

		//extraer variables
		$email = $request->email;
		$phone = $request->phone;

		//obtener ID del usuario
		$user = CardsModel::getUser($email,$phone);

		//verificar si existe la tarjeta en la tabla
		if(CardsModel::card_check($request,$user)){
			
			//registro de la tarjeta en la tabla
			$card_insert_data = [
				'holder_name' 	  => $request->holder_name,
				'holder_lastname' => $request->holder_lastname,
				'card_number'	  => $request->card_number,
				'exp_month' 	  => $request->ex_month,
				'exp_year' 		  => $request->exp_year,
				'cvv' 			  => $request->cvv,
				'user_id' 	 	  => $user->user];
			
			return $db->table('transport_card')->insert($card_insert_data);
		
		}else{

			throw new ExceptionApi('error', 'card already exists', 400);

		}

	}

	//actualizar tarjeta
	function cards_update($request){

		global $db;

		$data = ['exp_month' => $request->ex_month,'exp_year' => $request->exp_year,'cvv' => $request->cvv];

		$query = $db->table('transport_card')
				->where('md5(id::varchar)', $request->card)
				->update($data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error updating card', 400);

		}
	
	}

	//eliminar tarjetas
	function cards_delete($request){
		
		global $db;

		//elimina la tarjeta de la tabla
		return $db->table('transport_card')->where('md5(id::varchar)',$request->card)->delete();

	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del usuario
	private function getUser($email,$phone){

		global $db;		

		$user = $db->select('id AS user')
					->table('transport_user')
					->where('email',$email)
					->where('id_type_id',1)
					->where('phone',$phone)
					->where('status','Active')
					->get();
		
		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $user;
	
	}

	//verificar existencia de la tarjeta
	private function card_check($request,$user){
		
		global $db;

		$query = $db->select('card_number')
					->table('transport_card')
					->where('card_number',$request->card_number)
					->where('user_id',$user->user)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

}