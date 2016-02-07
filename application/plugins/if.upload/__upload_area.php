<?php if(!extension_loaded('fileinfo')): ?>
	<?php if(SHOW_DEBUG_BACKTRACE): ?>
		<p class="danger">Debe cargar la extensión de PHP fileinfo.</p>
	<?php else: ?>
		<p class="danger">Hubo un problema.</p>
	<?php endif; ?>
<?php else: ?>
	<?php
	$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
	$PLG_PATH = isset($PLG_PATH) ? correctPath($PLG_PATH) : correctPath(PLUGINS_PATH . 'if.upload/');
	$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ? $UPLOAD_FILE_TYPES : array('image/jpeg', 'image/png', 'image/gif', 'application/pdf');
	$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ? $UPLOAD_FILE_SIZE_MAX : 10000000; //EN BYTES esto es igual a 10 MB
	$UPLOAD_URL = isset($UPLOAD_URL) ? $UPLOAD_URL : NULL;
	$FILES_ARRAY = isset($FILES_ARRAY) ? $FILES_ARRAY : array();
	$TITTLE = isset($TITTLE) ? $TITTLE : 'Imágenes y archivos';
	$DELETE_CONFIRMATION = isset($DELETE_CONFIRMATION) ? $DELETE_CONFIRMATION : TRUE;

	$MAX_COUNT_FILE = isset($MAX_COUNT_FILE) ? $MAX_COUNT_FILE : count($FILES_ARRAY);

	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$base_dir = $this->config->item('base_url');
	?>

	<div id="if-upload-<?= md5($NOMBRE_OBJETO) ?>" class="if-upload">
		<label>
			<?= $TITTLE ?>
		</label>
		<p class="text-muted">
			Para eliminar una imagen, hágale doble click.
		</p>
		<div class="thumbs"></div>
	</div>

	<script>
		var <?= $NOMBRE_OBJETO ?> = new IF_UPLOAD({
			upload_url : '<?=INDEX_URL; ?>upload/ajax_save/',
			canvas: '#if-upload-<?= md5($NOMBRE_OBJETO) ?>',
			max_count_file: <?= $MAX_COUNT_FILE ?>,
			upload_files_types: <?= json_encode($UPLOAD_FILE_TYPES) ?>,
			upload_file_size_max: <?= $UPLOAD_FILE_SIZE_MAX ?>,
			delete_confirmation: <?= $DELETE_CONFIRMATION ? 'true' : 'false' ?>
		});
	</script>

<?php endif; ?>