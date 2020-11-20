<?php
    //VALIDAR SESION
    include 'header.php';
    //FUCIONES DEL PROYECTO
    require_once 'assets/scripts/php/functions/f_empresas.php';
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
    <title>Q-Pones | Empresas</title>
    <!-- This page plugin CSS -->
    <link href="assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/prism/prism.css">
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
</head>

<body onload="get_empresas_table()">
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
        <? include 'menu.php' ?>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Empresas</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.html" class="text-muted">Q-Pones</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Empresas</li>
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
                <!-- Nueva Empresa -->
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 col-xlg-2">
                        <div class="card card-hover">
                            <button type="button" class="btn btn-primary btn-rounded ml-1" data-toggle="collapse" data-target="#add_empresa_collapse" aria-expanded="false" aria-controls="add_empresa_collapse"><i class="fas fa-plus"></i>&emsp;Nueva</button>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-hover collapse" id="add_empresa_collapse">
                            
                            <div class="card-body">
                                <h4 class="card-title text-info">Nueva Empresa</h4>
                                <form method="POST" id="add_empresa_form" enctype="multipart/form-data">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#empresa">Empresa</label>
                                                    <input id="empresa" name="empresa" type="text" class="form-control" placeholder="Nombre de la empresa o negocio" maxlength="100" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#domicilio">Domicilio</label>
                                                    <input id="domicilio" name="domicilio" type="text" class="form-control" placeholder="Domicilio de la empresa o negocio" maxlength="255" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#telefono">Teléfono</label>
                                                    <input id="telefono" name="telefono" type="text" class="form-control" placeholder="Teléfono de la empresa o negocio" maxlength="15" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#logo">Logotipo</label>
                                                    <input id="logo" name="logo" type="file" class="form-control" accept="image/jpeg,image/gif,image/png" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button type="button" id="btn_add_empresa" type="button" class="btn btn-rounded btn-success" onclick="add_empresa()">Registrar</button>
                                            <button id="btn_cancel_empresa" type="reset" class="btn btn-rounded btn-danger text-white" data-toggle="collapse" data-target="#add_empresa_collapse" aria-expanded="false" aria-controls="add_empresa_collapse">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla Empresa -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table_empresas" class="table table-striped table-bordered display no-wrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nombre</th>
                                                <th class="text-center" data-orderable="false">Domicilio</th>
                                                <th class="text-center" data-orderable="false">Teléfono</th>
                                                <th></th>
                                                <th data-orderable="false"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    href="https://www.rayosxvillanueva.com">MaxLexGeo</a>.
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
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
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
    <!-- Empresas Functions -->
    <script src="assets/scripts/js/functions/f_empresas.js"></script>
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
</body>

</html>