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
	 * - controller: controlador CODEIGNITER que procesara la imagen
	 * (el cual debe heredar de controlador IF_Avatar.php) 
	 * - id: id NUMERICO de la persona duena del avatar
	 * - title (opcional): título del modal
	 * - callbackWebcam (opcional): callback cuando se suba una foto con webcam
	 * - callbackUpload (opcional): callback cuando se suba una foto de archivo
	 * @returns {undefined}
	 */
	open: function (CNF)
	{
		IF_MODAL.show({
			title: CNF.title || 'Avatar',
			controller: CNF.controller+'/detail_compos/'+CNF.id,
			btns: {
				'Cerrar': function ()
				{
					IF_MODAL.close();
				}
			}
		});
		
		IF_AVATAR.callbackWebcam = CNF.callbackWebcam || $.noop;
		IF_AVATAR.callbackUpload = CNF.callbackUpload || $.noop;
	}
	
	,close: function ()
	{
		IF_MODAL.close();
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
			$(selct + '#msg').show();

			//show image
			var token = Math.random();
			$(selct + 'img').attr('src', e + '?' + token);

			IF_AVATAR.callbackUpload();
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