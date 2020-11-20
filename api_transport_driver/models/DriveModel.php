<?php

/**
* modelo del controlador del conductor cuando esta "En Servicio"
*/

class DriveModel
{

	//cambiar el status - En Servicio
	function ondrive_status($request){

		global $db;

		$data = ['online' => $request->ondrive];

		$query = $db->table('transport_user')
				->where('email', $request->email)
				->where('phone', $request->phone)
				->update($data);

		if($query){

			return true;

		}else{

			throw new ExceptionApi('error', 'error change ondrive status', 400);

		}
	
	}

}