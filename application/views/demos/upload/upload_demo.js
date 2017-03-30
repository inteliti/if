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

		IF_MAIN.loadCompos({
			target: '#upload-detail-2',
			controller: 'Second_upload/detail_compos/' + (id || '-1') + '/mi_nombre_objeto_2',
			callback: function () {

			}
		});
	}

	, save: function ()
	{

		//procesar resto del formulario....


		toastr.info('Guardando...');

		console.log('sejecuto aqui 1');
		//Llamamos a nuestro objeto IF_UPLOAD
		console.log(mi_nombre_objeto);
		mi_nombre_objeto.upload(function (response)
		{
			console.log('sejecuto aqui 2');
			if (response.error == IF_UPLOAD.ERROR_NONE)
			{
				console.log('sejecuto aqui 3');
				toastr.success('Archivos cargados exitosamente.');

				console.log('sejecuto aqui 5');
				
				console.log(mi_nombre_objeto_2);

				mi_nombre_objeto_2.upload(function (response)
				{
					console.log('sejecuto aqui 6');
					if (response.error == IF_UPLOAD.ERROR_NONE)
					{
						console.log('sejecuto aqui 7');
						toastr.success('Archivos cargados exitosamente.');
					}
					else
					{
						console.log('sejecuto aqui 8');
						toastr.error('Error, codigo: ' + response.error);
					}
				});



			}
			else
			{
				console.log('sejecuto aqui 4');
				toastr.error('Error, codigo: ' + response.error);
			}
		});




	}
};

