<?php

/**
* controlador de perfil de facturación
*/

//require del modelo
require 'models/InvoiceModel.php';
//validador
require_once 'utilities/Validator.php';

class Invoice{
    
    //recibe los datos para consultar los perfiles de facturacion del usuario
    public static function get($request){
        
        if ($request == 'list') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::list($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para registrar los perfiles de facturacion del usuario
    public static function post($request){
        
        if ($request == 'register') {
            
            if (Auth::authorize()) {

                return self::register();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para actualizar los perfiles de facturacion
    public static function put($request){
        
        if ($request == 'update') {
            
            if (Auth::authorize()) {

                return self::update();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para borrar los perfiles de facturacion
    public static function delete($request){

        if ($request == 'delete') {
            
            if (Auth::authorize()) {

                //covertir parametros
                $params = [];
                parse_str($_SERVER['QUERY_STRING'], $params);

                return self::delete_invoice($params);
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //obtener tarjetas de credito
    private static function list($params){

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
                'email' => ['required'  => true, 'minlength' => 1, 'maxlength' => 254],
                'phone' => ['required'  => true, 'minlength' => 1, 'maxlength' => 15]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $invoiceModel = new InvoiceModel();

                //lista de perfiles de facturacion
                $invoice_list = $invoiceModel -> invoice_list($params);

                //verifica la consulta
                if (!is_null($invoice_list)) {
                    
                    return ['status' => 'success', 'invoice_profiles' => $invoice_list];

                }else{
                    
                    throw new ExceptionApi('error', 'error in cards list', 400);
                
                }
            
            }

        }

    }

    //registro de perfiles de facturacion
    private static function register(){
        
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //inicio del validador
            $validator = new Validator();

            //verifica si viene una cuenta de email alterna
            if($request->email_alt){ $email_validation = true; } else { $email_validation = false; }
            
            //validar los datos
            $validation = $validator->check(    
            $request,[
                'email'            => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'            => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'name'             => ['required' => true, 'minlength' => 4, 'maxlength' => 50],
                'rfc'              => ['required' => true, 'minlength' => 12, 'maxlength' => 13, 'rfc' => true],
                'razon'            => ['required' => true, 'minlength' => 4, 'maxlength' => 150],
                'calle'            => ['required' => true, 'minlength' => 2, 'maxlength' => 100],
                'num_ext'          => ['required' => true, 'minlength' => 1, 'maxlength' => 10],
                'num_int'          => ['maxlength' => 20],
                'colonia'          => ['required' => true, 'minlength' => 2, 'maxlength' => 100],
                'CP'               => ['required' => true, 'minlength' => 4, 'maxlength' => 10, 'cp' => true],
                'municipio'        => ['required' => true, 'minlength' => 4, 'maxlength' => 100],
                'ciudad'           => ['required' => true, 'minlength' => 4, 'maxlength' => 100],
                'estado'           => ['required' => true, 'minlength' => 4, 'maxlength' => 50],
                'email_alt'        => ['maxlength' => 150, 'email' => $email_validation],
                'forma_pago'       => ['required' => true, 'minlength' => 1, 'maxlength' => 1],
                'uso_cfdi'         => ['required' => true, 'minlength' => 3, 'maxlength' => 3],
                'tipo_facturacion' => ['required' => true, 'minlength' => 1, 'maxlength' => 1]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $invoiceModel = new InvoiceModel();

                //registrar perfiles de facturacion
                $invoice_register = $invoiceModel -> invoice_register($request);

                //valida el registro
                if ($invoice_register) {
                    
                    $response = array('user' => 'invoice profile register successful');
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    //return ['status' => 'error', 'message' => 'invoice profile register failed or already exist'];
                    throw new ExceptionApi('error', 'invoice profile register failed', 400);
                   
                }

            }

        }

    }

    //actualizar perfiles de facturacion
    public function update(){
        
        //arreglo de respuesta y cuerpo de la peticion
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //inicio del validador
            $validator = new Validator();

            //verifica si viene una cuenta de email alterna
            if($request->email_alt){ $email_validation = true; } else { $email_validation = false; }

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'email'            => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'            => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'name'             => ['required' => true, 'minlength' => 4, 'maxlength' => 50],
                'rfc'              => ['required' => true, 'minlength' => 12, 'maxlength' => 13, 'rfc' => true],
                'razon'            => ['required' => true, 'minlength' => 4, 'maxlength' => 150],
                'calle'            => ['required' => true, 'minlength' => 2, 'maxlength' => 100],
                'num_ext'          => ['required' => true, 'minlength' => 1, 'maxlength' => 10],
                'num_int'          => ['maxlength' => 20],
                'colonia'          => ['required' => true, 'minlength' => 2, 'maxlength' => 100],
                'CP'               => ['required' => true, 'minlength' => 4, 'maxlength' => 10, 'cp' => true],
                'municipio'        => ['required' => true, 'minlength' => 4, 'maxlength' => 100],
                'ciudad'           => ['required' => true, 'minlength' => 4, 'maxlength' => 100],
                'estado'           => ['required' => true, 'minlength' => 4, 'maxlength' => 50],
                'email_alt'        => ['maxlength' => 150, 'email' => $email_validation],
                'forma_pago'       => ['required' => true, 'minlength' => 1, 'maxlength' => 1],
                'uso_cfdi'         => ['required' => true, 'minlength' => 3, 'maxlength' => 3],
                'tipo_facturacion' => ['required' => true, 'minlength' => 1, 'maxlength' => 1]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $invoiceModel = new InvoiceModel();

                //actualizar perfiles de facturacion
                $invoice_update = $invoiceModel -> invoice_update($request);

                //valida la actualizacion
                if ($invoice_update) {
                    
                    $response = array('user' => 'invoice profile updated successful');
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'invoice profile update failed', 400);
                   
                }

            }

        }

    }

    //borrar perfiles de facturacion
    private static function delete_invoice($params){
        
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($params)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $invoiceModel = new InvoiceModel();

            //borrar perfiles
            $invoice_delete = $invoiceModel -> invoice_delete($params);

            //valida el borrado
            if ($invoice_delete) {
                
                $response = array('user' => 'invoice profile delete successful');
                
                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{

                throw new ExceptionApi('error', 'invoice profile delete failed', 400);
               
            }

        }

    }

}