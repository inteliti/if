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
	<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.main.js'></script>
	
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
	
	<?php if(!empty($error)): ?>
	
	<div class="container-fluid">
		<div class="row">
			<div id="error" class="col-lg-12 text-center bg-danger">
				<div >
					<?= $error; ?>
				</div>
			</div>
		</div>
	</div>
	
	<?php endif; ?>
	
	<div id="wrapper-login" class="container">
		<form id="form-login" method="post" action="./">
			<div class="form-group">
				
				<?php if(empty($usuario)): ?>
				
				<div class="form-group">
					<input type="text" 
						   id="usuario" name="usuario" 
						   class="form-control input-lg" 
						   placeholder="Nombre de usuario" 
						   required 
						   autofocus  />
				</div>
				
				<?php else: ?>
				
				<div class="form-group">
					<input type="password" 
						   id="pass" 
						   class="form-control input-lg" 
						   placeholder="Contraseña" 
						   required 
						   autocomplete="off" 
						   autofocus />
				</div>
				
				<input type="hidden" id="md5" name="md5" value="" />
				<input type="hidden" id="usuario" name="usuario" value="<?= $usuario; ?>" />
				<input type="hidden" id="is_valid" name="is_valid" value="<?= $is_valid ? '1': '0'; ?>" />
				
				<?php endif; ?>
				
				<?php if(!empty($enable_captcha) && $enable_captcha): ?>
				
				<!-- CAPTCHA -->
				
				<div class="form-group">
					<input type="text" 
						   id="captcha" name="captcha"
						   class="form-control input-lg" 
						   placeholder="Captcha" 
						   required 
						   autocomplete="off" />
				</div>
				
				<?php endif; ?>
				
			</div>
		</form>
		
		<button type="button" 
				class="btn btn-lg btn-primary btn-block" 
				onclick="submit();">
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
	
	<div id="div" style="border: red dashed 1px;"></div>
	
	<script type="text/javascript">
			
		IF_MAIN.CI_INDEX = '<?= INDEX_URL ?>';
		IF_MAIN.INVALID_BROWSER_URL = 
				IF_MAIN.CI_INDEX + '_if_sys/browser_invalid';
		IF_MAIN.init();
				
		$('input').keyup(function(e){
			if(e.which === 13)
			{ 
				submit();
			}
		});
		
		function submit()
		{
			/*if($('#pass').val())
			{
				var md5 = hex_md5($('#pass').val());
				$('#md5').val(md5);
				$('#pass').val('');
			}*/	
			
			//$('#form-login').submit();
			/*IF_MAIN.ajax({
				controller: 'login',
				data: $('#form-login').serialize(),
				callback: function(r)
				{
					alert(r);
				}
			});*/
		
			//alert($('#form-login').serialize());
			
			IF_MAIN.loadCompos({
				controller: '_if_sys/login',
				target: '#div'
			});
		}
	</script>
	
</body>
</html>

