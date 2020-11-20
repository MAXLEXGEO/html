<?php
    //INVOCAR EL ARCHIVO DE CONEXION
    require_once 'assets/scripts/php/connection.php';
    require_once 'assets/scripts/php/functions/f_login.php';
    $db = new mysqlDB;
    
    //SE EVALUA LA VARIABLE DE SESION
    session_start();

    //EN CASO DE HABER UNA SESION INICIADA REDIRECCIONA DIRECTAMENTE AL PANEL
    if ((isset($_SESSION['user_data'])) && (is_array($_SESSION['user_data']))) { header("Location: cupones.php"); die();}
    
    //RECIBE LA INFORMACION DEL FORMULARIO Y COMPRUEBA QUE NO VENGAN VACIAS
    if( !empty($_POST['username']) && !empty($_POST['password']) ){

        //VARIABLE CON EL VALOR TRAIDO DEL FORMULARIO
        $usuario  = $db->real_escape(strip_tags($_POST['username'],ENT_QUOTES));
        $password = $db->real_escape(strip_tags($_POST['password'],ENT_QUOTES));

        //INICIA LA SESION DEL USUARIO
        $response = user_login($usuario,$password);
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de cuponeras">
    <meta name="author" content="Mirsha Rojas - MAXLEXGEO">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Adminmart Template - The Ultimate Multipurpose admin template</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- This page plugin CSS -->
    <link rel="stylesheet" href="assets/extra-libs/jquery-validation/demo/css/screen.css">
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(assets/images/big/3.jpg);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="assets/images/big/icon.png" class="img-fluid">
                        </div>
                        <h2 class="mt-3 text-center">Iniciar Sesión</h2>
                        <p class="text-center">Ingresa tus datos de acceso</p>
                        <form method="post" action="index.php" class="mt-4" id="login_form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="username">Usuario</label>
                                        <input class="form-control" id="username" name="username" type="text" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Contraseña</label>
                                        <input class="form-control" id="password" name="password" type="password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-info">Entrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
    <script>
        $(".preloader ").fadeOut();
        var form = $('#login_form');
        form.validate();
    </script>
</body>

</html>