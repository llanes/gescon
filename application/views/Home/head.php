<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Gescom</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="<?= base_url('content/dist/iconcanva.ico');?>">
    
    <?php 
    
    echo $this->mi_css_js->css(); 
    ?>
     <style>
    .mini-menu {
      position: fixed;
      bottom: 50px;
      right: 20px;
      display: flex;
    align-items: center;
    }
    .menu-link {
        padding: 8px 12px;
  background-color: #51122A;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.menu-link:hover {
  background-color: #ddd;
}

.toggle-navbar-btn {
  padding: 8px 12px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.toggle-navbar-btn:hover {
  background-color: #0056b3;
}
  </style>

<link href="<?= base_url('bower_components/manifest.json')?>" rel="manifest">
  </head>
  
