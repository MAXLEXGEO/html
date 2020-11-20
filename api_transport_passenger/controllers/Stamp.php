<?php

/**
* controlador de las facturas del viaje
*/

//require del modelo
require 'models/StampModel.php';
//validador
require_once 'utilities/Validator.php';

class Stamp{
    
    //recibe los datos para consultar si hay un perfil de facturacion con la forma de pago indicada
    public static function get($request){
        
        if ($request == 'check') {
            
            if (Auth::authorize()) {

                return self::get_invoice_profile();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para emitir la factura del viaje
    public static function post($request){
        
        if ($request == 'stamp_receipt') {
            
            if (Auth::authorize()) {

                return self::stamp_receipt();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //detalles del perfil de facturacion
    private static function get_invoice_profile(){

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
                'travel'       => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'payment_form' => ['required' => true, 'minlength' => 1, 'maxlength' => 1]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $stampModel = new StampModel();

                //obtener el perfil de facturacion
                $stamp_invoice_profile = $stampModel -> stamp_invoice_profile($request);

                //verifica la consulta
                if (!is_null($stamp_invoice_profile)) {
                    
                    //envia la respuesta
                    return ['status' => 'success', 'invoice_profile' => $stamp_invoice_profile];

                }else{
                    
                    throw new ExceptionApi('error', 'error in consulting receipt details', 400);
                
                }

            }

        }

    }

    //facturar el recibo del viaje
    private static function stamp_receipt(){

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
                'travel'  => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'receipt' => ['required' => true, 'minlength' => 32, 'maxlength' => 32]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $stampModel = new StampModel();

                //verificar que esta en tiempo para timbrar factura
                if($stampModel -> travel_check_time($request)){

                    //envia la respuesta
                    return ['status' => 'success', 'stamp_receipt' => 'stamp receipt successful'];

                }

            }

        }

    }

}