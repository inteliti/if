<?php
	$usuario = isset($usuario) ? $usuario : NULL;
	$is_valid = isset($is_valid) ? $is_valid : FALSE;
	
	$success = isset($success) ? $success : FALSE;
	$msg = isset($msg) ? $msg : NULL;
?>


<form id="login_form" method="post" onsubmit="return false;">
	<div class="form-group">
		
		<?php if(!empty($msg)): ?>
		
		<div class="alert <?= $success ? 'alert-success' : 'alert-danger'; ?>" role="alert">
			<?= $msg; ?>
		</div>
		
		<?php endif; ?>
		
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
				   id="pass" name="pass"
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

		<!-- TO-DO: CAPTCHA -->

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
		onclick="LOGIN.submit()">
	<?= empty($usuario) ? 'Siguiente' : 'Acceder'; ?>
</button>

<?php if($success): ?>

<!-- Hubo exito en el inicio de sesion y se refresca el sitio web para obtener la sesion -->

<script>
	location.href = IF_MAIN.CI_INDEX;
</script>

<?php endif; ?>
