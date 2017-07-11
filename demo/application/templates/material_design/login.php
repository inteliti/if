<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="-1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>IF - INTELITI FRAMEWORK</title>

		<script type='text/javascript' src='<?= LIBS_URL; ?>js/md5.js'>
		</script>
		<script src="<?= LIBS_URL; ?>js/jquery.min.js"></script>

	</head>
	<body >

		<div >
			<div>
				
				<h2>
					IF LOGIN
				</h2>
				<p>
					Ingrese su nombre de usuario y contraseña 
					para acceder al sistema.
				</p>


				<?php if(!empty($SYSMSG) && $SYSMSG == 'INVALID_LOGIN'): ?>
					<div class="widget red-bg p-lg text-center">
						<h3 class="font-bold no-margins">
							Autenticación fallida
						</h3>
						<small>
							Usuario o contraseña incorrectos.
						</small>
					</div>
				<?php elseif(!empty($SYSMSG) && $SYSMSG == 'BLOCKED_USER'): ?>
					<div class="widget yellow-bg p-lg text-center">
						<h3 class="font-bold no-margins">
							Usuario bloqueado
						</h3>
						<small>
							Su usuario se encuentra bloqueado
							y no podrá acceder al sistema.
							Por favor contacte a un administrador.
						</small>
					</div>
				<?php endif; ?>


				<form class="m-t" role="form" id="forma"
					  method="POST"
					  action="<?= INDEX_URL ?>IF_Sys/login"
					  >

					<input type="hidden" name="md5" id="md5" value="" />

					<div class="form-group">
						<input type="text" 
							   class="form-control"
							   name="usuario"
							   placeholder="Nombre de usuario" 
							   value=""
							   required="required"
							   autocomplete="off">
					</div>
					<div class="form-group">
						<input type="password" 
							   id="pass"
							   class="form-control" 
							   placeholder="Contraseña" 
							   value=""
							   required="required"
							   autocomplete="off">
					</div>
					<button type="submit" 
							class="btn btn-primary block full-width m-b">
						Acceder
					</button>

					<p class="text-muted text-center">
						<small>
							Si olvidó su contraseña o tiene problemas
							para entrar, contacte a un administrador
							del sistema.
						</small>
					</p>
				</form>
			</div>
		</div>
		<br /><br /><br />

		<!-- Mainly scripts -->

	</body>
	<script>
			$("#forma").submit(function ()
			{
				var md5 = hex_md5($("#pass").val());
				$("#md5").val(md5);
				$("#pass").val('');
			});
	</script>
</html>

