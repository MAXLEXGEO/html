<?php
    //VALIDAR SESION
    include 'header.php';
    //FUCIONES DEL PROYECTO
    require_once 'assets/scripts/php/functions/f_usuarios.php';
    //LISTA ROLES SELECT
    $roles_select = get_roles_select($user_data['rol']);
    //LISTA EMPRESAS SELECT
    if($user_data['rol'] == '1'){
        $empresas_select = get_empresas_usuarios_select();
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
    <title>Q-Pones | Usuarios</title>
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
    <!-- CSS Bootstrap Select -->
    <link rel="stylesheet" href="assets/extra-libs/bootstrap-select/dist/css/bootstrap-select.css">
</head>

<body onload="get_usuarios_table()">
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Usuarios</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="index.html" class="text-muted">Q-Pones</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Usuarios</li>
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
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xlg-2">
                        <div class="card card-hover">
                            <button type="button" class="btn btn-primary btn-rounded ml-1" data-toggle="collapse" data-target="#add_usuario_collapse" aria-expanded="false" aria-controls="add_usuario_collapse"><i class="fas fa-plus"></i>&emsp;Nuevo usuario</button>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-hover collapse" id="add_usuario_collapse">
                            
                            <div class="card-body">
                                <h4 class="card-title text-info">Nuevo Usuario</h4>
                                <form method="POST" id="add_usuario_form">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#nombre">Nombre</label>
                                                    <input id="nombre" name="nombre" type="text" class="form-control" placeholder="Nombre" maxlength="150" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#apellidos">Apellidos</label>
                                                    <input id="apellidos" name="apellidos" type="text" class="form-control" placeholder="Apellidos" maxlength="150" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#email">E-mail</label>
                                                    <input id="email" name="email" type="email" class="form-control" placeholder="E-mail" maxlength="100" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#usuario">Usuario&nbsp;<i class="icon-question font-weight-bold text-info" data-toggle="tooltip" data-placement="top" title="Nombre de usuario con el que iniciará sesión"></i></label>
                                                    <input id="usuario" name="usuario" type="text" class="form-control" placeholder="Nombre de usuario" maxlength="50" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#password">Contraseña&nbsp;<i class="icon-question font-weight-bold text-info" data-toggle="tooltip" data-placement="top" title="Contraseña con la que iniciará sesión"></i></label>
                                                    <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña" maxlength="50" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#rol">Rol de usuario&nbsp;<i class="icon-question font-weight-bold text-info" data-toggle="tooltip" data-placement="top" title="Rol de usuario. Gerente: Puede canjear y ver el status de los cupones, asi como dar de alta a nuevos usuarios. Staff: únicamente puede canjear los cupones."></i></label>
                                                    <select name="rol" class="selectpicker show-tick form-control" data-live-search="true" id="rol" data-size="5" required>
                                                        <option value="">Rol...</option>
                                                        <? foreach ($roles_select AS $rol){ ?>
                                                        <option value="<?=$rol['id_rol']?>"><?=$rol['rol']?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <? if($user_data['rol'] == '1'){?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="#empresa">Empresa o negocio</label>
                                                    <select name="empresa" class="selectpicker show-tick form-control" data-live-search="true" id="empresa" data-size="5" required>
                                                        <option value="">Empresa...</option>
                                                        <? foreach ($empresas_select AS $empresa){ ?>
                                                        <option value="<?=$empresa['id_empresa']?>"><?=$empresa['nombre']?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <? }else{?>
                                            <input type="hidden" name="empresa" value="<?=$user_data['empresa']?>">
                                            <?}?>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button type="button" id="btn_add_usuario" type="button" class="btn btn-rounded btn-success" onclick="add_usuario()">Registrar</button>
                                            <button id="btn_cancel_usuario" type="reset" class="btn btn-rounded btn-danger text-white" data-toggle="collapse" data-target="#add_usuario_collapse" aria-expanded="false" aria-controls="add_usuario_collapse">Cancelar</button>
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
                                    <table id="table_usuarios" class="table table-striped table-bordered display no-wrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Usuario</th>
                                                <th class="text-center" data-orderable="false">Rol</th>
                                                <th class="text-center" data-orderable="false">E-mail</th>
                                                <th class="text-center" data-orderable="false">Login</th>
                                                <th></th>
                                                <!--<th data-orderable="false"></th>-->
                                                <!--<th data-orderable="false"></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!--<td></td>-->
                                                <!--<td></td>-->
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
                    href="https://www.maxlexgeo.com">MAXLEX GEO</a>.
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
    <script src="assets/scripts/js/functions/f_usuarios.js"></script>
    <script src="assets/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
    <script src="assets/extra-libs/bootstrap-select/dist/js/bootstrap-select.js"></script>
</body>

</html>