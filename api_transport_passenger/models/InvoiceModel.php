<?php

/**
* modelo del controlador de perfiles de facturación
*/

class InvoiceModel
{

	//lista de perfiles de facturacion
	function invoice_list($params){

		global $db;

		//extraer variables
		$email = $params['email'];
		$phone = $params['phone'];

		//obtener ID del usuario
		$user = InvoiceModel::getUser($email,$phone);

		//consultar perfiles
		$invoices = $db->select('
						md5(i.id::varchar) AS invoice_profile,
						i.name,
						i.rfc,
						i.razon,
						i.calle,
						i.num_ext,
						i.num_int,
						i.colonia,
						i."CP",
						i.municipio,
						i.ciudad,
						i.estado,
						i.email_alt,
						i.forma_pago,
						i.uso_cfdi,
						i.tipo_facturacion')
		->table('transport_user_invoice_data AS i')
		->join('transport_user AS u', 'u.id', 'i.user_id')
		->where('u.id',$user->user)
		->getAll();

		return ($db->numRows() > 0) ? $invoices : [];

	}

	//registro de perfil de facturacion
	function invoice_register($request){

		global $db;

		//extraer variables
		$email = $request->email;
		$phone = $request->phone;
        
		//obtener ID del usuario
		$user = InvoiceModel::getUser($email,$phone);

		//verificar si existe el RFC en la tabla
		if(InvoiceModel::invoice_check($request,$user)){
			
			//registro del perfil de facturacion
			$invoice_insert_data = [
				'user_id' 		   => $user->user,
				'name'             => $request->name,
                'rfc'              => $request->rfc,
                'razon'            => $request->razon,
                'calle'            => $request->calle,
                'num_ext'          => $request->num_ext,
                'num_int'          => $request->num_int,
                'colonia'          => $request->colonia,
                '"CP"'               => $request->CP,
                'municipio'        => $request->municipio,
                'ciudad'           => $request->ciudad,
                'estado'           => $request->estado,
                'email_alt'        => $request->email_alt,
                'forma_pago'       => $request->forma_pago,
                'uso_cfdi'         => $request->uso_cfdi,
                'tipo_facturacion' => $request->tipo_facturacion];

                //echo "<pre>"; var_dump($invoice_insert_data); die();

			return $db->table('transport_user_invoice_data')->insert($invoice_insert_data);

		}else{

			//return false;
			throw new ExceptionApi('error', 'invoice profile already exists', 400);

		}

	}

	//actualizar perfil de facturacion
	function invoice_update($request){

		global $db;

		$data = [
				'name'             => $request->name,
                'rfc'              => $request->rfc,
                'razon'            => $request->razon,
                'calle'            => $request->calle,
                'num_ext'          => $request->num_ext,
                'num_int'          => $request->num_int,
                'colonia'          => $request->colonia,
                '"CP"'             => $request->CP,
                'municipio'        => $request->municipio,
                'ciudad'           => $request->ciudad,
                'estado'           => $request->estado,
                'email_alt'        => $request->email_alt,
                'forma_pago'       => $request->forma_pago,
                'uso_cfdi'         => $request->uso_cfdi,
                'tipo_facturacion' => $request->tipo_facturacion];

		$query = $db->table('transport_user_invoice_data')
				->where('md5(id::varchar)', $request->invoice_profile)
				->update($data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error updating invoice profile', 400);

		}
	
	}

	//eliminar perfil de facturacion
	function invoice_delete($params){

		global $db;

		//elimina el perfil de la tabla
		return $db->table('transport_user_invoice_data')->where('md5(id::varchar)',$params['invoice'])->delete();

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
			
			return ['status' => 'error', 'message' => 'user not found'];
			//throw new ExceptionApi('error', 'user not found', 400);

		}
		
		return $user;
	
	}

	//verificar existencia del RFC en la tabla
	private function invoice_check($request,$user){
		
		global $db;

		$query = $db->select('rfc')
					->table('transport_user_invoice_data')
					->where('rfc',$request->rfc)
					->where('user_id',$user->user)
					->get();

		if ($db->numRows() > 0) {
			
			return false;

		}else{

			return true;

		}

	}

}