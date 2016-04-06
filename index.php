<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Login Form</title>
        <script type="text/javascript" src="../_librerias/js/global.js"></script>
        <script type="text/javascript" src="../_librerias/js/ajaxglobal.js"></script>
        <link rel="stylesheet" href="css/style.css">    
  </head>
  <body>
    <body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>SystemDc</h1>
			</div>
			<div class="login-form">
				 <div  id=panelMsg></div>
				<div id=verificar  ></div>
				 <form method="post" action="" id="Form_login_usuario" name="Form_login_usuario">
					<div class="control-group">
					<input type="text" class="login-field" value="" placeholder="User" id="login-name" name="Usuario">
					<label class="login-field-icon fui-user" for="login-name"></label>
					</div>

					<div class="control-group">
					<input type="password" class="login-field" value="" placeholder="password" id="login-pass" name="Contrasena">
					<label class="login-field-icon fui-lock" for="login-pass"></label>
					</div>

					<a class="btn btn-primary btn-large btn-block" href="#" onclick="enviaFormRD('../_vistas/login_user.php?metodo=login_usuario&transaccion=OTRO','Form_login_usuario','panelMsg','/projects.php');" >login</a>
					
				</form>
				
			</div>
		</div>
	</div>
</body>
</body>
</html>
