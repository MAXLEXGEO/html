<?php

/**
* controlador del viaje
*/

//require del modelo
require 'models/TravelModel.php';
//validador
require_once 'utilities/Validator.php';

class Travel{

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

    //recibe los datos para que el conductor acepte el viaje
    public static function post($request){
        
        if ($request == 'accept') {
            
            if (Auth::authorize()) {

                return self::accept_travel();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para que el conductor inicia el viaje
    public static function put($request){
        
        if ($request == 'start') {
            
            if (Auth::authorize()) {

                return self::start_travel();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para finalizar el viaje
    public static function delete($request){
        
        if ($request == 'end') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::end_travel($params);
                
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

    //aceptar el viaje
    private static function accept_travel(){

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
                'travel'           => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'travel_passenger' => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'driver_email'     => ['required' => true, 'minlength' => 5, 'maxlength' => 100],
                'driver_phone'     => ['required' => true, 'minlength' => 5, 'maxlength' => 15]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $travelModel = new TravelModel();

                //update del viaje - aceptar
                $travel_accept = $travelModel -> travel_accept($request);

                //verifica el update
                if (!is_null($travel_accept)) {
                    
                    //array de respuesta
                    $response = array('travel' => 'travel accepted successful');

                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in accepting travel', 400);
                
                }

            }

        }

    }

    //aceptar el viaje
    private static function start_travel(){

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
                'travel'           => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'travel_passenger' => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'driver_email'     => ['required' => true, 'minlength' => 5, 'maxlength' => 100],
                'driver_phone'     => ['required' => true, 'minlength' => 5, 'maxlength' => 15]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $travelModel = new TravelModel();

                //update del viajer - aceptar
                $travel_start = $travelModel -> travel_start($request);

                //verifica el update
                if (!is_null($travel_start)) {
                    
                    //array de respuesta
                    $response = array('travel' => 'travel start successful');

                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in accepting travel', 400);
                
                }
                
            }
    
        }

    }

    //aceptar el viaje
    private static function end_travel($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //inicio del validador
            $validator = new Validator();

            //validar los datos
            $validation = $validator->check(    
            $params,[
                'travel'           => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'travel_passenger' => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'driver_email'     => ['required' => true, 'minlength' => 5, 'maxlength' => 100],
                'driver_phone'     => ['required' => true, 'minlength' => 5, 'maxlength' => 15]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $travelModel = new TravelModel();
                
                //finalizar el viaje
                $travel_finalize = $travelModel -> travel_finalize($params);

                //borrar el trackeo del viaje
                $travel_clean_track = $travelModel -> travel_clean($params);

                //verifica el update
                if (!is_null($travel_finalize)) {
                    
                    //array de respuesta
                    $response = array('travel' => 'travel finalized successful');

                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in finalize travel', 400);
                
                }

            }

        }

    }
    
}