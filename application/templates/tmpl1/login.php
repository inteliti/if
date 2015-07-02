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

	<title>if (inteliti framework)</title>
	
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap.min.css' />
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap-theme.min.css' />
	
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/md5.js'></script>

	<link rel='stylesheet' type='text/css' href='<?=TMPL_URL; ?>css/login.css' />
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h1>if (inteliti framework)</h1>
			</div>
		</div>
	</div>	
	
	<div id="wrapper-login" class="container">
		<form id="form-login" method="post" action="./">
			<div class="form-group">
				
				<div class="form-group">
					<input type="text" id="" name="usuario" 
						   class="form-control input-lg" 
						   placeholder="Usuario" 
						   required autofocus />
				</div>
				
				<!--<div class="form-group">
					<input type="password" id="pass" 
						   class="form-control input-lg" 
						   placeholder="Contraseña" 
						   required 
						   autocomplete="off" />
				</div>-->
			</div>
			<!--<input type="hidden" id="md5" name="md5" value="" />-->
		</form>
		
		<button type="button" 
				class="btn btn-lg btn-primary btn-block" 
				onclick="submit()">
			Iniciar sesión
		</button>
	</div>
	
	<div class="container-fluid">	
		<div class="row">
			<div class="col-lg-12 text-center" id="footer">
				© <?= date('Y'); ?> Inteliti Soluciones, C.A. Todos los derechos reservados. 
			</div>
		</div>	
	</div>
	
	<script type="text/javascript">
		
		$('input').keyup(function(e){
			if(e.which==13) submit();
		});
		
		function submit()
		{
			/*var md5 = hex_md5( $('#pass').val() );

			$('#md5').val(md5);
			$('#pass').val('');*/
			$('#form-login').submit();
			
			return false;
		}
	</script>
	
</body>
</html>

