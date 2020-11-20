<?php

/**
* controlador de la foto de usuario
*/

//require del modelo
require 'models/PhotoModel.php';
//validador
require_once 'utilities/Validator.php';

class Photo{
    
    //recibe los datos para el registro de usuario
    public static function post($request){
        
        if ($request == 'upload_profile_photo') {

            if (Auth::authorize()) {
            
                return self::upload_profile_photo();
            }
            
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registro de usuario
    private static function upload_profile_photo(){
        
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = $_POST;
        //$request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
            
            throw new ExceptionApi('error', 'invalid request body', 401);
        
        }else{
            
            //inicio del validador
            $validator = new Validator();

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'email'         => ['required'  => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'         => ['required'  => true, 'minlength' => 1, 'maxlength' => 15],
                'profile_photo' => ['required' => true]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 402);
            
            }else{

                //instanciar el modelo
                $photoModel = new PhotoModel();

                //subir imagen y validar la carga de la imagen
                if ($photoModel -> photo_upload($request)) {

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