<?php

/**
* controlador de la foto de usuario
*/

//require del modelo
require 'models/PhotoJsonModel.php';
//validador
require_once 'utilities/Validator.php';

class PhotoJson{
    
    //recibe los datos para el registro de usuario
    public static function post($request){
        
        if ($request == 'upload_profile_photo_json') {

            if (Auth::authorize()) {
            
                return self::upload_profile_photo_json();
            }
            
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registro de usuario
    private static function upload_profile_photo_json(){
        
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
            
            throw new ExceptionApi('error', 'invalid request body', 401);
        
        }else{

            //inicio del validador
            $validator = new Validator();

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'email'         => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'         => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'profile_photo' => ['required' => true]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 402);
            
            }else{

                //instanciar el modelo
                $photojsonModel = new PhotoJSonModel();

                //subir imagen y validar la carga de la imagen
                if ($photojsonModel -> photo_upload_json($request)) {

                    $response = array(
                        'user' =>  'photo upload successful'
                    );
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'photo upload failed', 403);
                   
                }

            }

        }

    }

}   