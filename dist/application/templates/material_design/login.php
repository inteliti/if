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
	
	<!--<link rel="icon" type="image/png" href="<?= TMPL_URL; ?>img/favicon.png" />-->
	
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap/bootstrap.min.css' />
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap/bootstrap-theme.min.css' />
	<link rel='stylesheet' type='text/css' href='<?=TMPL_URL; ?>css/app.css' />
	
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/md5.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.additional-methods.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.messages_es.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.hotkeys.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.main.js'></script>	
	
	<script type='text/javascript' src='<?=TMPL_URL; ?>js/login.js'></script>
	
	<?php include PLUGINS_PATH.'if.modal/_loader.php'; ?>
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
		<?php include TMPL_PATH.'partial/login_form.php'; ?>
	</div>
	
	<div class="container-fluid">	
		<div class="row">
			<div class="col-lg-12 text-center" id="footer">
				&copy; <?= date('Y'); ?> Inteliti Soluciones, C.A. Todos los derechos reservados. 
			</div>
		</div>	
	</div>
	
	<script type="text/javascript">
		
		//configuracion inicial
		IF_MAIN.CI_INDEX = '<?= INDEX_URL ?>';
		IF_MAIN.init();
		
		LOGIN.init();
		
	</script>
	
</body>
</html>

