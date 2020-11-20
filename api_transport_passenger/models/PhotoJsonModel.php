<?php

date_default_timezone_set('America/Mexico_City');

/**
* modelo del controlador de usuario
*/

class PhotoJsonModel
{
	//cargar la imagen
	function photo_upload_json($request){
		
		global $db;

		if(isset($request->profile_photo)){

			//if ($_FILES['profile_photo']['size'] > 10000000) {

                //throw new ExceptionApi('error', 'profile photo size is too bigger', 400);

			//}else{

				//verificar usuario
				$get_user = PhotoJsonModel::user_check($request);

				if($get_user != false){

					//borrar foto anterior del usuario
					if($get_user->user_photo != 'None/no-img.webp'){
                		
                		unlink('../../tso.maxlexgeo.com/media/'.$get_user->user_photo);

                	}

					//ruta de la foto
					$folder_photo = 'Users/'.date('Y').'/'.date('m').'/'.date('d');

					//crear ruta para la foto
	                if (!file_exists('./fotos/'.$folder_photo)){
	                    
	                	mkdir('../../tso.maxlexgeo.com/media/'.$folder_photo, 0777, true);
	                
	                }

	                // Obtain the original content (usually binary data)
					$bin = base64_decode($request->profile_photo);

					// Load GD resource from binary data
					$size = getImageSizeFromString($bin);

					// Check the MIME type to be sure that the binary data is an image
					if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
					  throw new ExceptionApi('error', 'empty profile_photo', 400);
					}

					// Mime types are represented as image/gif, image/png, image/jpeg, and so on
					// Therefore, to extract the image extension, we subtract everything after the “image/” prefix
					$ext = substr($size['mime'], 6);

					// Make sure that you save only the desired file extensions
					if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
					  die('Unsupported image type');
					}

					// Specify the location where you want to save the image
					$img_file = '../../tso.maxlexgeo.com/media/'.$folder_photo.'/user_'.$request->phone.'.'.$ext;

					// Save binary data as raw data (that is, it will not remove metadata or invalid contents)
					// In this case, the PHP backdoor will be stored on the server
					//copiar al servidor
	                if(file_put_contents($img_file, $bin)){

	                	//actualizar info del usuario
	                	$user_photo_data = ['photo' => $folder_photo.'/user_'.$request->phone.'.'.$ext];
	                	$query = $db->table('transport_user')
	                				->where('id', $get_user->user)
									->where('email', $request->email)
									->where('phone', $request->phone)
									->update($user_photo_data);

						//permisos de la foto cargada
						chmod('../../tso.maxlexgeo.com/media/'.$folder_photo.'/user_'.$request->phone.'.'.$ext, 0777);
	                	
	                	return $query;

	                }else{

	                	throw new ExceptionApi('error', 'upload photo error', 405);

	                }
				
				}else{

					throw new ExceptionApi('error', 'user not found', 406);

				}

			//}

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
					->where('email',$request->email)
					->where('phone',$request->phone)
					->get();

		if ($db->numRows() > 0) {
			
			return $query;

		}else{

			return false;

		}

	}

}