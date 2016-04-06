<!DOCTYPE html> 
<html lang='es'>
    <head>
        <title>OWL Administrator</title>
        <meta charset='UTF-8'>
        <script type='text/javascript' src='/_librerias/js/jquery-2.1.1.min.js'></script>
        <script type='text/javascript' src='/_librerias/js/owlchat/zilli.js'></script>
        <script type='text/javascript' src='/_librerias/js/owlchat/AjaxZilli.js'></script>
        <script type='text/javascript' src='/_librerias/js/owl-login.js'></script>
        <link href="/_estilos/owl-login.css" rel="stylesheet" type="text/css">
        <style>
            .form{
                min-width: 500px;
                width: 25%;
            }
        </style>
    </head>
    <body>
        <div class="form" id="form"></div>
        <script>
            var login = new Login({
                id          : "form",
                urlRequest  : "/_vistas/se_login_master.php",
                urlImg      : "/_imagenes/captcha.php"
            });
        </script>
    </body>
</html>