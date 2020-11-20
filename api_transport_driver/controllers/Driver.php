<?php

/**
* controlador del conductor
*/

//require del modelo
require 'models/DriverModel.php';
//validador
require_once 'utilities/Validator.php';

class Driver{
    
    //recibe los datos para el registro del conductor
    public static function post($request){
        
        if ($request == 'register') {
            
            return self::register();
        
        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registro del conductor
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
                'first_name' => ['required' => true, 'minlength' => 1, 'maxlength' => 50],
                'last_name'  => ['required' => true, 'minlength' => 1, 'maxlength' => 50],
                'email'      => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'      => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'password'   => ['required' => true, 'minlength' => 4, 'maxlength' => 254],
                'car_plate'  => ['required' => true, 'minlength' => 3, 'maxlength' => 20]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $driverModel = new DriverModel();

                //registro del usuario
                $driver_register = $driverModel -> driver_register($request);
 
               //valida el registro del conductor
                if ($driver_register) {

                    $response = array(
                        'user' =>  'driver register successful'
                    );
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'driver register failed', 400);
                   
                }

            }

        }

    }

}   