var UPLOAD_DEMO = {
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
		
		mi_nombre_objeto.upload();
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

