<?php

/**
* modelo del controlador de las regiones (donde hay disponibilidad del servicio)
*/

class RegionModel
{

	//lista de regiones
	function region_list($params){
        
        if($params['filter'] == 'all'){
	        
	        global $db;

			//consultar regiones
			$region = $db->select("
							md5(r.id::varchar) AS region,
							r.name,
							r.state,
							r.city,
							r.center_lat,
							r.center_long,
							r.radius AS raidius_km,
							c.cost AS cost_km,
							c.cost_min")
			->table('transport_region AS r')
			->join('transport_cost AS c','c.id_region_id','r.id')
			->getAll();

			return ($db->numRows() > 0) ? $region : [];
		}
	}

	//region donde se tiene cobertura
	function region_coverage($request){

		//verificar usuario
		$driver = RegionModel::getDriverID($request);
		
		//ubicacion del usuario
		$driver_location = $request->lat.','.$request->long;

		//consultar regiones
		$regiones = RegionModel::getRegiones();

		//obtener region donde se tenga cobertura
		foreach ($regiones AS $region){

			//centro de la region
			$region_center = $region->center_lat.','.$region->center_long;
			
			//radio de la region
			$region_radius = $region->radius_km;

			//distancia a la que se encuentra el usuario
			$driver_region_distance = RegionModel::getDistance($driver_location,$region_center);

			//depurar las regiones con cobertura
			if($driver_region_distance < $region_radius){

				//arreglo de regiones con cobertura
				$coverage_regions[] = [	
								
					'region' 				 => $region->region,
					'name' 					 => $region->name,
					'state' 				 => $region->state,
					'city' 					 => $region->city,
					'center_lat'  	   		 => $region->center_lat,
					'center_long' 			 => $region->center_long,
					'radius_km'  	   		 => $region->radius_km,
					'cost_km'     			 => $region->cost_km,
					'cost_min'     			 => $region->cost_min,
					'driver_region_distance' => number_format($driver_region_distance,2)
				
				];

			}

		}
		
		//en caso de haber mas de una region con cobertura se depura a la mas cercana al centro
		if(count($coverage_regions) > 1){

			//region mas cercarna
			$closest_region = min(array_column($coverage_regions, 'driver_region_distance'));

			//extraer la region mas cercana del arreglo
			foreach ($coverage_regions as $cov_reg) {
				
				if($cov_reg['driver_region_distance'] == $closest_region){

					$coverage_region[] = $cov_reg;
				}
			}

		}else{

			 $coverage_region = $coverage_regions;
		}

		return $coverage_region;
	}

	/**
	* funciones privadas del modelo
	*/

	//consultar ID del conductor
	private function getDriverID($request){

		global $db;		
		
		$driver = $db->select('id AS driver')
					->table('transport_user')
					->where('email',$request->email)
					->where('phone',$request->phone)
					->where('status','Active')
					->get();

		if (!$db->numRows() > 0) {
			
			throw new ExceptionApi('error', 'driver not found', 400);

		}
		
		return $driver;
	
	}

	//lista de regiones
	function getRegiones(){
        
        global $db;

		//consultar regiones
		$region = $db->select("
						md5(r.id::varchar) AS region,
						r.name,
						r.state,
						r.city,
						r.center_lat,
						r.center_long,
						r.radius AS radius_km,
						c.cost AS cost_km,
						c.cost_min")
		->table('transport_region AS r')
		->join('transport_cost AS c','c.id_region_id','r.id')
		->orderby('r.id','ASC')
		->getAll();

		return ($db->numRows() > 0) ? $region : [];

	}

	//***********************************************************//
	private function getDistance($from, $to){
	    
	    $from = urlencode($from);
		$to = urlencode($to);

		$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false&key=AIzaSyDjsi_LKP5OT1l2b3AJ5ZczihRqx92upFQ");
		$data = json_decode($data);

		$distance = 0;

		foreach($data->rows[0]->elements as $road) {
		
		    $distance += $road->distance->value;
		
		}

		return $distance/1000;

	}

}