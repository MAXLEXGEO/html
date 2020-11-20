<?php

/**
* controlador de calificacion del viaje
*/

//require del modelo
require 'models/RateModel.php';

class Rate{

    //recibe los datos para calificar al pasajero al finalizar el viaje
    public static function put($request){
        
        if ($request == 'travel_rate') {
            
            if (Auth::authorize()) {

                return self::travel_rate();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //calificar al pasajero
    private static function travel_rate(){
        
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $rateModel = new RateModel();

            //calificacion del viaje - pasajero
            $rate_passenger = $rateModel -> rate_passenger($request);

            //valida el status
            if ($rate_passenger) {
                
                $response = array('rates' => 'rates register successful');
                
                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{

                throw new ExceptionApi('error', 'change ondrive status failed', 400);
               
            }

        }

    }

}