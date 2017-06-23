<?php include PLUGINS_PATH . "toastr/_loader.php"; ?>
<?php include APPPATH . "plugins/if.upload/_loader.php"; ?>
<script src='<?= PLUGINS_URL; ?>if.upload/l10n.es.js'></script>
<div>
	<div class="page-header">
		<h1>
			IF UPLOAD PLUGIN
		</h1>
	</div>

	<h2>Objeto con ID</h2>
	<div id="upload-detail"></div>
	<button type="button" class="btn btn-raised" onclick="saveConId()">
		Guardar
	</button>

	<hr />

	<h2>Objeto sin ID (nuevo)</h2>
	<div id="upload-detail-2"></div>
	<button type="button" class="btn btn-raised" onclick="saveSinId()">
		Guardar
	</button>

</div>
<script>
	//Objeto ya existente con ID
	var conID = new IF_UPLOAD({
		id: 89,
		target: '#upload-detail',
		controller: 'IF_Upload'
	});
	conID.loadComposite();

	//Objeto SIN ID
	var sinID = new IF_UPLOAD({
		id: IF_UPLOAD.NEW_OBJECT,
		target: '#upload-detail-2',
		controller: 'IF_Upload'
	});
	sinID.loadComposite();

	function saveConId(params)
	{
		toastr.info('Guardando...');
		conID.upload(function (response)
		{
			if (response.status != IF_UPLOAD.STATUS_OK) {
				toastr.error(
					"Error durante la subida. Codigo: " + response.status
					);
				return;
			}

			toastr.success('Archivos cargados exitosamente.');
		});
	}

	function saveSinId()
	{
		toastr.info('Guardando...');
		sinID.upload(function (response)
		{
			if (response.status != IF_UPLOAD.STATUS_OK) {
				toastr.error(
					"Error durante la subida. Codigo: " + response.status
					);
				return;
			}

			toastr.success('Archivos cargados exitosamente.');

			//En este punto, los archivos se encuentran en un directorio 
			//temporal.

			//Guardamos otra data del objeto (nombre, cedula, etc)......
			//.......
			//En este punto ya tenemos un ID para el objeto, necesitamos
			//reescribir el directorio temporal y asignarle el ID real.
			//Este método es ASINCRONO!!!!
			if (response.folder_provisional)
			{
				var id = Math.random(); //simulamos un ID
				sinID.setId(id, function ()
				{
					toastr.info("Directorio temporal renombrado al nuevo ID");
				});

			}
		});
	}
</script>	