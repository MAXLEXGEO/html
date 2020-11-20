<?php

/**
* controlador del trackeo del viaje
*/

//require del modelo
require 'models/TrackModel.php';
//validador
require_once 'utilities/Validator.php';

class Track{

    //recibe los datos para registrar el tracking del viaje
    public static function post($request){
        
        if ($request == 'tracking_travel') {
            
            if (Auth::authorize()) {

                return self::tracking_travel();
                
            }

        } else {
        
            throw new ExceptionApi('error processing request', 'malformed url', 400);
        
        }
    
    }

    //registro del tracking del viaje
    private static function tracking_travel(){
        
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
                'travel'            => ['required' => true, 'minlength' => 32, 'maxlength' => 32],
                'tracking_lat'      => ['required' => true, 'minlength' => 7, 'maxlength' => 32],
                'tracking_long'     => ['required' => true, 'minlength' => 7, 'maxlength' => 32],
                'tracking_distance' => ['required' => true, 'minlength' => 1, 'maxlength' => 32],
                'tracking_date'     => ['required' => true, 'minlength' => 10, 'maxlength' => 25]                
                ]
            );

            //si hay errores en la validacion los muestra
            if ($validation->fails()) {
            
                throw new ExceptionApi('error', $validation->errors()->all() , 400);
            
            }else{

                //instanciar el modelo
                $trackModel = new TrackModel();

                //obtener los detalles del viaje
                $travel_details = $trackModel->get_travel_details($request);

                //distancia estimada del viaje y distancia recorrida
                $tracking_distance = $request->tracking_distance;
                $travel_distance   = $travel_details->travel_distance;

                //fecha/hora de partida del viaje y del tracking
                $travel_start_date = new DateTime($travel_details->travel_start);
                $travel_track_date = new DateTime($request->tracking_date);
                
                //obtener tiempo de recorrido en minutos
                $interval           = date_diff($travel_start_date, $travel_track_date);
                $tracking_duration += $interval->h * 60;
                $tracking_duration += $interval->i;

                //tiempo estimado del viaje
                $travel_duration = $travel_details->travel_duration;

                //indicar que se actualizo el costo del viaje
                $travel_cost_recalculate = 'F';

                ###--------------------------------------------------------------------------------------------------------###
                #--------- verifica si el recorrido se ha extendido mas de lo estimado y se recalcula el costo --------------#
                ###--------------------------------------------------------------------------------------------------------###

                //en caso de que se extienda mas la distancia
                if($tracking_distance > $travel_distance){

                    //verifica si tambien se extendio del tiempo estimado
                    if($tracking_duration > $travel_duration){

                        //asignar la nueva duracion del viaje
                        $travel_new_duration = $tracking_duration;
                    
                    }else{

                        //asignar la nueva duracion del viaje
                        $travel_new_duration = $travel_duration;
                    }

                    //asignar la nueva distancia del viaje
                    $travel_new_distance = $tracking_distance;

                    //recalcular el costo en tiempo real
                    $travel_new_subtotal = (($travel_new_distance * $travel_details->cost_km)+($travel_new_duration * $travel_details->cost_min));

                    //actualizar el costo del viaje
                    $travel_cost_update = $trackModel -> travel_cost_update($request,$travel_new_distance,$travel_new_duration,$travel_new_subtotal);

                    //indicar que se actualizo el costo del viaje
                    $travel_cost_recalculate = 'T';

                }

                //en caso de que se extienda mas el tiempo del viaje
                if($tracking_duration > $travel_duration){

                    //verifica si tambien se extendio de la distancia estimada
                    if($tracking_distance > $travel_distance){

                        //asignar la nueva distancia del viaje
                        $travel_new_distance = $tracking_distance;
                    
                    }else{

                        //asignar la nueva distancia del viaje
                        $travel_new_distance = $travel_distance;
                    }

                    //asignar la nueva duracion del viaje
                    $travel_new_duration = $tracking_duration;

                    //recalcular el costo en tiempo real
                    $travel_new_subtotal = (($travel_new_distance * $travel_details->cost_km)+($travel_new_duration * $travel_details->cost_min));
                    
                    //actualizar el costo del viaje
                    $travel_cost_update = $trackModel -> travel_cost_update($request,$travel_new_distance,$travel_new_duration,$travel_new_subtotal);

                    //indicar que se actualizo el costo del viaje
                    $travel_cost_recalculate = 'T';
                }

                ###--------------------------------------------------------------------------------------------------------###
                #------------------------------------------------------------------------------------------------------------#
                ###--------------------------------------------------------------------------------------------------------###

                //registro del trackeo del viaje
                $tracking_register = $trackModel -> tracking_register($request);

                //obtener costos del viaje actualizados
                $travel_new_costs = $trackModel->get_travel_new_costs($request);

                //verifica el registro
                if ($tracking_register) {

                    $response = array(
                            'travel' =>  'tracking update successful',
                            'travel_cost_update' => $travel_cost_recalculate,
                            'travel_distance' => $travel_new_costs->distance,
                            'travel_cost' => $travel_new_costs->cost,
                            'travel_total' => $travel_new_costs->cost_total);
                    
                    //envia la respuesta
                    return ['status' => 'success', 'travel' => $response];

                }else{
                    
                    throw new ExceptionApi('error', 'error in updating tracking', 400);
                
                }

            }

        }

    }

}