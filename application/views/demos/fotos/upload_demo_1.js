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
		var id = '12345';
		
		//subir fotos, id se usara para crear el directorio,
		//deberia ser 
		mi_nombre_objeto.upload(id);
	}
	/**
	,
	save: function (form)
	{
		var data = IF_MAIN.serialize(form);

		var cnf = {
			controller: 'upload/save',
			target: '#upload-detail',
			data: data,
			callback: function ()
			{
				var success = $('input[name=success]').val();
				if (success)
				{
					SLIDER.nuevo();
				}
			}
		}

		IF_MAIN.loadCompos(cnf);
	}
	/**/
}

