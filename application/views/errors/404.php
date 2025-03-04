<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Error</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     <link rel="shortcut icon" href="<?php echo base_url('content/dist/error.ico');?>">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url('content/bootstrap/css/bootstrap.css');?>" rel="stylesheet" type="text/css" />    
    <!-- Font Awesome Icons -->
<link href="<?php echo base_url('content/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->

    <!-- Theme style -->
   <link href="<?php echo base_url('content/dist/css/AdminLTE.css');?>" rel="stylesheet" type"text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="">
    <div class="wrapper">
      <!-- Left side column. contains the logo and sidebar -->
      <!-- Content Wrapper. Contains page content -->
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">

          <div class="error-page">
            <h1 class="headline text-yellow"> 404</h1>
            <div class="error-content">
              <h3><i class="fa fa-warning text-yellow"></i> Ups! Página no encontrada..</h3>
              <p>
               No hemos podido encontrar la página que estabas buscando.
Mientras tanto , es posible <a href='#' name="Back2" onclick="history.back()">Volver Atras</a> o intente utilizar el formulario de búsqueda.
              </p>
              <form class='search-form' method="GET" action="http://www.google.com/search">
                <div class='input-group'>
                  <input type="text" name="q" class='form-control' placeholder="Buscar"/>
                  <INPUT TYPE=hidden name=hl value=es>
                  <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div><!-- /.input-group -->
              </form>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section><!-- /.content -->
    <!-- jQuery 2.1.4 -->
   <script  src="<?php echo base_url('content/plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script  src="<?php echo base_url('content/bootstrap/js/bootstrap.min.js');?>" type="text/javascript"></script>    

  </body>
</html>
