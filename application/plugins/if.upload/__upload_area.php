<?php if( ! extension_loaded( 'fileinfo' )): ?>
	<?php if( SHOW_DEBUG_BACKTRACE ): ?>
	<p class="danger">Debe cargar la extensión de PHP fileinfo.</p>
	<?php else: ?>
	<p class="danger">Hubo un problema.</p>
	<?php endif; ?>
<?php else: ?>
<?php
	
	
	$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
	$PLG_PATH = isset($PLG_PATH) ? correctPath($PLG_PATH) :  correctPath(PLUGINS_PATH . 'if.upload/');
	$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ? $UPLOAD_FILE_TYPES : array('image/jpeg','image/png','image/gif','application/pdf');
	$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ? $UPLOAD_FILE_SIZE_MAX : 10000000; //EN BYTES esto es igual a 10 MB
	$UPLOAD_URL = isset($UPLOAD_URL) ? $UPLOAD_URL : NULL;
	$FILES_ARRAY = isset($FILES_ARRAY) ? $FILES_ARRAY : array();
	$TITTLE = isset($TITTLE) ? $TITTLE : 'Imágenes y archivos';
	
	$MAX_COUNT_FILE = isset($MAX_COUNT_FILE) ? $MAX_COUNT_FILE : count($FILES_ARRAY);
	
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$base_dir = $this->config->item('base_url');
?>
<?php



?>

<div id="if-upload-area-<?= md5($UPLOAD_URL) ?>" class="upload-area-canvas">
	
	<input type="hidden" name="upload_url" value="<?= $UPLOAD_URL ?>" />
	
	
	<label>
		<?= $TITTLE ?>
	</label>
	
	
	<div id="if-upload-output-<?= md5($UPLOAD_URL) ?>">
		<p class=""><p>
		<div class="row">
			<?php foreach($FILES_ARRAY as $FILE_NAME => $FILE): ?>
			<?php if(!empty($FILE)): ?>
			<?php 
			$ftype = finfo_file($finfo, correctPath(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . rtrim($UPLOAD_URL, '/') . '/' . $FILE));
			?>
			<input type="hidden" name="<?= $FILE_NAME; ?>" value="<?= $FILE; ?>" />
			<div id="if-upload-thumb-<?= $FILE_NAME; ?>" class="col-md-2" data-filename="<?= $FILE_NAME; ?>" 
				 data-file="<?= $FILE; ?>" data-ftype="<?= $ftype ?>">
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
							<a class="label label-info izq-img-btn" 
							   title="Desplazar izquierda">
								<
							</a>
							<a	class="label label-info der-img-btn" 
								title="Desplazar derecha">
								>
							</a>
						</p>
					</div>
					<?php if($ftype=='image/jpeg' || $ftype=='image/png' || $ftype=='image/gif'): ?>
					<img 
						class="img-thumbnail"
						alt="<?= $FILE_NAME; ?>" 
						src="<?= $UPLOAD_URL . 'thumb_' . $FILE .'?'.time()   ?>">
					<?php else: ?>
					<img 
						class="img-thumbnail"
						alt="PDF" 
						src="<?= $PLG_URL ?>img/PDF-Icon.jpg">
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			
			<div id="if-upload-image-loader" class="col-md-2">
				<div class="if-upload-image-new">
					<div id="if-upload-input-<?= md5($UPLOAD_URL) ?>" class="fileUpload">
						<span class="icon-upload"></span>
						<input type="file" id="if-upload-image-file" class="upload" class="exclude" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	
	var cnf = {
		plg_url : '<?= addslashes($PLG_URL) ?>',
		plg_path : '<?= addslashes($PLG_PATH) ?>',
		upload_url : '<?= $UPLOAD_URL ?>',
		max_count_file : <?= $MAX_COUNT_FILE ?>,
		files_array : <?= json_encode($FILES_ARRAY) ?>,
		upload_area : '#if-upload-area-<?= md5($UPLOAD_URL) ?>',
		upload_area_input : '#if-upload-input-<?= md5($UPLOAD_URL) ?>',
		upload_area_output : '#if-upload-output-<?= md5($UPLOAD_URL) ?>',
		upload_files_types : <?= json_encode($UPLOAD_FILE_TYPES) ?>,
		upload_file_size_max : <?= $UPLOAD_FILE_SIZE_MAX ?>
	};
	
	var <?= 'IF_UPLOAD_'.md5($UPLOAD_URL) ?> = new IF_UPLOAD();
	
	<?= 'IF_UPLOAD_'.md5($UPLOAD_URL) ?>.init(cnf);
		
</script>

<?php endif; ?>