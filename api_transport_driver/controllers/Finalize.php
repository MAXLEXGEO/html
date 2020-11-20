<?php

/**
* controlador del viaje
*/

//require del modelo
require 'models/FinalizeModel.php';
//validador
require_once 'utilities/Validator.php';

class Finalize{

    //recibe los datos para finalizar el viaje - antes de llegar al destino
    public static function delete($request){
        
        if ($request == 'end') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::end_travel_transit($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //finalizar el viaje antes de llegar al destino
    private static function end_travel_transit($params){

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
                'travel_code'      => ['required' => true, 'minlength' => 5, 'maxlength' => 5],
                'driver_email'     => ['required' => true, 'minlength' => 5, 'maxlength' => 100],
                'driver_phone'     => ['required' => true, 'minlength' => 5, 'maxlength' => 15]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $finalizeModel = new FinalizeModel();


                //verificar codigo del viaje
                if($finalizeModel -> check_travel_code($params)){

                    //finalizar el viaje
                    $travel_finalize = $finalizeModel -> travel_finalize($params);

                    //borrar el trackeo del viaje
                    $travel_clean_track = $finalizeModel -> travel_clean($params);

                    //verifica el update
                    if (!is_null($travel_finalize)) {
                        
                        //array de respuesta
                        $response = array('travel' => 'travel finalized successful');

                        //envia la respuesta
                        return ['status' => 'success', 'data' => $response];

                    }else{
                        
                        throw new ExceptionApi('error', 'error in finalize travel', 400);
                    
                    }

                }else{

                    throw new ExceptionApi('error', 'error in travel code', 400);

                }

            }

        }

    }
    
}