/****************************************
 * V2.0
 * 
 * DEPENDENCIAS
 * - if.main 1.2+
 * - if.modal 2.0+
 * 
 ****************************************/

var IF_AVATAR = {
	SEL: '#ifAvatar ',
	localstream: null,
	
	
	/**
	 * Llamar a esta funcion para mostrar la ventana de carga de avatar.
	 * Configuración:
	 * - controller: Ruta del controlador CODEIGNITER que procesara 
	 * la imagen (el cual debe heredar de If_Avatar_controller) 
	 * - title (opcional): título del modal
	 * - 
	 * @returns {undefined}
	 */
	show: function (CNF)
	{
		IF_MODAL.show({
			title: CNF.title || '',
			
		});
	}

	, upload: function (formSel, uploadTo, callback)
	{
		var selct = IF_AVATAR.SEL + '#upl ';
		$(selct + '#error').html('');

		//show progress
		//$(selct + '#file').hide();
		$(selct + '#msg').removeClass('hidden');

		var formData = new FormData($(formSel)[0]);
		$.ajax({
			url: uploadTo, //server script to process data
			type: 'POST',
			data: formData,
			success: function (e)
			{
				IF_AVATAR._uplSuccess(e, callback);
			},
			error: function (e)
			{
				IF_AVATAR._uplError(e);
				callback(false);
			},
			//Tell JQuery not to process data or worry about content-type
			cache: false,
			contentType: false,
			processData: false
		});
	}

	, _uplSuccess: function (e, callback)
	{
		var selct = IF_AVATAR.SEL + '#upl ';
		if (e == 0)
		{
			IF_AVATAR._uplError();
			callback(false);
		} else
		{
			$(selct + '#msg i').addClass('success');
			$(selct + '#msg span').html('Imagen subida exitosamente.');

			//show image
			var token = Math.random();
			$(selct + 'img').attr('src', e + '?' + token);

			callback(true);
		}
	}

	, _uplError: function (e)
	{
		alert('Ha ocurrido un error y no se ha podido subir el archivo, '
			+ 'intente nuevamente	.');
		IF_AVATAR._uplShowInput();
	}

	, _uplShowInput: function ()
	{
		$(IF_AVATAR.SEL + '#upl #msg').hide();
		$(IF_AVATAR.SEL + '#upl #file').fadeIn();
	}

	//
	, stopWebCam: function ()
	{
		IF_AVATAR.localstream.getTracks()[0].stop();
	}
};