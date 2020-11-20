<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador de usuario
*/

class PhotoModel
{
	//cargar la imagen
	function photo_upload($request){
		
		global $db;

		if(isset($request['profile_photo'])){

			if ($_FILES['profile_photo']['size'] > 10000000) {

                throw new ExceptionApi('error', 'profile photo size is too bigger', 400);

			}else{

				//verificar usuario
				$get_user = PhotoModel::user_check($request);

				if($get_user != false){

					//ruta de la foto
					$folder_photo = 'Users/'.date('Y').'/'.date('m').'/'.date('d');

					//crear ruta para la foto
	                if (!file_exists('../../tso.maxlexgeo.com/media/'.$folder_photo)){
	                    
	                	mkdir('../../tso.maxlexgeo.com/media/'.$folder_photo, 0777, true);
	                
	                }

					//subir foto al sevidor
                	$file_photo = basename($_FILES['profile_photo']['name']);
	                $ruta_photo = '../../tso.maxlexgeo.com/media/'.$folder_photo.'/'.$file_photo;

	                //copiar al servidor
	                if(copy($_FILES['profile_photo']['tmp_name'],$ruta_photo)){

	                	//actualizar info del usuario
	                	$user_photo_data = ['photo' => $folder_photo.'/'.$file_photo];
	                	$query = $db->table('transport_user')
	                				->where('id', $get_user->user)
									->where('email', $request['email'])
									->where('phone', $request['phone'])
									->update($user_photo_data);

						//permisos de la foto cargada
						chmod('../../tso.maxlexgeo.com/media/'.$folder_photo.'/'.$file_photo, 0777);

	                	//borrar foto anterior del usuario
						if($get_user->user_photo != 'None/no-img.webp'){
	                		
	                		unlink('../../tso.maxlexgeo.com/media/'.$get_user->user_photo);

	                	}
	                	
	                	return $query;

	                }else{

	                	throw new ExceptionApi('error', 'upload photo error', 405);

	                }
				
				}else{

					throw new ExceptionApi('error', 'user not found', 406);

				}

			}

		}else{

			throw new ExceptionApi('error', 'profile photo is null', 404);

		}

	}

	/**
	* funciones privadas del modelo
	*/

	//verificar existencia del email
	private function user_check($request){
		
		global $db;
		$query = $db->select('
						id AS user,
						photo AS user_photo')
					->table('transport_user')
					->where('email',$request['email'])
					->where('phone',$request['phone'])
					->get();

		if ($db->numRows() > 0) {
			
			return $query;

		}else{

			return false;

		}

	}

}