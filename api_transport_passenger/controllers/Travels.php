<?php

/**
* controlador de los viajes (realizados,cancelados)
*/

//require del modelo
require 'models/TravelsModel.php';

class Travels{
    
    //recibe los datos para consultar los viajes del usuario
    public static function get($request){
        
        if ($request == 'travels_list') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::travels_list($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener los viajes
    private static function travels_list($params){

        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $travelsModel = new TravelsModel();

            //lista de viajes del conductor
            $travels_list = $travelsModel -> travels_list($params);

            //verifica la consulta
            if (!is_null($travels_list)) {
                
                return ['status' => 'success', 'travels' => $travels_list];

            }else{
                
                throw new ExceptionApi('error', 'error in travels list', 400);
            
            }

        }

    }

}