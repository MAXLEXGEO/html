<?php

/**
* index api
*/

// conexion y controladores/recursos
$db = require 'bootstrap.php';

// base url
const API_PATH = 'PATH_INFO';

// codigos de errores internos
const URL_NOT_SATISFIABLE      = 1000;
const RESOURCE_NOT_IMPLEMENTED = 1001;
const METHOD_NOT_ALLOWED       = 405;
const INTERNAL_SERVER_ERROR    = 500;

// respuesta de la API - JSON
$response = new ResponseJson;

// formato de respuesta - success - error
set_exception_handler(function ($exception) use ($response) {
    
    $body = [
        'status'  => $exception->status,
        'message' => $exception->getMessage()
    ];

    $response->status = ($exception->getCode()) ? $exception->getCode() : INTERNAL_SERVER_ERROR;
    $response->transform($body);

});

// manejo de urls y segmentos
if (isset($_GET[API_PATH])) {

    $request = explode('/', $_GET[API_PATH]);

} else {

    throw new ExceptionApi(URL_NOT_SATISFIABLE, 'the petition cannot be processed');
}

// obtener el controlador/recurso al que se apunta
$resource = array_shift($request);

//controladores/recursos de la API
$ourResources = ['auth','pin','user','password','cards','invoice','drivers','driver','travels','estimation','travel','rate','receipt','region','track'];

// verifica que exista el recurso/controlador
if (! in_array($resource, $ourResources)) {
    throw new ExceptionApi(
        RESOURCE_NOT_IMPLEMENTED, 
        'resource not available'
    );
}

// obtener el metodo de la peticion
$method = strtolower($_SERVER['REQUEST_METHOD']);

// verificar si el metodo de la petición lo permite la API
$allowedMethods = ['get', 'post', 'put', 'delete'];

if (! in_array($method, $allowedMethods)) {

    $response->status = METHOD_NOT_ALLOWED;
    $body = [
        'status'  => METHOD_NOT_ALLOWED,
        'message' => 'method not allowed'
    ];
    
    $response->transform($body);

} else {

    if (method_exists($resource, $method)) {
    
        $body = call_user_func_array([$resource, $method], $request);

        $response->transform($body);
    }

}