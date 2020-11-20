<?php
    //function contador()
    //{
        $archivo = "contador.txt"; //el archivo que contiene en numero
        $f = fopen($archivo, "r"); //abrimos el archivo en modo de lectura
        if($f)
        {
            $contador = fread($f, filesize($archivo)); //leemos el archivo
            $contador = $contador + 1; //sumamos +1 al contador
            fclose($f);
        }
        $f = fopen($archivo, "w+");
        if($f)
        {
            fwrite($f, $contador);
            fclose($f);
        }
        //return $contador;
    //}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Diseño y Desarrollo de Software | MAXLEX GEO</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="software,desarrollo,diseño,design,development,system,tics,negocio,business" name="keywords">
  <meta content="MAXLEX GEO Soluciones tecnologicas a tu medida" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,700|Roboto:300,400,700&display=swap"
    rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="vendor/line-awesome/css/line-awesome.min.css" rel="stylesheet">
  <link href="vendor/aos/aos.css" rel="stylesheet">
  <link href="vendor/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="css/style.css" rel="stylesheet">
  
</head>

<body>

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icofont-close js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar js-sticky-header site-navbar-target" role="banner">

      <div class="container">
        <div class="row align-items-center">

          <div class="col-6 col-lg-2">
            <h1 class="mb-0 site-logo"><a href="https://www.maxlexgeo.com" class="mb-0">MAXLEX GEO</a></h1>
          </div>

          <div class="col-12 col-md-10 d-none d-lg-block">
            <nav class="site-navigation position-relative text-right" role="navigation">

              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li class="active"><a href="https://www.maxlexgeo.com" class="nav-link">Inicio</a></li>
                <!--<li><a href="features.html" class="nav-link">Features</a></li>
                <li><a href="pricing.html" class="nav-link">Pricing</a></li>-->
                <li><a href="proyectos" class="nav-link">Proyectos</a></li>
                <li><a href="servicios" class="nav-link">Servicios</a></li>

                <!--<li class="has-children">
                  <a href="blog.html" class="nav-link">Blog</a>
                  <ul class="dropdown">
                    <li><a href="blog.html" class="nav-link">Blog</a></li>
                    <li><a href="blog-single.html" class="nav-link">Blog Sigle</a></li>
                  </ul>
                </li>-->
                <li><a href="contacto" class="nav-link">Contacto</a></li>
              </ul>
            </nav>
          </div>


          <div class="col-6 d-inline-block d-lg-none ml-md-0 py-3" style="position: relative; top: 3px;">

            <a href="#" class="burger site-menu-toggle js-menu-toggle" data-toggle="collapse"
              data-target="#main-navbar">
              <span></span>
            </a>
          </div>

        </div>
      </div>

    </header>


    <main id="main">
      <div class="hero-section">
        <div class="wave">

          <svg width="100%" height="355px" viewBox="0 0 1920 355" version="1.1" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
                <path
                  d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,757 L1017.15166,757 L0,757 L0,439.134243 Z"
                  id="Path"></path>
              </g>
            </g>
          </svg>

        </div>

        <div class="container">
          <div class="row align-items-center">
            <div class="col-12 hero-text-image">
              <div class="row">
                <div class="col-lg-7 text-center text-lg-left">
                  <h1 data-aos="fade-right">Software a medida</h1>
                  <p class="mb-5" data-aos="fade-right" data-aos-delay="100">Acercate a nosotros y haz despegar tus ideas</p>
                  <!--<p data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500"><a href="#"
                      class="btn btn-outline-white">Get started</a></p>-->
                </div>
                <div class="col-lg-5 iphone-wrap">
                  <img src="img/phone_1.png" alt="Image" class="phone-1" data-aos="fade-right">
                  <img src="img/phone_2.png" alt="Image" class="phone-2" data-aos="fade-up-right" data-aos-delay="130">
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="site-section">
        <div class="container">

          <div class="row justify-content-center text-center mb-5">
            <div class="col-md-10" data-aos="fade-up">
              <h2 class="section-heading">Aplicaciones a medida de tus necesidades</h2>
              <p>Desarrollamos aplicaciones siempre con el mismo objetivo... La experiencia del usuario.<br>Cada proyecto se lleva bajo estrictos estándares de calidad, haciendolos escalables, robustos y fáciles de usar.</p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="" alt="PHP">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon lab la-php"></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="100">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon icofont-brand-android-robot"></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="200">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon lab la-node-js"></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="300">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon lab la-python"></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="400">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon lab la-css3-alt"></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-delay="500">
              <div class="feature-1 text-center">
                <div class="wrap-icon icon-1">
                  <span class="icon la lab la-html5"></span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div> <!-- .site-section -->

      <div class="site-section pt-0">

        <div class="container">

          <div class="row justify-content-center text-center mb-5">
            <div class="col-md-8" data-aos="fade-up">
              <h2 class="section-heading">Enfocado a resultados</h2>
              <p>Trazamos el plan para llevar a cabo tu idea y/o resolver tus necesidades.</p>
            </div>
          </div>

        </div>

        <div class="container">
          <div class="row justify-content-center text-center mb-5" data-aos="fade-down">
            <div class="col-md-6 mb-5">
              <img src="img/plan.png" alt="Image" class="img-fluid">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="step">
                <span class="number">01</span>
                <h3>Diagnostico</h3>
                <p>Compartenos tus necesidades o idea para poder comprenderlas.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="step">
                <span class="number">02</span>
                <h3>Diseño</h3>
                <p>Elaboremos juntos la solución que cumpla con tu visión.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="step">
                <span class="number">03</span>
                <h3>Ejecución</h3>
                <p>Desarrollemos el producto final de manera agil y eficiente.</p>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- .site-section -->



      <div class="site-section">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-6 mr-auto" data-aos="fade-right">
              <h2 class="mb-4">Trabajemos juntos</h2>
              <p class="mb-4">Queremos conocer tu proyecto y llevarlo acabo juntos.</p>
              <a href="contacto">
                <button type="button" class="btn btn-primary d-block w-50">Cotiza tu proyecto</button>
              </a>
            </div>
            <div class="col-md-6" data-aos="fade-left">
              <img src="img/contact.png" alt="Image" class="img-fluid">
            </div>
          </div>
        </div>
      </div> <!-- .site-section -->

    </main>
    <footer class="footer cta-section" role="contentinfo">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4 mb-md-0">
            <h3 style="color: #FFF;">Sobre Nosotros</h3>
            <p>En MAXLEX GEO nos gusta tomar retos y hacer realidad la vision de nuestros clientes, para ello estamos capacitandonos constantemente.</p>
            <!--<p class="social">
              <a href="#"><span class="icofont-twitter"></span></a>
              <a href="#"><span class="icofont-facebook"></span></a>
              <a href="#"><span class="icofont-dribbble"></span></a>
              <a href="#"><span class="icofont-behance"></span></a>
            </p>-->
          </div>
          <div class="col-md-7 ml-auto">
            <div class="row site-section pt-0">
              <div class="col-md-4 mb-4 mb-md-0">
                <h3 style="color: #FFF;">Navegación</h3>
                <ul class="list-unstyled">
                  <li><a style="color: #FFF;" href="https://www.maxlexgeo.com">Inicio</a></li>
                  <li><a style="color: #FFF;" href="proyectos">Proyectos</a></li>
                  <li><a style="color: #FFF;" href="servicios">Servicios</a></li>
                  <li><a style="color: #FFF;" href="contacto">Contacto</a></li>
                </ul>
              </div>
              <div class="col-md-4 mb-4 mb-md-0">
                <h3 style="color: #FFF;">Contactanos</h3>
                <ul class="list-unstyled">
                  <li>
                    <strong class="d-block">Teléfono</strong>
                    <span>+52 951 526 08 80</span>
                  </li>
                  <li>
                    <strong class="d-block">Email</strong>
                    <span>maxlexgeo@gmail.com</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center text-center">
          <div class="col-md-7">
            <p class="copyright">&copy; Copyright MAXLEX GEO 2018. All Rights Reserved</p>
            <!--<div class="credits">
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>-->
          </div>
        </div>

      </div>
    </footer>
  </div> <!-- .site-wrap -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/jquery/jquery-migrate.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="vendor/easing/easing.min.js"></script>
  <script src="vendor/php-email-form/validate.js"></script>
  <script src="vendor/sticky/sticky.js"></script>
  <script src="vendor/aos/aos.js"></script>
  <script src="vendor/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Main JS File -->
  <script src="js/main.js"></script>

</body>

</html>
