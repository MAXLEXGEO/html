<?php

/**
* controlador del conductor
*/

//require del modelo
require 'models/DriverModel.php';

class Driver{
    
    //recibe los datos para consultar el perfil del conductor
    public static function get($request){

        if ($request == 'profile') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::driver_profile($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para actualizar la calificacion del conductor
    public static function put($request){
        
        if ($request == 'update_rate') {
            
            if (Auth::authorize()) {

                return self::update_rate();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener el perfil del conductor
    private static function driver_profile($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $driverModel = new DriverModel();

            //obtener el perfil del conductor
            $driver_profile = $driverModel -> driver_profile($params);

            //obtener la calificacion del conductor
            $driver_rate = $driverModel -> driver_rate($params);

            //obtener comentarios del conductor
            $driver_comments = $driverModel -> driver_comments($params);

            //array de respuesta
            $response   = array(
                   'driver_profile'  => $driver_profile,
                   'driver_rate'     => $driver_rate,
                   'driver_comments' => $driver_comments
            );

            //verifica la consulta
            if (!is_null($driver_profile)) {
                
                return ['status' => 'success', 'driver' => $response];

            }else{
                
                throw new ExceptionApi('error', 'error in drivers list', 400);
            
            }

        }

    }

    //actualizar calificacion del conductor
    private static function update_rate(){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $driverModel = new DriverModel();

            //actualizar calificacion
            $update_rate = $driverModel -> update_rate($request);

            //verifica la actualizacion
            if ($update_rate) {
                
                //array de respuesta
                $response = array('travel' => 'driver rating updated');

                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{
                
                throw new ExceptionApi('error', 'error updating driver rating', 400);
            
            }

        }

    }

}