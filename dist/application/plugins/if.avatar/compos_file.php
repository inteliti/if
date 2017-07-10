<?php

function echoMimes($mimes)
{
	$a = array();
	foreach($mimes as $m)
	{
		$a[] = strtoupper(str_replace('image/', '', $m));
	}
	echo implode(', ', $a);
}
?>
<div id="ifAvatarFile">
	<h4>
		Seleccione una imagen
	</h4>

	<small>
		&SmallCircle; Formatos permitidos: 
		<span class="text-primary"><?= echoMimes($UPLOAD_FILE_TYPES) ?></span>
		<br />
		&SmallCircle; Tamaño maximo permitido: 
		<span class="text-primary"><?= $UPLOAD_FILE_SIZE_MAX ?>KB</span>
	</small>

	<i class="clearfix"></i>
	<br />

	<div>
		<form enctype="multipart/form-data" id="forma_upload">
			<input type="file" name="file" id="file" class="form-control"  />

			<input type="hidden" name="avatar_folder" 
				   value="<?= $AVATAR_FOLDER ?>" />

			<input type="hidden" name="upload_path" 
				   value="<?= $UPLOAD_PATH ?>" />

			<?php if(!empty($UPLOAD_FILE_SIZE_MAX)): ?>
				<input type="hidden" 
					   name="file_size_max" 
					   value="<?= $UPLOAD_FILE_SIZE_MAX ?>" />
				   <?php endif; ?>

			<?php if(!empty($FILE_NAME)): ?>
				<input type="hidden" 
					   name="file_name" 
					   value="<?= $FILE_NAME ?>" />
				   <?php endif; ?>
		</form>

		<div class="text-center text-danger" id="error"></div>
		<div class="text-center text-primary" id="msg"></div>

	</div>
</div>
<script>
	IF_AVATAR.FILE.init(
<?= json_encode($UPLOAD_FILE_TYPES) ?>, <?= $UPLOAD_FILE_SIZE_MAX ?>
	);
</script>