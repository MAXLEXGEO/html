<?php

/**
* controlador de de usuarios
*/

//require del modelo
require 'models/UserModel.php';
//validador
require_once 'utilities/Validator.php';

class User{
    
    //recibe los datos para el registro de usuario
    public static function post($request){
        
        if ($request == 'register') {
            
            return self::register();
        
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para la actualizacion de los datos del usuario
    public static function put($request){
        
        if ($request == 'update') {
        
            if (Auth::authorize()) {
        
                return self::update();
        
            }
        
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registro de usuario
    private static function register(){
        
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
                'first_name' => ['required'  => true, 'minlength' => 1, 'maxlength' => 50],
                'last_name'  => ['required'  => true, 'minlength' => 1, 'maxlength' => 50],
                'email'      => ['required'  => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'      => ['required'  => true, 'minlength' => 1, 'maxlength' => 15],
                'password'   => ['required'  => true, 'minlength' => 4, 'maxlength' => 254]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $userModel = new UserModel();

                //registro del usuario
                $user_register = $userModel -> user_register($request);

                //valida el registro de usuario
                if ($user_register) {

                    $response = array(
                        'user' =>  'user register successful'
                    );
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'user register failed', 400);
                   
                }

            }

        }

    }

    //actualizar el usuario
    public function update(){
        
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //inicio del validador
            $validator = new Validator();

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'first_name' => ['required'  => true, 'minlength' => 1, 'maxlength' => 50],
                'last_name'  => ['required'  => true, 'minlength' => 1, 'maxlength' => 50]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $userModel = new UserModel();

                //actualizar datos del usuario
                $user_update = $userModel -> user_update($request);
                
                //verifica la actualizacion
                if ($user_update) {
                    
                    //array de respuesta
                    $response = array(
                        'user' => 'user info updated'
                    );

                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error updating user data', 400);
                
                }

            }

        }

    }

}   