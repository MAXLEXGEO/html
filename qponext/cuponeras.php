<?php
    //VALIDAR SESION
    include 'header.php';
    //FUCIONES DEL PROYECTO
    require 'assets/scripts/php/functions/f_cuponeras.php';
    //LISTA EMPRESAS SELECT
    $empresas_select = get_empresas_select();
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
    <title>QPonext | Cuponeras</title>
    <!-- This page plugin CSS -->
    <link rel="stylesheet" type="text/css" href="assets/extra-libs/prism/prism.css">
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

<body onload="get_cuponeras_table();">
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Cuponeras</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.php" class="text-muted">Q-Pones</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Cuponeras</li>
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

                    <? if($user_data['rol'] == '1'){?>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 col-xlg-2">
                        <div class="card card-hover">
                            <button type="button" class="btn btn-primary btn-rounded ml-1" data-toggle="collapse" data-target="#add_cuponera_collapse" aria-expanded="false" aria-controls="add_cuponera_collapse"><i class="fas fa-plus"></i>&emsp;Nueva</button>
                        </div>
                    </div>
                    <? } ?>

                    <div class="col-12" id="add_cuponera_div">
                        <div class="card card-hover collapse" id="add_cuponera_collapse">
                            
                            <div class="card-body">
                                <h4 class="card-title text-info">Nueva Cuponera</h4>
                                <form action="#" method="POST" id="add_cuponera_form">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#empresa">Empresa</label>
                                                    <select name="empresa" class="selectpicker show-tick form-control" data-live-search="true" id="empresa" data-size="5" required>
                                                        <option value="">Empresa...</option>
                                                        <? foreach ($empresas_select AS $empresa){ ?>
                                                        <option value="<?=$empresa['id_empresa']?>"><?=$empresa['nombre']?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#codigo">Codigo</label>
                                                    <input id="codigo" name="codigo" type="text" class="form-control" placeholder="Codigo/Nombre de la Serie" maxlength="50" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#inicio">Fecha Inicio</label>
                                                    <input type="date" class="form-control" name="inicio" id="inicio" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="#caducidad">Fecha Caducidad</label>
                                                    <input type="date" class="form-control" name="caducidad" id="caducidad" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button id="btn_add_cuponera" type="button" class="btn btn-rounded btn-success" onclick="add_cuponera()">Registrar</button>
                                            <button id="btn_cancel_cuponera" type="reset" class="btn btn-rounded btn-danger text-white" data-toggle="collapse" data-target="#add_cuponera_collapse" aria-expanded="false" aria-controls="add_cuponera_collapse">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div id="add_cupon_div" class="col-12" style="display: none;">
                        <div class="card card-hover">
                            
                            <div class="card-body">
                                <h4 class="card-title text-info">Agregar Cupones</h4>
                                <form action="#" method="POST" id="add_cupon_form">
                                    <div class="form-body">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#titulo">Título</label>
                                                    <input id="titulo" name="titulo" type="text" class="form-control" placeholder="Título o nombre del cupón" maxlength="50" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="#codigo_cupon">Código</label>
                                                    <input id="codigo_cupon" name="codigo_cupon" type="text" class="form-control" placeholder="Codigo del Cupón" maxlength="50" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="#tipo_descuento">Tipo Descuento</label>
                                                    <select name="tipo_descuento" class="form-control selectpicker" id="tipo_descuento" required>
                                                        <option value="">Tipo descuento...</option>
                                                        <option value="PCT">%</option>
                                                        <option value="MND">$</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="#descuento">Descuento</label>
                                                    <input type="number" class="form-control" name="descuento" id="descuento" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="#disponibles">Num. Cupones</label>
                                                    <input type="number" class="form-control" name="disponibles" id="disponibles" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="#descripcion">Descripción</label>
                                                    <textarea class="form-control" rows="2" placeholder="Descripción del cupón..." name="descripcion" id="descripcion" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="#restricciones">Restricciones</label>
                                                    <textarea class="form-control" rows="2" placeholder="Restricciones del cupón..." name="restricciones" id="restricciones"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <input type="hidden" class="form-control" name="cuponera_id" id="cuponera_id">
                                            <button id="btn_add_cupon" type="button" class="btn btn-rounded btn-success" onclick="add_cupon()"><i class="fas fa-plus"></i>&emsp;Añadir</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body">
                                <h4 class="card-title text-info">Cupones</h4>
                                <div class="table-responsive">
                                    <table id="table_cupones" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Cupón</th>
                                                <th>Descuento</th>
                                                <th class="text-center">Descripción</th>
                                                <th class="text-center">Cuponera</th>
                                                <th class="text-center">Empresa</th>
                                                <th class="text-center">Disponibles</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="text-right">
                                        <br>
                                        <button id="btn_done_cupon" type="button" class="btn btn-rounded btn-danger" onclick="done_cuponera()">Finalizar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="table_cuponeras" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Cuponera</th>
                                                <th>Empresa</th>
                                                <th class="text-center">Inicio</th>
                                                <th class="text-center">Caducidad</th>
                                                <th class="text-center">No. Cupones</th>
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
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center text-muted">
                All Rights Reserved by QPonext. Designed and Developed by <a href="https://www.rayosxvillanueva.com" target="_blank">MAXLEX GEO</a>.
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
    <script src="assets/extra-libs/prism/prism.js"></script>
    <!-- Empresas Functions -->
    <script src="assets/scripts/js/functions/f_cuponeras.js"></script>
    <script src="assets/scripts/js/functions/f_cupones.js"></script>
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
    <script src="assets/extra-libs/bootstrap-select/dist/js/bootstrap-select.js"></script>
</body>

</html>