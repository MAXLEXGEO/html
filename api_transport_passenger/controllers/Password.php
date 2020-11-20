<?php

/**
* controlador de password del usuario
*/

//require del modelo
require 'models/PasswordModel.php';
//validador
require_once 'utilities/Validator.php';

class Password{
    
    //recibe los datos para verificar la contraseña
    public static function get($request){
        
        if ($request == 'check') {
            
            if (Auth::authorize()) {

                return self::password_check();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para la actualizacion de los datos del usuario
    public static function put($request){
        
        if ($request == 'update') {
        
            if (Auth::authorize()) {
        
                return self::password_update();
        
            }
        
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //verificar contraseña
    private static function password_check(){
        
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
                'email'    => ['required'  => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'    => ['required'  => true, 'minlength' => 1, 'maxlength' => 15],
                'password' => ['required'  => true, 'minlength' => 4, 'maxlength' => 254]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $passModel = new PasswordModel();

                //registro del usuario
                $check_pass = $passModel -> check_pass($request);

                //arreglo de respuesta
                $response = array('user' => 'match password');
                    
                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }

        }

    }

    //actualizar la contraseña
    public function password_update(){
        
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
                'email'        => ['required'  => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'        => ['required'  => true, 'minlength' => 1, 'maxlength' => 15],
                'new_password' => ['required'  => true, 'minlength' => 4, 'maxlength' => 254]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $passModel = new PasswordModel();

                //actualizar la contraseña
                $pass_update = $passModel -> password_update($request);

                //array de respuesta
                $response = array('user' => 'password updated successful');

                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }

        }

    }

}   