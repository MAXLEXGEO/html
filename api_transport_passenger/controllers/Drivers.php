<?php

/**
* controlador de los conductores
*/

//require del modelo
require 'models/DriversModel.php';

class Drivers{
    
    //recibe los datos para consultar conductores en linea
    public static function get($request){
        
        if ($request == 'drivers_online') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::drivers_online($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener a los conductores en linea
    private static function drivers_online($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        //$request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $driversModel = new DriversModel();

            //lista de conductores online
            $drivers_online = $driversModel -> drivers_online($params);

            //verifica la consulta
            if (!is_null($drivers_online)) {
                
                return ['status' => 'success', 'drivers_online' => $drivers_online];

            }else{
                
                throw new ExceptionApi('error', 'error in drivers list', 400);
            
            }

        }

    }

}