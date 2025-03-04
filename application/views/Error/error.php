<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Gescon </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     <link rel="shortcut icon" href="<?php echo base_url('content/dist/PDF.ico');?>">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url('content/bootstrap/css/bootstrap.css');?>" rel="stylesheet" type="text/css" />  

    <link href="<?= base_url('content/dist/css/AdminLTE.css');?>" rel="stylesheet" type"text/css" />

  </head>
  <body class="lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
        <a href="#"><b><?= $titulo2 ?></b></a>
      </div>
      <div class="lockscreen-logo">
        <a href="#"><b><?= $titulo ?></b></a>
      </div>
      <!-- User name -->
      <div class="lockscreen-name"><?= $titulo3   ?></div>

     <div class='lockscreen-footer text-center'>
      <b>Gescon</b> 1.0    <strong>Copyright &copy; 2016 <a href="">Administracion</a>.</strong> Todos los derechos reservados 
      </div>
    </div><!-- /.center -->

    <!-- jQuery 2.1.4 -->
   <script src="<?php echo base_url();?>bower_components/jquery/jquery.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url();?>admin_stilo/bootstrap/js/bootstrap.js" type="text/javascript"></script>
  </body>
</html>