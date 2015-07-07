<?php

	$PLG_URL = isset($PLG_URL) ? $PLG_URL : PLUGINS_URL . 'if.upload/';
	$PLG_PATH = isset($PLG_PATH) ? $PLG_PATH :  PLUGINS_PATH . 'if.upload/';
	$UPLOAD_FILE_TYPES = isset($UPLOAD_FILE_TYPES) ? $UPLOAD_FILE_TYPES : array('image/jpeg','image/png');
	$UPLOAD_FILE_SIZE_MAX = isset($UPLOAD_FILE_SIZE_MAX) ? $UPLOAD_FILE_SIZE_MAX : 200;
	$UPLOAD_PATH = isset($UPLOAD_PATH) ? $UPLOAD_PATH : NULL;
	$FILES_ARRAY = isset($FILES_ARRAY) ? $FILES_ARRAY : array();
	$TITTLE = isset($TITTLE) ? $TITTLE : 'Imágenes';
	
	
	$MAX_COUNT_FILE = isset($MAX_COUNT_FILE) ? $MAX_COUNT_FILE : count($FILES_ARRAY);
	
?>


<div id="if-upload-area">
	
	<input type="hidden" name="upload_path" value="<?= $UPLOAD_PATH ?>" />
	
	<duv id="if-upload-input">
		<label>
			<?= $TITTLE ?>
		</label>
		<input name="if-upload-image-file" id="if-upload-image-file" type="file" class="exclude" />
	</duv>
	
	<div id="if-upload-output">
		<p class=""><p>
		<div class="row">
			<?php foreach($FILES_ARRAY as $FILE_NAME => $FILE): ?>
			<?php if(!empty($FILE)): ?>
			<input type="hidden" name="<?= $FILE_NAME; ?>" value="<?= $FILE; ?>" />
			<div id="if-upload-thumb-<?= $FILE_NAME; ?>" class="col-md-2">
				<div class="if-upload-thumbnail">
					<div class="if-upload-caption">
						<p>
							<a onclick="IF_UPLOAD.removeImage('<?= $FILE_NAME; ?>')" 
							   class="label label-danger" 
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
						src="<?= $UPLOAD_PATH . $FILE .'?'.time()   ?>">
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
			
			<div id="if-upload-image-loader" class="col-md-2">
				<div class="if-upload-image-new">
					
				</div>
			</div>
			

		</div>
	</div>
</div>

<script>
	
	var cnf = {
		plg_url : '<?= $PLG_URL ?>',
		upload_path : '<?= $UPLOAD_PATH ?>',
		max_count_file : <?= $MAX_COUNT_FILE ?>,
		files_array : <?= json_encode($FILES_ARRAY) ?>
	}
	
	IF_UPLOAD.init(cnf);
	
	
</script>