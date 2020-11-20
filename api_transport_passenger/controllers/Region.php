<?php

/**
* controlador de las regiones (donde hay disponibilidad del servicio)
*/

//require del modelo
require 'models/RegionModel.php';

class Region{
    
    //recibe los datos para consultar las regiones disponibles
    public static function get($request){
        
        if ($request == 'region_list') {
            
            if (Auth::authorize()) {
                
                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);
                
                return self::region_list($params);
                
            }

        } else {
        
            //return ['status' => 'error', 'message' => 'malformed url'];
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para obtener la region donde tiene cobertura
    public static function post($request){
        
        if ($request == 'coverage') {
            
            if (Auth::authorize()) {
                
                return self::region_coverage();
                
            }

        } else {
        
            //return ['status' => 'error', 'message' => 'malformed url'];
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener las regiones disponibles
    private static function region_list($params){
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
            
            //return ['status' => 'error', 'message' => 'invalid request body'];
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $regionModel = new RegionModel();

            //lista de regiones
            $region_list = $regionModel -> region_list($params);

            //verifica la consulta
            if (!is_null($region_list)) {
                
                return ['status' => 'success', 'region' => $region_list];

            }else{
                
                //return ['status' => 'error', 'message' => 'error in regions list'];
                throw new ExceptionApi('error', 'error in regions list', 400);
            
            }

        }

    }

    //obtener la region donde tiene cobertura
    private static function region_coverage(){

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
                'email' => ['required' => true, 'minlength' => 5, 'maxlength' => 100],
                'phone' => ['required' => true, 'minlength' => 5, 'maxlength' => 15],
                'lat'   => ['required' => true, 'minlength' => 3,],
                'long'  => ['required' => true, 'minlength' => 3,]
                ]
            );            

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $regionModel = new RegionModel();

                //obtener region
                $region_coverage = $regionModel -> region_coverage($request);

                //verifica la region
                if (!is_null($region_coverage)) {
                    
                    //envia la respuesta
                    return ['status' => 'success', 'region' => $region_coverage];

                }else{
                    
                    throw new ExceptionApi('error', 'error in accepting travel', 400);
                
                }

            }

        }

    }

}