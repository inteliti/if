var UPLOAD_DEMO = {
	
	//mi_nombre_objeto debe ser un nombre unico en el que se instanciara
	//la clase IF_UPLOAD para futura referencia.
	detail: function (id)
	{
		IF_MAIN.loadCompos({
			target: '#upload-detail',
			controller: 'upload/detail/' + (id || '-1') + '/mi_nombre_objeto',
			callback: function () {

			}
		});
	}
	
	,save: function ()
	{
		
		//procesar resto del formulario....
		
		
		//Llamamos a nuestro objeto IF_UPLOAD
		mi_nombre_objeto.upload(function (errorCode)
		{
			if(!errorCode)
			{
				alert('Archivos cargados exitosamente.');
			}
			else
			{
				alert('Error, codigo: '+errorCode);
			}
		});
		
	}
};

