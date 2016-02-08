<?php
$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ?
	$UPLOAD_FILE_TYPES : array(
	'image/jpeg', 'image/png', 'image/gif', 'application/pdf'
);
$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ?
	$UPLOAD_FILE_SIZE_MAX : 10000000
; //EN BYTES esto es igual a 10 MB
$TITTLE = isset($TITTLE) ? $TITTLE : 'Imágenes y archivos';
$DELETE_CONFIRMATION = isset($DELETE_CONFIRMATION) ?
	$DELETE_CONFIRMATION : TRUE
;
$MAX_COUNT_FILE = isset($MAX_COUNT_FILE) ?
	$MAX_COUNT_FILE : count($FILES_ARRAY)
;
?>
<div id="if-upload-<?= md5($NOMBRE_OBJETO) ?>" class="if-upload">

	<input type="hidden" class="remove_remote_files" value="" />

	<label>
		<?= $TITTLE ?>
	</label>
	<p class="text-muted msg">
		Para eliminar una imagen, hágale doble click.
	</p>
	
	<div class="thumbs">
		<?php foreach($FILES as $v): ?>
			<?php
			$a = explode('.', $v);
			$ext = strtolower(array_pop($a));
			?>
			<?php
			if(
				$ext == 'jpg' || $ext == 'jpeg' ||
				$ext == 'png' || $ext == 'gif' || $ext == 'bmp'
			)
			{
				$src = $FILES_URL . $v;
			}
			else
			{
				$src = $PLG_URL . "img/files/{$ext}.png";
			}
			?>
			<img src="<?= $src ?>" data-remote="<?= $v ?>"
				 alt="" border="0" />
			 <?php endforeach ?>
	</div>
</div>
<script>
	var <?= $NOMBRE_OBJETO ?> = new IF_UPLOAD({
		id: '<?= $ID; ?>',
		upload_url: '<?= INDEX_URL; ?>upload/ajax_save/',
		plg_url: '<?= addslashes($PLG_URL) ?>',
		namespace: '#if-upload-<?= md5($NOMBRE_OBJETO) ?>',
		max_count_file: <?= $MAX_COUNT_FILE ?>,
		upload_files_types: <?= json_encode($UPLOAD_FILE_TYPES) ?>,
		upload_file_size_max: <?= $UPLOAD_FILE_SIZE_MAX ?>,
		delete_confirmation: <?= $DELETE_CONFIRMATION ? 'true' : 'false' ?>
	});

	if (IF_MAIN.IS_MOBILE)
	{
		$("if-upload-<?= md5($NOMBRE_OBJETO) ?> .msg").html(
			"Para eliminar una imagen, tóquela dos veces."
			);
	}
</script>
