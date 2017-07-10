<div id="ifAvatar" class="mobile">
	<p>
		Pulse este icono para tomar una foto (si su dispositivo tiene cámara)
		o subir una imagen ya almacenada.
	</p>
	<br />
	<form enctype="multipart/form-data" id="forma_upload">
		<div class="add_file">
			<i class="fa fa-upload"></i>
			<input type="file" name="file" id="file"  />
		</div>

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
<script>
	var IF_AVATAR_FILE = $("#ifAvatar .add_file #file");

	$("#ifAvatar .add_file .fa").click(function ()
	{
		IF_AVATAR_FILE.click();
	});

	IF_AVATAR_FILE.change(function ()
	{
		var types = <?= json_encode($UPLOAD_FILE_TYPES) ?>;
		var fileSize = <?= $UPLOAD_FILE_SIZE_MAX ?>;
		var plgnURL = '<?= $PLG_URL ?>/';
		IF_AVATAR._uplFileChange(types, fileSize, plgnURL);
	});
</script>