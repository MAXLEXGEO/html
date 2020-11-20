<?php
    //VALIDAR SESION
    include 'header.php';
    //FUCIONES DEL PROYECTO
    require_once 'assets/scripts/php/functions/f_cupones.php';
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
    <title>Q-Pones | Canjear cupón</title>
    <!-- This page plugin CSS -->
    <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/prism/prism.css">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- CSS Jquery-Validation-->
    <link rel="stylesheet" href="assets/extra-libs/jquery-validation/demo/css/screen.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  </head>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Canjear cupones</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">QPonext</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Canjear</li>
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
                <div id="app" class="row">
                    <div class="col-12 mt-4">
                        <div class="card-deck">
                            <div class="card">
                                <video class="card-img-top img-fluid" id="preview"></video>
                                <div class="card-body">
                                    <h4 class="card-title">Cámaras</h4>
                                    <ul class="list-style-none">
                                        <li v-if="cameras.length === 0" class="empty"><i class="fas fa-ban text-warning"></i>&nbsp;Ninguna camara detectada.</li>
                                    </ul>
                                    <ul class="list-style-none" v-for="camera in cameras">
                                        <li v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="text-info font-weight-bold">
                                            <i class="fa fa-check text-info"></i>&nbsp;<span>{{ formatName(camera.name) }}</span>
                                        </li>
                                        <li v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
                                           <a @click.stop="selectCamera(camera)"><i class="fas fa-chevron-right"></i>&nbsp;<span>{{ formatName(camera.name) }}</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-5 mt-4">
                        <div class="card-deck">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Cupones</h4>
                                    <section class="scans">
                                        <ul v-if="scans.length === 0">
                                            <li class="empty">No scans yet</li>
                                        </ul>
                                        <transition-group name="scans" tag="ul">
                                            <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
                                        </transition-group>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
                
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center text-muted">
                All Rights Reserved by Q-Pones. Designed and Developed by <a
                    href="https://www.maxlexgeo.com">MaxLexGeo</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>

        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                
                                <img class="card-img-top img-fluid" id="cupon_img" src="assets/images/cupones/default.jpg" alt="">
                                <div class="card-body text-center">
                                    <h1 class="card-title text-primary font-weight-bold" id="cupon_titulo">Título</h1>
                                    <p class="card-text" id="cupon_desc">Descripción</p>
                                    <p class="card-text font-weight-bold" id="cupon_codigo">Código</p>
                                    <p><small class="card-text text-muted" id="cupon_rest">Restricciones</small></p>
                                    <input type="hidden" name="id_cupon" id="id_cupon">
                                    <button type="button" id="cupon_btn_canjear" onclick="canjear_cupon(document.getElementById('id_cupon').value)" class="btn btn-rounded btn-success font-weight-bold">Canjear</button>
                                    <button type="button" id="cupon_btn_cerrar" class="btn btn-rounded btn-light" data-dismiss="modal">Cerrar</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <!-- themejs -->
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!--This page plugins -->
    <script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/pages/datatable/datatable-basic.init.js"></script>
    <script src="assets/extra-libs/prism/prism.js"></script>
    <script type="text/javascript" src="assets/extra-libs/qr_lector/docs/app.js"></script>
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
    <script src="assets/scripts/js/functions/f_cupones.js"></script>
  </body>
</html>