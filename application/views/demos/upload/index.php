<?php
$id = isset($id) ? $id : NULL;
?>
<?php include PLUGINS_PATH . "toastr/_loader.php"; ?>
<?php include APPPATH . "plugins/if.upload/_loader.php"; ?>
<script src='<?= PLUGINS_URL; ?>if.upload/l10n.es.js'></script>
<script src='<?= APP_URL; ?>views/demos/upload/upload_demo.js'></script>
<div>
	<div class="page-header">
		<h1>
			IF UPLOAD PLUGIN
		</h1>
	</div>
	<div id="upload-detail"></div>
	<div id="upload-detail-2"></div>
	<hr />
	<button type="button" class="btn" onclick="UPLOAD_DEMO.save()">
		Guardar
	</button>
</div>
<script>
	$(function ()
	{
		UPLOAD_DEMO.detail(89);
	});
</script>	