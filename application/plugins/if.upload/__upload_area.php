<?php

	$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
	$PLG_PATH = isset($PLG_PATH) ? $PLG_PATH :  PLUGINS_PATH . 'if.upload/';
	$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ? $UPLOAD_FILE_TYPES : array('image/jpeg','image/png','image/gif','application/pdf');
	$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ? $UPLOAD_FILE_SIZE_MAX : 1000000; //EN BYTES esto es igual a 1 MB
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
	
	<duv id="if-upload-input-<?= md5($UPLOAD_PATH) ?>">
		<label>
			<?= $TITTLE ?>
		</label>
		<input name="if-upload-image-file" id="if-upload-image-file" type="file" class="exclude" />
	</duv>
	
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
							<?php if( finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/jpeg' ||
								finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/png' ||
								finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/gif' ): ?>
							<a 
							   class="label label-info ver-img-btn" 
							   title="Ver imágen">
								Ver
							</a>
							<?php else: ?>
							<a 
							   class="label label-info des-fil-btn" 
							   href="<?= $UPLOAD_PATH . $FILE ?>"
							   target="_blank"
							   title="Descargar" >
								Descargar
							</a>
							<?php endif; ?>
						</p>
					</div>
					<?php if( finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/jpeg' ||
							finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/png' ||
							finfo_file($finfo, getcwd(). '/' . substr($UPLOAD_PATH . $FILE, strlen($base_dir)))=='image/gif' ): ?>
					<img 
						class="img-thumbnail"
						alt="<?= $FILE_NAME; ?>" 
						src="<?= $UPLOAD_PATH . 'thumb_' . $FILE .'?'.time()   ?>">
					<?php else: ?>
					<img 
						class="img-thumbnail"
						alt="PDF" 
						src="<?= $PLG_URL . 'img/PDF-Icon.jpg'  ?>">
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			<div id="if-upload-image-loader" class="col-md-2 hidden">
				
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

