<?php
  //ACTIVE - MENU
  $almacen_menu       = 'active open';
  $almacen_categorias = 'active is-shown';
?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Cake Admin System">
    <meta name="author" content="Alejandro Mirsha Rojas Calvo | alikey01@gmail.com">
    
    <title>Almacen | Categorías</title>
    
    <link rel="apple-touch-icon" href="../assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../global/css/bootstrap.min.css">
    <link rel="stylesheet" href="../global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="../assets/css/site.css"><!-- IMPORTANT -->
    
    <!-- Plugins -->
    <link rel="stylesheet" href="../global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="../global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="../global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="../global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="../global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="../global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="../global/vendor/waves/waves.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-bs4/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css">
        <link rel="stylesheet" href="../assets/examples/css/tables/datatable.css">
        <link rel="stylesheet" href="../global/vendor/webui-popover/webui-popover.css">
        <link rel="stylesheet" href="../global/vendor/toolbar/toolbar.css">
        <link rel="stylesheet" href="../assets/examples/css/uikit/modals.css">
        <link rel="stylesheet" href="../global/vendor/toastr/toastr.css">
        <link rel="stylesheet" href="../assets/examples/css/advanced/toastr.css">
        <link rel="stylesheet" href="../global/extra-libs/jquery-validation/demo/css/screen.css">
    
    <!-- Fonts -->
        <link rel="stylesheet" href="../global/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="../global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="../global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    
    <!-- Scripts -->
    <script src="../global/vendor/breakpoints/breakpoints.js"></script>
    <script>
      Breakpoints();
    </script>
  </head>
  <body class="animsition dashboard">

    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega bg-blue-300" role="navigation">
    
      <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
          data-toggle="menubar">
          <span class="sr-only">Toggle navigation</span>
          <span class="hamburger-bar"></span>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
          <img class="navbar-brand-logo img-fluid" src="../assets/images/logo.png" title="Cake Admin">
          <span class="navbar-brand-text hidden-xs-down"> Toky's</span>
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
          data-toggle="collapse">
          <span class="sr-only">Toggle Search</span>
          <i class="icon md-search" aria-hidden="true"></i>
        </button>
      </div>
    
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
          <!-- Navbar Toolbar -->
          <ul class="nav navbar-toolbar">
            <li class="nav-item hidden-float" id="toggleMenubar">
              <a class="nav-link" data-toggle="menubar" href="#" role="button">
                <i class="icon hamburger hamburger-arrow-left">
                  <span class="sr-only">Toggle menubar</span>
                  <span class="hamburger-bar"></span>
                </i>
              </a>
            </li>
            <li class="nav-item hidden-float">
              <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"
                role="button">
                <span class="sr-only">Toggle Search</span>
              </a>
            </li>
          </ul>
          <!-- End Navbar Toolbar -->
        </div>
        <!-- End Navbar Collapse -->
    
        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
          <form role="search">
            <div class="form-group">
              <div class="input-search">
                <i class="input-search-icon md-search" aria-hidden="true"></i>
                <input type="text" class="form-control" name="site-search" placeholder="Buscar...">
                <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
                  data-toggle="collapse" aria-label="Close"></button>
              </div>
            </div>
          </form>
        </div>
        <!-- End Site Navbar Search -->
      </div>
    </nav>

    <? include '../assets/menu/admin-menu-almacen.php' ?>

    <!-- Page -->
    <div class="page">
      <div class="page-header">
        <h1 class="page-title blue-500">Categorías</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Almacén</a></li>
          <li class="breadcrumb-item active">Categorías</li>
        </ol>
        <div class="page-header-actions">
          <button class="btn btn-sm bg-blue-700 btn-round white" data-target="#modal_categoria_nueva" data-target-file="categorias_modal_nueva" data-toggle="modal">
            <i class="icon md-plus" aria-hidden="true"></i>
            <span class="hidden-sm-down">Nueva</span>
          </button>
        </div>
      </div>

      <div class="page-content">
        <!-- Panel Basic -->
        <div class="panel">
          <header class="panel-heading">
            <h3 class="panel-title"></h3>
          </header>
          <div class="panel-body" id="tabla_categorias">
          </div>
        </div>
        <!-- End Panel Basic -->
      </div>
    </div>
    <!-- End Page -->

    <!-- Modal - Agregar categoria -->
    <div class="modal fade example-modal-sm" id="modal_categoria_nueva" aria-hidden="true" aria-labelledby="modal_categoria_nueva" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-simple modal-sm" id="modal_categoria_nueva_content">
      </div>
    </div>
    <!-- End Modal -->

    <!-- Modal - Editar categoria -->
    <div class="modal fade example-modal-sm" id="modal_categoria_editar" aria-hidden="true" aria-labelledby="modal_categoria_editar" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-simple modal-sm" id="modal_categoria_editar_content">
      </div>
    </div>
    <!-- End Modal -->

    <!-- Footer -->
    <footer class="site-footer">
      <div class="site-footer-legal">© 2020 <a href="http://www.tokys.com">Toky's Admin</a></div>
      <div class="site-footer-right">
        by <a href="https://www.maxlexgeo.com/" target="_blank">MAXLEX GEO</a>
      </div>
    </footer>

    <!-- Core  -->
    <script src="../global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script src="../global/vendor/jquery/jquery.js"></script>
    <script src="../global/vendor/popper-js/umd/popper.min.js"></script>
    <script src="../global/vendor/bootstrap/bootstrap.js"></script>
    <script src="../global/vendor/animsition/animsition.js"></script>
    <script src="../global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="../global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script src="../global/vendor/asscrollable/jquery-asScrollable.js"></script>
    <script src="../global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>
    <script src="../global/vendor/waves/waves.js"></script>
    
    <!-- Plugins -->
    <script src="../global/vendor/switchery/switchery.js"></script>
    <script src="../global/vendor/intro-js/intro.js"></script>
    <script src="../global/vendor/screenfull/screenfull.js"></script>
    <script src="../global/vendor/slidepanel/jquery-slidePanel.js"></script>
        <script src="../global/vendor/datatables.net/jquery.dataTables.js"></script>
        <script src="../global/vendor/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="../global/vendor/datatables.net-fixedheader/dataTables.fixedHeader.js"></script>
        <script src="../global/vendor/datatables.net-fixedcolumns/dataTables.fixedColumns.js"></script>
        <script src="../global/vendor/datatables.net-rowgroup/dataTables.rowGroup.js"></script>
        <script src="../global/vendor/datatables.net-scroller/dataTables.scroller.js"></script>
        <script src="../global/vendor/datatables.net-responsive/dataTables.responsive.js"></script>
        <script src="../global/vendor/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
        <script src="../global/vendor/datatables.net-buttons/dataTables.buttons.js"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.html5.js"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.flash.js"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.print.js"></script>
        <script src="../global/vendor/datatables.net-buttons/buttons.colVis.js"></script>
        <script src="../global/vendor/datatables.net-buttons-bs4/buttons.bootstrap4.js"></script>
        <script src="../global/vendor/asrange/jquery-asRange.min.js"></script>
        <script src="../global/vendor/bootbox/bootbox.js"></script>
        <script src="../global/vendor/webui-popover/jquery.webui-popover.min.js"></script>
        <script src="../global/vendor/toolbar/jquery.toolbar.js"></script>
        <script src="../global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
        <script src="../global/vendor/toastr/toastr.js"></script>
    
    <!-- Scripts -->
    <script src="../global/js/Component.js"></script>
    <script src="../global/js/Plugin.js"></script>
    <script src="../global/js/Base.js"></script>
    <script src="../global/js/Config.js"></script>
    
    <script src="../assets/js/Section/Menubar.js"></script>
    <script src="../assets/js/Section/GridMenu.js"></script>
    <script src="../assets/js/Section/Sidebar.js"></script>
    <script src="../assets/js/Section/PageAside.js"></script>
    <script src="../assets/js/Plugin/menu.js"></script>
    
    <script src="../global/js/config/colors.js"></script>
    <script src="../assets/js/config/tour.js"></script>
    <script>Config.set('assets', '../assets');</script>
    
    <!-- Page -->
    <script src="../assets/js/Site.js"></script>
    <script src="../global/js/Plugin/asscrollable.js"></script>
    <script src="../global/js/Plugin/slidepanel.js"></script>
    <script src="../global/js/Plugin/switchery.js"></script>
        <script src="../global/js/Plugin/datatables.js"></script>
        <script src="../global/js/Plugin/webui-popover.js"></script>
        <script src="../global/js/Plugin/toolbar.js"></script>
        <script src="../global/js/Plugin/toastr.js"></script>
        <script src="../assets/examples/js/tables/datatable.js"></script>
        <script src="../assets/examples/js/uikit/icon.js"></script>
        <script src="../assets/examples/js/uikit/tooltip-popover.js"></script>
        <script src="../global/js/Plugin/jquery-placeholder.js"></script>
        <script src="../global/js/Plugin/material.js"></script>
        <script src="../global/extra-libs/jquery-validation/dist/jquery.validate.js"></script>
        <script src="../global/js/Plugin/input-group-file.js"></script>
    <!-- Functions -->
    <script src="../assets/scripts/js/f_categorias.js"></script>
  </body>
</html>