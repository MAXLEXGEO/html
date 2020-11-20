<?php

/**
* controlador del recibo del viaje
*/

//require del modelo
require 'models/ReceiptModel.php';
//validador
require_once 'utilities/Validator.php';

class Receipt{
    
    //recibe los datos para hacer el registro del recibo del viaje
    public static function post($request){
        
        if ($request == 'travel_receipt') {
            
            if (Auth::authorize()) {

                return self::travel_receipt_register();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para consultar el recibo del viaje
    public static function get($request){
        
        if ($request == 'receipt_details') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::travel_receipt_details($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para pagar el recibo del viaje
    public static function put($request){
        
        if ($request == 'receipt_payment') {
            
            if (Auth::authorize()) {

                return self::travel_receipt_payment($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registrar el recibo del viaje
    private static function travel_receipt_register(){

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
                'travel' => ['required' => true, 'minlength' => 32, 'maxlength' => 32]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $receiptModel = new ReceiptModel();

                //registro del recibo del viaje
                $receipt_travel = $receiptModel -> receipt_register($request);

                //verifica el registro
                if ($receipt_travel) {
                    
                    //array de respuesta
                    $response = array('receipt' => 'receipt travel registered');

                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in receipt register', 400);
                
                }

            }

        }

    }

    //detalles del recibo del viaje
    private static function travel_receipt_details($params){

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
                'travel' => ['required' => true, 'minlength' => 32, 'maxlength' => 32]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $receiptModel = new ReceiptModel();

                //detalles del recibo del viaje
                $travel_receipt = $receiptModel -> receipt_details($params);

                //impuestos/cuotas del recibo
                $receipt_taxes = $receiptModel -> receipt_taxes($params);

                //verifica la consulta
                if (!is_null($travel_receipt) || !is_null($receipt_taxes)) {
                    
                    //array de respuesta
                    $response   = array(
                           'receipt' => $travel_receipt,
                           'taxes'   => $receipt_taxes
                    );

                    //envia la respuesta
                    return ['status' => 'success', 'receipt' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in consulting receipt details', 400);
                
                }

            }

        }

    }

    //pago del recibo del viaje
    private static function travel_receipt_payment(){

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
                'travel' => ['required' => true, 'minlength' => 32, 'maxlength' => 32]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $receiptModel = new ReceiptModel();

                //pagar el recibo del viaje
                $travel_payment = $receiptModel -> receipt_payment($request);

                //verifica la consulta
                if ($travel_payment) {
                    
                    $response = array('travel' => 'travel payed successful');
                
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in receipt payment', 400);
                
                }

            }

        }

    }
}