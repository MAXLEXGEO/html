<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-brand">
                <!-- Logo icon -->
                <a href="index.html">
                    <!-- Logo text -->
                    <span class="logo-text">
                        <!-- dark Logo text -->
                        <img src="assets/images/logo.png" alt="homepage" class="dark-logo" width="160" />
                        <!-- Light Logo text -->
                        <img src="assets/images/logo.png" class="light-logo" alt="homepage" width="160" />
                    </span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                    class="ti-more"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img src="assets/images/users/profile-pic.jpg" alt="user" class="rounded-circle"
                            width="40">
                        <span class="ml-2 d-none d-lg-inline-block"><span>Hola,</span> <span
                                class="text-dark"><?=$user_data['nombre'].' '.$user_data['apellidos']?></span> <i data-feather="chevron-down"
                                class="svg-icon"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <!--<a class="dropdown-item" href="javascript:void(0)"><i data-feather="user"
                                class="svg-icon mr-2 ml-1"></i>
                            My Profile</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="credit-card"
                                class="svg-icon mr-2 ml-1"></i>
                            My Balance</a>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="mail"
                                class="svg-icon mr-2 ml-1"></i>
                            Inbox</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0)"><i data-feather="settings"
                                class="svg-icon mr-2 ml-1"></i>
                            Account Setting</a>
                        <div class="dropdown-divider"></div>-->
                        <a class="dropdown-item" href="logout.php"><i data-feather="power"
                                class="svg-icon mr-2 ml-1"></i>
                            Salir</a>
                        <!--<div class="dropdown-divider"></div>
                        <div class="pl-4 p-3"><a href="javascript:void(0)" class="btn btn-sm btn-info">View
                                Profile</a></div>-->
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                
                <li class="sidebar-item"> <a class="sidebar-link" href="cupones.php"
                        aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                            class="hide-menu">Cupones
                        </span></a>

                <li class="sidebar-item"> <a class="sidebar-link" href="cupones_canjear.php"
                        aria-expanded="false"><i data-feather="maximize" class="feather-icon"></i><span
                            class="hide-menu">Canjear
                        </span></a>

                <li class="sidebar-item <?=$active_menu?>"> <a class="sidebar-link" href="cuponeras.php"
                        aria-expanded="false"><i data-feather="layers" class="feather-icon"></i><span
                            class="hide-menu">Cuponeras
                        </span></a>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link" href="empresas.php"
                        aria-expanded="false"><i data-feather="briefcase" class="feather-icon"></i><span
                            class="hide-menu">Empresas
                        </span></a>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link" href="usuarios.php"
                        aria-expanded="false"><i data-feather="user" class="feather-icon"></i><span
                            class="hide-menu">Usuarios
                        </span></a>
                </li>
                
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="logout.php"
                        aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                            class="hide-menu">Salir</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->