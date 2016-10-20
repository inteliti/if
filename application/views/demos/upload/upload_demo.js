var UPLOAD_DEMO = {
	
	//mi_nombre_objeto debe ser un nombre unico en el que se instanciara
	//la clase IF_UPLOAD para futura referencia.
	detail: function (id)
	{
		IF_MAIN.loadCompos({
			target: '#upload-detail',
			controller: 'IF_Upload/detail_compos/' + (id || '-1') + '/mi_nombre_objeto',
			callback: function () {

			}
		});
	}
	
	,save: function ()
	{
		
		//procesar resto del formulario....
		
		
		toastr.info('Guardando...');
		
		
		//Llamamos a nuestro objeto IF_UPLOAD
		mi_nombre_objeto.upload(function (response)
		{
			if(response.error==IF_UPLOAD.ERROR_NONE)
			{
				toastr.success('Archivos cargados exitosamente.');
			}
			else
			{
				toastr.error('Error, codigo: '+response.error);
			}
		});
		
	}
};

