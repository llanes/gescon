<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión | Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo base_url('content/dist/gescon2.ico'); ?>" type="image/x-icon" />
    <link href="<?php echo base_url('content/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('content/font-awesome/css/fontawesome/css/all.css'); ?>" rel="stylesheet" type="text/css" />
    <style>
        body {
            background: linear-gradient(45deg, #83a4d4, #b6fbff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container .icon {
            font-size: 50px;
            color: #4A90E2;
            margin-bottom: 20px;
        }
        .login-container .form-signin {
            display: flex;
            flex-direction: column;
        }
        .login-container .form-signin .content-group {
            position: relative;
            margin-bottom: 15px;
        }
        .login-container .form-signin .content-group input {
            width: 100%;
            padding: 10px;
            padding-left: 40px; /* Add padding for the icon */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container .form-signin .content-group .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #888;
        }
        .login-container .form-signin .btn {
            background: #4A90E2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container .form-signin .btn i {
            margin-right: 5px;
        }
        .login-container .list-login {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .login-container .list-login .switch {
            display: flex;
            align-items: center;
        }
        .login-container .list-login .switch input {
            margin-right: 10px;
        }
        .login-container .list-login a {
            color: #4A90E2;
            text-decoration: none;
        }
        .login-container .bottom-grid_1 {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center icon">
            <i class="fab fa-google"></i><i class="fab fa-edge"></i><i class="fab fa-stripe-s"></i><i class="fab fa-tumblr"></i><i class="fas fa-italic"></i><i class="fab fa-opera"></i><i class="fab fa-maxcdn"></i>
        </div>
        <form class="form-signin" id="for_login" action="#" autocomplete="off">
            <div class="content-group" id="alet-usuario" data-toggle="tooltip" data-placement="right">
                <i class="fa fa-user input-icon" aria-hidden="true"></i>
                <input type="text" required maxlength="35" id="usuario" class="input-monde" placeholder="Usuario" name="usuario" pattern="[A-Za-z ]{3,100}">
            </div>
            <div class="content-group" id="alet-password" data-toggle="tooltip" data-placement="right">
                <i class="fa fa-lock input-icon" aria-hidden="true"></i>
                <input type="password" required maxlength="35" id="password" class="input-monde" name="password" placeholder="Clave" pattern="(?=.*[a-z]).{6,}">
            </div>
            <button type="submit" class="btn" id="cargar"><i class="fab fa-think-peaks"></i> <span id="gol">Empezar</span> <i id="init" class="fas fa-sign-in-alt"></i></button>
            <ul class="list-login">
                <li class="switch-agileits">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                        Mantener conectado
                    </label>
                </li>
                <li>
                    <a href="#" class="text-right">¿Olvidó su contraseña?</a>
                </li>
            </ul>
        </form>
        <div class="bottom-grid_1">
            <div class="copyright">
                <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> Todos los derechos reservados | Diseñado por
                    <a href="">EvoSoft.py</a>
                </p>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('bower_components/jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('content/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <script>
        $(function () {
            $('#for_login').submit(function(e) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url("index.php/Login/logeo"); ?>',
                    dataType: 'json',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#cargar').attr("disabled", true);
                        $('#for_login').css("opacity", ".5");
                    },
                })
                .done(function(data) {
                    if (data.res == "error") {
                        $('#for_login').css("opacity", "");
                        $('#cargar').attr("disabled", false);
                        if (data.usuario) {
                            $('#alet-usuario').addClass('alert-vaidation').attr('title', data.usuario).tooltip('show');
                        }
                        if (data.password) {
                            $('#alet-password').addClass('alert-vaidation').attr('title', data.password).tooltip('show');
                        }
                        setTimeout(function() {
                            $('#alet-usuario,#alet-password').removeClass('alert-vaidation').tooltip('hide');
                        }, 5000);
                    } else {
                        $('#for_login').css("opacity", "");
                        $('#cargar').attr("disabled", false);
                        $('#gol').text('Iniciando.');
                        $('#init').toggleClass('fa fa-spinner fa-pulse fa-1x fa-fw');
                        setTimeout('document.location.reload()', 2000);
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
