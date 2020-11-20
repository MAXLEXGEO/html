<?php
    //VALIDAR SESION
    include 'header.php';

    //FUCIONES DEL PROYECTO
    require_once 'assets/scripts/php/functions/f_cupones.php';
    
    //VALIDA LA LISTA DE CUPONES SI ES UN ADMIN EL QUE ESTA EN SESIÓN
    if($user_data['rol'] == "1"){
        //LISTA DE CUPONES
        $empresa = '';
    }else{
        //LISTA DE CUPONES
        $empresa = $user_data['empresa'];
    }

    //LISTA DE CUPONES
    $cupones = get_cupones_vw($empresa);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de cuponeras">
    <meta name="author" content="Mirsha ROjas - MAXLEXGEO">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>QPonext | Cupones</title>
    <!-- This page plugin CSS -->
    <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!--<div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>-->
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <? include $menu ?>
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Cupones</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">QPonext</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Cupones</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- basic table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-primary text-center">
                                                <h1 class="font-light text-white"><?=get_totales($empresa)['total_cupones']?></h1>
                                                <h6 class="text-white">Total Cupones</h6>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-secondary text-center">
                                                <h1 class="font-light text-white"><?=get_totales($empresa)['total_canjeados']?></h1>
                                                <h6 class="text-white">Canjeados</h6>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-success text-center">
                                                <h1 class="font-light text-white"><?=get_totales($empresa)['total_vigentes']?></h1>
                                                <h6 class="text-white">Vigentes</h6>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-danger text-center">
                                                <h1 class="font-light text-white"><?=get_totales($empresa)['total_caducados']?></h1>
                                                <h6 class="text-white">Caducados</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Disponibles</th>
                                                <th class="text-center">Empresa</th>
                                                <th data-orderable="false" class="text-center">Cuponera</th>
                                                <th data-orderable="false" class="text-center">Título</th>
                                                <th data-orderable="false" class="text-center">Código</th>
                                                <th data-orderable="false" class="text-center">Descripción</th>
                                                <th data-orderable="false" class="text-center">Restricciones</th>
                                                <th class="text-center">Descuento</th>
                                                <th class="text-center">Último Canjeado</th>
                                                <th data-orderable="false"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <? foreach($cupones AS $cupon){ ?>
                                            <tr>
                                                <td class="text-center"><?=$cupon['disponibles']?></td>
                                                <td><?=$cupon['empresa']?></td>
                                                <td class="text-center"><?=$cupon['cuponera']?></td>
                                                <td><?=$cupon['titulo']?></td>
                                                <td class="text-center"><?=$cupon['codigo_cupon']?></td>
                                                <td><?=$cupon['descripcion']?></td>
                                                <td><?=$cupon['restricciones']?></td>
                                                <td class="text-center"><?=get_descuento($cupon['descuento'],$cupon['tipo_descuento'])?></td>
                                                <td class="text-center"><?=$cupon['fecha_canjeo']?></td>
                                                <td class="text-center"><?=get_status($cupon['status_cupon'])?></td>
                                            </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center text-muted">
                All Rights Reserved by Q-Ponext. Designed and Developed by <a
                    href="https://www.maxlexgeo.com" target="_blank">MAXLEX GEO</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!--This page plugins -->
    <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>