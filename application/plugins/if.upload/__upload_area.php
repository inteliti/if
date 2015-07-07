<?php if( ! extension_loaded( 'fileinfo' )): ?>
	<?php if( SHOW_DEBUG_BACKTRACE ): ?>
	<p class="danger">Debe cargar la extensión de PHP fileinfo.</p>
	<?php else: ?>
	<p class="danger">Hubo un problema.</p>
	<?php endif; ?>
<?php else: ?>
<?php

	$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
	$PLG_PATH = isset($PLG_PATH) ? $PLG_PATH :  PLUGINS_PATH . 'if.upload/';
	$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ? $UPLOAD_FILE_TYPES : array('image/jpeg','image/png','image/gif','application/pdf');
	$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ? $UPLOAD_FILE_SIZE_MAX : 10000000; //EN BYTES esto es igual a 10 MB
	$UPLOAD_PATH = isset($UPLOAD_PATH) ? $UPLOAD_PATH : NULL;
	$FILES_ARRAY = isset($FILES_ARRAY) ? $FILES_ARRAY : array();
	$TITTLE = isset($TITTLE) ? $TITTLE : 'Imágenes y archivos';
	
	$MAX_COUNT_FILE = isset($MAX_COUNT_FILE) ? $MAX_COUNT_FILE : count($FILES_ARRAY);
	
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$base_dir = $this->config->item('base_url');
	
?>
<?php



?>

<div id="if-upload-area-<?= md5($UPLOAD_PATH) ?>" class="upload-area-canvas">
	
	<input type="hidden" name="upload_path" value="<?= $UPLOAD_PATH ?>" class="exclude" />
	
	
	<label>
		<?= $TITTLE ?>
	</label>
	
	
	<div id="if-upload-output-<?= md5($UPLOAD_PATH) ?>">
		<p class=""><p>
		<div class="row">
			<?php foreach($FILES_ARRAY as $FILE_NAME => $FILE): ?>
			<?php if(!empty($FILE)): ?>
			<input type="hidden" name="<?= $FILE_NAME; ?>" value="<?= $FILE; ?>" />
			<div id="if-upload-thumb-<?= $FILE_NAME; ?>" class="col-md-2" data-filename="<?= $FILE_NAME; ?>" data-file="<?= $FILE; ?>">
				<div class="if-upload-thumbnail">
					<div class="if-upload-caption">
						<p>
							<a 
							   class="label label-danger eli-img-btn" 
							   title="Borrar imágen">
								Borrar
							</a>
						</p>
						<p>
							<a onclick="IF_UPLOAD.desplazarIzq('<?= $FILE_NAME; ?>')" 
							   class="label label-info" 
							   title="Desplazar izquierda">
								<
							</a>
							<a	onclick="IF_UPLOAD.desplazarDer('<?= $FILE_NAME; ?>')" 
								class="label label-info" 
								title="Desplazar derecha">
								>
							</a>
						</p>
					</div>
					<img 
						class="img-thumbnail"
						alt="<?= $FILE_NAME; ?>" 
						src="<?= $UPLOAD_PATH . 'thumb_' . $FILE .'?'.time()   ?>">
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			
			<div id="if-upload-image-loader" class="col-md-2">
				<div class="if-upload-image-new">
					<div id="if-upload-input-<?= md5($UPLOAD_PATH) ?>" class="fileUpload">
						<input type="file" id="if-upload-image-file" class="upload" class="exclude" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	
	var cnf = {
		plg_url : '<?= $PLG_URL ?>',
		plg_path : '<?= $PLG_PATH ?>',
		upload_path : '<?= $UPLOAD_PATH ?>',
		max_count_file : <?= $MAX_COUNT_FILE ?>,
		files_array : <?= json_encode($FILES_ARRAY) ?>,
		upload_area : '#if-upload-area-<?= md5($UPLOAD_PATH) ?>',
		upload_area_input : '#if-upload-input-<?= md5($UPLOAD_PATH) ?>',
		upload_area_output : '#if-upload-output-<?= md5($UPLOAD_PATH) ?>',
		upload_files_types : <?= json_encode($UPLOAD_FILE_TYPES) ?>,
		upload_file_size_max : <?= $UPLOAD_FILE_SIZE_MAX ?>
	};
	
	var <?= 'IF_UPLOAD_'.md5($UPLOAD_PATH) ?> = new IF_UPLOAD();
	 
	
	<?= 'IF_UPLOAD_'.md5($UPLOAD_PATH) ?>.init(cnf);
	
	
</script>

<?php endif; ?>