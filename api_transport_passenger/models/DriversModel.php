<?php

/**
* modelo del controlador de conductores - online
*/

class DriversModel
{

	//lista de conductores online
	function drivers_online($params){

		$passenger_ubication = $params['passenger_ubication'];
		//$passenger_ubication = '17.0584916,-96.7190036';

		if($params['online'] == 'T'){

			global $db;

			if(is_null($params['region'])){

				//consultar conductores
				$conductores = $db->select("
								md5(id::varchar) AS driver,
								first_name,
								last_name,
								email,
								phone,
								google_token")
				->table('transport_user')
				->where('status','Active')
				->where('online','T')
				->in('id_type_id', [2,3])
				->orderBy('id', 'ASC')
				->getAll();
				
			
			}else{

				//consultar conductores
				$conductores = $db->select("
								md5(d.id::VARCHAR) AS driver,
								d.first_name,
								d.last_name,
								d.email,
								d.phone,
								d.google_token")
				->table('transport_user AS d')
				->join('transport_driver_region AS dr', 'dr.id_driver', 'd.id')
				->join('transport_region AS r', 'r.id', 'dr.id_region')
				->where('d.status','Active')
				->where('d.online','T')
				->where('md5(r.id::VARCHAR)',$params['region'])
				->in('d.id_type_id', [2,3])
				->orderBy('d.id', 'ASC')
				->getAll();

			}

			//obtener a los conductores mas cercanos
			if(!is_null($passenger_ubication)){

				//consultar datos de firebase
				foreach ($conductores AS $conductor){

					//URL del nuevo reporsitorio FIREBASE
					if(!is_null($params['new_url'])){

						$f_url = 'https://tase-91ea6.firebaseio.com/Driver/'.$conductor->google_token.'.json';

					}else{

						$f_url = 'https://maxlexgeo-57dd4.firebaseio.com/Driver/'.$conductor->google_token.'.json';
					}

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $f_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$response = curl_exec($ch);
					curl_close($ch);

					//arreglo de firebase
					$conductor_firebase_data = json_decode($response, true);

					//arreglo de conductores en linea
					if( !is_null($conductor_firebase_data['latitud']) && $conductor_firebase_data['latitud'] > 0 ){

						//calcular distancia y tiempo hacia el pasajero
						$distancia = DriversModel::getDistance($passenger_ubication, $conductor_firebase_data['latitud'].','.$conductor_firebase_data['longitud']);

						//distancia maxima
						$distancia_max = 5;

						//filtrar conductores cercanos
						if( $distancia['distance'] <= $distancia_max ){

							$conductores_online[] = [	
								
								'driver' 	   => $conductor->driver,
								'first_name'   => $conductor->first_name,
								'last_name'    => $conductor->last_name,
								'email' 	   => $conductor->email,
								'phone'  	   => $conductor->phone,
								'google_token' => $conductor->google_token,
								'latitud'  	   => $conductor_firebase_data['latitud'],
								'longitud'     => $conductor_firebase_data['longitud'],
								'distance_km'  => number_format($distancia['distance'],4),
								'time_min' 	   => number_format($distancia['time_min'],2)
							
							];

						}

					}

				}


				if(!is_null($conductores_online)){

					return $conductores_online;

				}else{

					return $conductores_online = [	['driver' => 'there are no drivers near the passenger'] ];
				}

			}else{

				return $conductores;

			}

		}
		
	}

	//***********************************************************//
	private function getDistance($from, $to){
	    
	    $from = urlencode($from);
		$to = urlencode($to);

		$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false&key=AIzaSyDjsi_LKP5OT1l2b3AJ5ZczihRqx92upFQ");
		$data = json_decode($data);

		$time = 0;
		$distance = 0;

		foreach($data->rows[0]->elements as $road) {
		
		    $time += $road->duration->value;
		    $distance += $road->distance->value;
		
		}

		return $distance_time = [
		
			"distance" => $distance/1000,
			"time_min" => $time/60
		];

	}

}