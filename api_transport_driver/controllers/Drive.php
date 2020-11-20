<?php

/**
* controlador del conductor cuando esta "En Servicio"
*/

//require del modelo
require 'models/DriveModel.php';

class Drive{

    //recibe los datos para cambiar el status del conductor
    public static function put($request){
        
        if ($request == 'ondrive') {
            
            if (Auth::authorize()) {

                return self::ondrive();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //conductor en servicio
    private static function ondrive(){
        
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $driveModel = new DriveModel();

            //cambiar status En Servicio
            $ondrive_status = $driveModel -> ondrive_status($request);

            //valida el status
            if ($ondrive_status) {
                
                $response = array('driver' => 'change ondrive status successful');
                
                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{

                throw new ExceptionApi('error', 'change ondrive status failed', 400);
               
            }

        }

    }

}