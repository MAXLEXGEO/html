<?php

/**
* controlador del viaje
*/

//require del modelo
require 'models/TravelModel.php';
//validador
require_once 'utilities/Validator.php';

class Travel{

    //recibe los datos para registrar el viaje - Pedir viaje
    public static function post($request){
        
        if ($request == 'new') {
            
            if (Auth::authorize()) {

                return self::new();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }
    
    //recibe los datos para consultar los detalles del viaje
    public static function get($request){
        
        if ($request == 'details') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::travel_details($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para registrar el viaje - Pedir viaje
    public static function put($request){
        
        if ($request == 'cancel') {
            
            if (Auth::authorize()) {

                return self::cancel();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener detalles del viaje
    private static function travel_details($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $travelModel = new TravelModel();

            //detalles del viaje
            $travel_details = $travelModel -> travel_details($params);

            //verifica la consulta
            if (!is_null($travel_details)) {
                
                return ['status' => 'success', 'travel' => $travel_details];

            }else{
                
                throw new ExceptionApi('error', 'error in consulting details', 400);
            
            }

        }

    }

    //registro del viaje 
    private static function new(){

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
                'email'             => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'             => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'driver'            => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'region'            => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'start_address'     => ['required' => true, 'minlength' => 10],
                'start_lat'         => ['required' => true, 'minlength' => 7, 'maxlength' => 21],
                'start_long'        => ['required' => true, 'minlength' => 7, 'maxlength' => 21],
                'suggested_address' => ['required' => true, 'minlength' => 10],
                'suggested_lat'     => ['required' => true, 'minlength' => 7, 'maxlength' => 21],
                'suggested_long'    => ['required' => true, 'minlength' => 7, 'maxlength' => 21],
                'travel_cost'       => ['required' => true, 'minlength' => 1, 'maxlength' => 10],
                'travel_distance'   => ['required' => true, 'minlength' => 1, 'maxlength' => 50],
                'travel_duration'   => ['required' => true, 'minlength' => 1, 'maxlength' => 50]
                
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{                

                //instanciar el modelo
                $travelModel = new TravelModel();

                //registro del viaje
                $travel_register = $travelModel -> travel_register($request);

                //verifica la consulta
                if (!is_null($travel_register)) {
                    
                    return ['status' => 'success', 'travel' => md5($travel_register)];

                }else{
                    
                    throw new ExceptionApi('error', 'error in consulting details', 400);
                
                }

            }

        }

    }

    //registro del viaje 
    private static function cancel(){

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
                'travel' => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'email'  => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'  => ['required' => true, 'minlength' => 1, 'maxlength' => 15]
                
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{                

                //instanciar el modelo
                $travelModel = new TravelModel();

                //cancelar viaje
                $travel_cancel = $travelModel -> travel_cancel($request);

                //verifica la consulta
                if ($travel_cancel) {
                    
                    $response = array('travel' => 'travel canceled successful');
                
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in consulting details', 400);
                
                }

            }

        }

    }

}