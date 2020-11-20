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
#--------------- pasajero - usuario ----#
require 'controllers/User.php';
require 'controllers/Password.php';
require 'controllers/Cards.php';
require 'controllers/Invoice.php';
#--------------- conductor -------------#
require 'controllers/Drivers.php';
require 'controllers/Driver.php';
#--------------- viajes ----------------#
require 'controllers/Travels.php';
require 'controllers/Travel.php';
require 'controllers/Estimation.php';
require 'controllers/Receipt.php';
require 'controllers/Region.php';
require 'controllers/Track.php';
require 'controllers/Rate.php';
//******************************************************//


//************* conexion PDO ***************************//
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
