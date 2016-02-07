<?php
	//PARAMETROS  DE ENTRADA DE LA VISTA
	
	//campos
    $id = isset($id) ? $id : NULL;
	
	$file1 = isset($file1) ? $file1 : "";
	$file2 = isset($file2) ? $file2 : "";
	$file3 = isset($file3) ? $file3 : "";
	$file4 = isset($file4) ? $file4 : "";
	$file5 = isset($file5) ? $file5 : "";
	$file6 = isset($file6) ? $file6 : "";
	
	//parametros extra
	$main_upload_url = isset($main_upload_url) ? $main_upload_url : "";
?>
<div>

	<form id="det-upload-form" onsubmit="return(false);" novalidate>
		
		<input type="hidden" name="id" value="<?= $id; ?>" />
		
		<?php 
			include "upload_files_loader.php"; 
		?>
		
		<hr />
		
		<div class="row">
			<div class="col-md-12">
				<button class="btn green pull-right margin-left-right-xs" 
						onclick="UPLOAD_DEMO.save('#det-upload-form');">
					Guardar
				</button>
			</div>
		</div>
		
	</form>
</div>


