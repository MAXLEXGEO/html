<?php
    //error_reporting(E_ALL);
    //VALIDAR SESION
    include 'header.php';
    //FUCIONES DEL PROYECTO
    require 'assets/scripts/php/functions/f_cuponeras.php';
    //RECIBE ID DE LA CUPONERA
    $cuponera = $_REQUEST['cuponera'];
    $cuponera_url = sha1($cuponera);
    //DETALLES CUPONERA
    $cuponera_detalles = get_cuponera_detalles($cuponera);
    $cupon_col         = get_cupones_col($cuponera);
    //LISTA CUPONES
    $lista_cupones = get_cupones_per($cuponera);
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
    <title>QPonext | Personalizar</title>
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
    <!-- CSS Jquery-Validation-->
    <link rel="stylesheet" href="assets/extra-libs/jquery-validation/demo/css/screen.css">
    <!-- CSS Bootstrap Select -->
    <link rel="stylesheet" href="assets/extra-libs/bootstrap-select/dist/css/bootstrap-select.css">
</head>

<!--<body onload="get_cuponeras_table();">-->
<body>
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
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <?
            $active_menu = 'active selected';
            include $menu ?>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Personalizar Cuponera</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">QPonext</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Cuponeras</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <div class="text-center mt-2 mb-4">
                            <img src="assets/images/empresa-logo/<?=$cuponera_detalles['empresa_logo']?>" width="120">
                        </div>
                        <h3 class="card-title"><?=$cuponera_detalles['empresa']?> | <span class="text-muted"><?=$cuponera_detalles['codigo']?></span></h3>
                        <p>
                            <i class="icon-calender mr-2 text-info font-weight-bold"></i> Inicio: <?=$cuponera_detalles['fecha_inicio']?>&emsp;
                            <i class="icon-calender mr-2 text-info font-weight-bold"></i>Caducidad: <?=$cuponera_detalles['fecha_caducidad']?>
                        </p>
                        <a target="_new" href="http://www.facebook.com/sharer.php?u=https://maxlexgeo.com/qponext/cuponeras_view.php?cuponera=<?=$cuponera_url?>" data-toggle="tooltip" title="Compartir cuponera en Facebook" data-placement="left">
                            <button style="background-color: #4267B2; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-facebook-f"></i></button>
                        </a>

                        <a target="_new" href="https://twitter.com/share?url=https://maxlexgeo.com/qponext/cuponeras_view.php?cuponera=<?=$cuponera_url?>" data-toggle="tooltip" title="Compartir cuponera en Twitter" data-placement="top">
                            <button style="background-color: #1DA1F2; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-twitter"></i></button>
                        </a>

                        <a target="_new" href="https://api.whatsapp.com/send?phone=&amp;text=https://maxlexgeo.com/qponext/cuponeras_view.php?cuponera=<?=$cuponera_url?>&amp;source=&amp;data=" data-toggle="tooltip" title="Compartir cuponera en WhatsApp" data-placement="right">
                            <button style="background-color: #0CC243; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-whatsapp"></i></button>
                        </a>
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
                    <? foreach($lista_cupones AS $cupon){?>

                    <div class="<?=$cupon_col?> mt-4">
                        <div class="card-deck">
                            
                            <div class="card">
                                <button style="position: absolute;" type="button" class="btn btn-info btn-sm btn-rounded mt-2 ml-2" data-toggle="modal" data-target="#editar_cupon_modal" data-id="<?=$cupon['id_cupon']?>" data-target-file="cupones_editar.php">
                                    <i class="icon-pencil"></i> Editar
                                </button>
                                <img class="card-img-top img-fluid" src="assets/images/cupones/<?=$cupon['img']?>" alt="">
                                <img style="position: absolute;" class="img-fluid mt-5 ml-2" align="center" src="<?=$cupon['qr']?>" alt="" width="70">
                                <div class="card-body text-center">
                                    <h1 class="card-title text-primary font-weight-bold"><?=$cupon['titulo']?></h1>
                                    <p class="card-text"><?=$cupon['descripcion']?>.</p>
                                    <p class="card-text font-weight-bold">CÓDIGO: <?=$cupon['codigo_cupon']?></p>
                                    <p><small class="card-text text-muted"><?=$cupon['restricciones']?></small></p>
                                    
                                    <!--<a target="_new" href="http://www.facebook.com/sharer.php?u=https://leyendaslegendarias.com/e30-john-wayne-gacy/">
                                        <button style="background-color: #4267B2; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-facebook-f"></i></button>
                                    </a>

                                    <a target="_new" href="https://twitter.com/share?url=https://leyendaslegendarias.com/e30-john-wayne-gacy/&amp;text=Simple%20Share%20Buttons&amp;hashtags=simplesharebuttons">
                                        <button style="background-color: #1DA1F2; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-twitter"></i></button>
                                    </a>

                                    <a target="_new" href="https://api.whatsapp.com/send?phone=&amp;text=https://leyendaslegendarias.com/e30-john-wayne-gacy/&amp;source=&amp;data="><button style="background-color: #0CC243; border: none;" type="button" class="btn btn-secondary btn-circle"><i class="fab fa-whatsapp"></i></button></a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <? } ?>
                </div>

            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center text-muted">
                All Rights Reserved by QPonext. Designed and Developed by <a href="https://www.maxlexgeo.com" target="_blank">MAXLEX GEO</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <div id="editar_cupon_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
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
    <script src="assets/extra-libs/prism/prism.js"></script>
    <!-- Empresas Functions -->
    <script src="assets/scripts/js/functions/f_cuponeras.js"></script>
    <script src="assets/scripts/js/functions/f_cupones.js"></script>
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
    <script src="assets/extra-libs/bootstrap-select/dist/js/bootstrap-select.js"></script>
</body>

</html>