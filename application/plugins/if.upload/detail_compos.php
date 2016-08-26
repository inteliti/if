<div id="<?= $NAMESPACE; ?>" class="if-upload">
	<input type="hidden" class="remove_remote_files" value="" />
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
				$src = $PLG_URL . "img/filetype/{$ext}.png";
			}
			?>
			<img src="<?= $src ?>" data-remote="<?= $v ?>"
				 alt="" border="0" />
			 <?php endforeach ?>
	</div>
	<ul class="text-muted texts">
		<li class="text-zoom"></li>
		<li class="text-del"></li>
	</ul>
</div>
<script>
	var cnf = {
		id: '<?= $ID; ?>',
		upload_url: '<?= $CONTROLLER; ?>',
		plg_url: '<?= addslashes($PLG_URL) ?>',
		namespace: '#<?= $NAMESPACE; ?>',
		upload_files_types: <?= json_encode($CONFIG->FILE_TYPE) ?>,
		upload_file_size_max: <?= $CONFIG->FILE_SIZE_MAX ?>,
		file_count: <?= $CONFIG->FILE_COUNT ?>,
		image_size_max: <?= json_encode($CONFIG->IMAGE_SIZE_MAX) ?>
	};
	var <?= $NOMBRE_OBJETO ?> = new IF_UPLOAD(cnf);

	//Cambiar el mensaje en mobiles
	$("#<?= $NAMESPACE; ?> .text-zoom").html(
		IF_MAIN.IS_MOBILE ?
		IF_UPLOAD.L10N.VIEW_TEXT_CLICK_TO_ZOOM_MOBILE :
		IF_UPLOAD.L10N.VIEW_TEXT_CLICK_TO_ZOOM_DESKTOP
		);

	$("#<?= $NAMESPACE; ?> .text-del").html(
		IF_MAIN.IS_MOBILE ?
		IF_UPLOAD.L10N.VIEW_TEXT_DELETE_FILE_MOBILE :
		IF_UPLOAD.L10N.VIEW_TEXT_DELETE_FILE_DESKTOP
		);

	if (cnf.image_size_max)
	{
		$("<li></li>")
			.html(
				IF_UPLOAD.L10N.VIEW_IMAGE_SIZE_MAX.replace(
					'%1', cnf.image_size_max[0] + 'x' + cnf.image_size_max[1]
					)
				)
			.appendTo("#<?= $NAMESPACE; ?> .texts")
			;
	}
</script>
