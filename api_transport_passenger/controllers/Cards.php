<?php

/**
* controlador de tarjetas de credito/debito
*/

//require del modelo
require 'models/CardsModel.php';
//validador
require_once 'utilities/Validator.php';

class Cards{
    
    //recibe los datos para consultar las tarjetas del usuario
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

    //recibe los datos para registrar las tarjetas del usuario
    public static function post($request){
        
        if ($request == 'register') {
            
            if (Auth::authorize()) {

                return self::register();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para actualizar las tarjetas del usuario
    public static function put($request){
        
        if ($request == 'update') {
            
            if (Auth::authorize()) {

                return self::update();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //recibe los datos para borrar las tarjetas del usuario
    public static function delete($request){
        
        if ($request == 'delete') {
            
            if (Auth::authorize()) {

                return self::delete_card();
                
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
                $cardsModel = new CardsModel();

                //lista de tarjetas
                $cards_list = $cardsModel -> cards_list($params);

                //verifica la consulta
                if (!is_null($cards_list)) {
                    
                    return ['status' => 'success', 'cards' => $cards_list];

                }else{
                    
                    throw new ExceptionApi('error', 'error in cards list', 400);
                
                }
            
            }

        }

    }

    //registro de tarjetas
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

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'email'           => ['required' => true, 'minlength' => 1, 'maxlength' => 254],
                'phone'           => ['required' => true, 'minlength' => 1, 'maxlength' => 15],
                'holder_name'     => ['required' => true, 'minlength' => 5, 'maxlength' => 150],
                'holder_lastname' => ['required' => true, 'minlength' => 5, 'maxlength' => 150],
                'card_number'     => ['required' => true, 'minlength' => 16, 'maxlength' => 16],
                'ex_month'        => ['required' => true, 'minlength' => 2, 'maxlength' => 2],
                'exp_year'        => ['required' => true, 'minlength' => 2, 'maxlength' => 4],
                'cvv'             => ['required' => true, 'minlength' => 3, 'maxlength' => 4]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $cardsModel = new CardsModel();

                //registrar tarjetas
                $cards_register = $cardsModel -> cards_register($request);

                //valida el registro
                if ($cards_register) {
                    
                    $response = array(
                        'user' => 'card register successful'
                    );
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'card register failed', 400);
                   
                }

            }

        }

    }

    //actualizar tarjetas
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

            //validar los datos
            $validation = $validator->check(    
            $request,[
                'card'     => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'ex_month' => ['required' => true, 'minlength' => 2, 'maxlength' => 2],
                'exp_year' => ['required' => true, 'minlength' => 2, 'maxlength' => 4],
                'cvv'      => ['required' => true, 'minlength' => 3, 'maxlength' => 4]
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $cardsModel = new CardsModel();

                //actualizar tarjetas
                $cards_update = $cardsModel -> cards_update($request);

                //valida la actualizacion
                if ($cards_update) {
                    
                    $response = array(
                        'user' => 'card updated successful'
                    );
                    
                    //envia la respuesta
                    return ['status' => 'success', 'data' => $response];

                }else{

                    throw new ExceptionApi('error', 'card update failed', 400);
                   
                }

            }

        }

    }

    //borrar tarjetas del usuario
    private static function delete_card(){
        
        $response = [];
        $request  = json_decode(file_get_contents('php://input'));

        //verifica el cuerpo de la peticion
        if (is_null($request)) {
        
            throw new ExceptionApi('error', 'invalid request body', 400);
        
        }else{

            //instanciar el modelo
            $cardsModel = new CardsModel();

            //borrar tarjetas
            $cards_delete = $cardsModel -> cards_delete($request);

            //valida el borrado
            if ($cards_delete) {
                
                $response = array(
                    'user' => 'card delete successful'
                );
                
                //envia la respuesta
                return ['status' => 'success', 'data' => $response];

            }else{

                throw new ExceptionApi('error', 'card delete failed', 400);
               
            }

        }

    }

}