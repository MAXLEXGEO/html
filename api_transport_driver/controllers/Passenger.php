<?php

/**
* controlador de los pasajeros
*/

//require del modelo
require 'models/PassengerModel.php';
//validador
require_once 'utilities/Validator.php';

class Passenger{

    //recibe los datos para consultar el perfil del pasajero
    public static function get($request){
        
        if ($request == 'profile') {
            
            if (Auth::authorize()) {

                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::passenger_profile($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para actualizar la calificacion del pasajero - de un viaje ya finalizado
    public static function put($request){
        
        if ($request == 'update_rate') {
            
            if (Auth::authorize()) {

                return self::update_rate();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener el perfil del pasajero
    private static function passenger_profile($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $passengerModel = new PassengerModel();

            //obtener el perfil del pasajero
            $passenger_profile = $passengerModel -> passenger_profile($params);            

            //obtener la calificacion del pasajero
            $passenger_rate = $passengerModel -> passenger_rate($params);

            //obtener comentarios del pasajero
            $passenger_comments = $passengerModel -> passenger_comments($params);

            //array de respuesta
            $response   = array(
                   'passenger_profile'  => $passenger_profile,
                   'passenger_rate'     => $passenger_rate,
                   'passenger_comments' => $passenger_comments
            );

            //verifica la consulta
            if (!is_null($passenger_profile)) {
                
                return ['status' => 'success', 'passenger' => $response];

            }else{
                
                throw new ExceptionApi('error', 'error in passenger profile', 400);
            
            }

        }

    }

    //actualizar calificacion del pasajero
    private static function update_rate(){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $passengerModel = new PassengerModel();

            //actualizar calificacion
            $update_rate = $passengerModel -> update_rate($request);

            //verifica la actualizacion
            if ($update_rate) {
                
                //array de respuesta
                $response = array('travel' => 'passenger rating updated');

                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{
                
                throw new ExceptionApi('error', 'error updating passenger rating', 400);
            
            }

        }

    }

}   