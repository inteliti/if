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
	
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap.min.css' />
	<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap-theme.min.css' />
	<link rel='stylesheet' type='text/css' href='<?=TMPL_URL; ?>css/app.css' />
	
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/md5.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.additional-methods.min.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.messages_es.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.hotkeys.js'></script>
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.main.js'></script>	
	
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
		<?php include TMPL_PATH.'login_form.php'; ?>
	</div>
	
	<div class="container-fluid">	
		<div class="row">
			<div class="col-lg-12 text-center" id="footer">
				&copy; <?= date('Y'); ?> Inteliti Soluciones, C.A. Todos los derechos reservados. 
			</div>
		</div>	
	</div>
	
	<script type="text/javascript">
		
		function submit()
		{
			//IF_MODAL.alert('aqui va un mensaje!');

			/*if($('#pass').val())
			{
				var md5 = hex_md5($('#pass').val());
				$('#md5').val(md5);
				$('#pass').val('');
			}*/
			
			if($('#login').valid())
			{
				IF_MAIN.loadCompos({
					target: '#wrapper-login',
					controller: '_if_sys/login',
					data: $('#login').serializeArray(),
					callback: function()
					{
						//alert('funciono!');
					}
				});
			}
		}

		$('input').keyup(function(){ 
			return false; 
		});

		//
		IF_HOTKEY.registerTemp('enter', submit);

		//validacion de formulario
		IF_MAIN._form_validate(
			'#login', 
			{
				rules:{
					usuario: 'required'
				}
			}
		);
		
		//configuracion inicial
		IF_MAIN.CI_INDEX = '<?= INDEX_URL ?>';
		IF_MAIN.init();
	</script>
	
</body>
</html>

