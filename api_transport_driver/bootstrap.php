<?php

/**
* archivo de configuracion de la api
*/

//************* funciones y librerias ******************//
require 'database/Connection.php';
require 'database/QueryBuilder.php';
require 'utilities/ResponseJson.php';
require 'utilities/ExceptionApi.php';
//******************************************************//

//************* controladores **************************//
#--------------- sesion ----------------#
require 'controllers/Auth.php';
require 'controllers/Pin.php';
#--------------- conductor -------------#
require 'controllers/Drive.php';
require 'controllers/Driver.php';
require 'controllers/Photo.php';
#--------------- pasajeros -------------#
require 'controllers/Passenger.php';
#--------------- viajes ----------------#
require 'controllers/Travels.php';
require 'controllers/Travel.php';
require 'controllers/EstimationFinalize.php';
require 'controllers/Finalize.php';
require 'controllers/Rate.php';
require 'controllers/Region.php';
require 'controllers/Receipt.php';
//******************************************************//


//************* conexion PDO ***************************//
/*$pdo  = Connection::instance();
$conn = $pdo->getConnection(
    'pgsql:host=localhost;dbname=TRANSPORT;port=5432',
    'postgres',
    'maxlex'
);*/
$pdo  = Connection::instance();
$conn = $pdo->getConnection(
    'pgsql:host=157.245.92.36;dbname=transport_service;port=5432',
    'postgres',
    'L;kdd34C.)wvM2}^'
);
//******************************************************//

//************* constructor de consultas ***************//
return new QueryBuilder($conn);
//******************************************************//