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

<span class="avatar">
	<img src="<?= $PLG_URL; ?>up3.png" alt="" border="0" />
</span>

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

<div id="file">
	<form enctype="multipart/form-data">
		<input type="file" name="file" class="form-control"  />

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

		<input type="hidden" name="upload_path" 
			   value="<?= $UPLOAD_PATH ?>" />
	</form>

	<strong><small class="text-danger" id="error"></small></strong>

	<div id="msg" class="">
		<br />
		<span>Imagen subida exitosamente.</span>
	</div>

</div>


<script>

	$('#ifAvatar #upl :file').change(function ()
	{
		var SEL = '#ifAvatar #upl ';
		var file = this.files[0];
		var types = <?= json_encode($UPLOAD_FILE_TYPES) ?>;

		if (file.size > (<?= $UPLOAD_FILE_SIZE_MAX ?> * 1024))
		{
			$(SEL + '#error').html(
				'El tamaño excede el m&aacute;ximo permitido.'
				);
			return;
		}

		if ($.inArray(file.type.toLowerCase(), types) < 0)
		{
			$(SEL + '#error').html(
				'Formato  de archivo no permitido.'
				);
			return;
		}

		IF_AVATAR.upload(
			'#ifAvatar #upl form',
			'<?= $PLG_URL ?>/upload.php',
<?= empty($CALLBACK) ? '$.noop' : $CALLBACK ?>
		);
	});
</script>