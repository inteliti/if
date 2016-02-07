<?php
$id = isset($id) ? $id : NULL;
?>
<?php include APPPATH . "plugins/if.upload/_loader.php"; ?>
<script src='<?= APP_URL; ?>views/demos/fotos/upload_demo.js'></script>
<div>
	<div class="page-header">
		<h1>
			IF UPLOAD PLUGIN
		</h1>
	</div>
	<div id="upload-detail"></div>
</div>
<script>
	$(function ()
	{
		UPLOAD_DEMO.detail(<?= $id ?>);
	});
</script>	