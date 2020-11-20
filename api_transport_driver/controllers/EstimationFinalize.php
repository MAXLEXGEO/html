<?php

/**
* controlador del cotizador de viajes
*/

//require del modelo
require 'models/EstimationFinalizeModel.php';
//validador
require_once 'utilities/Validator.php';

class EstimationFinalize{
    
    //recibe los datos para hacer el presupuesto del viaje
    public static function get($request){
        
        if ($request == 'travel_estimation_finalize') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::travel_estimation_finalize($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener presupuesto del viaje
    private static function travel_estimation_finalize($params){

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
                'travel'          => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'travel_distance' => ['required' => true, 'minlength' => 1, 'maxlength' => 10],
                'travel_date'     => ['required' => true],
                'cost_km'         => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'cost_min'        => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'region'          => ['maxlength' => 32]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{                

                //instanciar el modelo
                $estimationModel = new EstimationFinalizeModel();

                //obterner estimacion del viaje - costo
                $estimate = $estimationModel -> travel_estimate_finalize($params);

                //verifica la consulta
                if (!is_null($estimate)) {
                    
                    return ['status' => 'success', 'estimate' => $estimate];

                }else{
                    
                    throw new ExceptionApi('error', 'error in get travel estimation details', 400);
                
                }

            }

        }

    }

}