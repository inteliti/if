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
	 * - callbackDelete (opcional): callback cuando se elimina el avatar
	 * @returns {undefined}
	 */
	open: function (CNF)
	{
		IF_MODAL.show({
			title: CNF.title || 'Avatar',
			controller: CNF.controller + '/load_avatar/'
				+ CNF.id + '/' + (IF_MAIN.IS_MOBILE ? '1' : '0'),
			btns: {
				'Borrar Avatar': function ()
				{
					IF_AVATAR._elim(CNF.controller, CNF.id);
				}
				, 'Cerrar': function ()
				{
					IF_MODAL.close();
					IF_AVATAR.stopWebCam();
				}
			}
		});

		IF_AVATAR.callbackWebcam = CNF.callbackWebcam || $.noop;
		IF_AVATAR.callbackUpload = CNF.callbackUpload || $.noop;
		IF_AVATAR.callbackDelete = CNF.callbackDelete || $.noop;
	}

	, close: function ()
	{
		IF_MODAL.close();
	}

	//elimina el avatar (funcion PRIVADA)
	, _elim: function (controller, id)
	{
		IF_MODAL.confirm(
			'¿Eliminar el avatar permanentemente?', function (si)
			{
				if (!si) {
					IF_AVATAR.close();
					return;
				}

				IF_MODAL.show({
					hideTitle: true,
					controller: controller + '/delete_avatar/' + id,
					callback: IF_AVATAR.callbackDelete,
					timeout: 2500
				});
			}, {
			dontClose: true
		});
	}

	, upload: function (formSel, uploadTo)
	{
		IF_AVATAR._clear();
		IF_AVATAR._msg('Subiendo imagen...');

		var formData = new FormData($(formSel)[0]);
		$.ajax({
			url: uploadTo, //server script to process data
			type: 'POST',
			data: formData,
			success: function (e)
			{
				IF_AVATAR._uplSuccess(e);
			},
			error: function (e)
			{
				IF_AVATAR._error(
					'Ha ocurrido un error y no se ha podido subir el archivo, '
					+ 'intente nuevamente.'
					);
			},
			//Tell JQuery not to process data or worry about content-type
			cache: false,
			contentType: false,
			processData: false
		});
	}

	, _uplSuccess: function (e)
	{
		if (e == 0)
		{
			IF_AVATAR._error('No se pudo subir la imagen.');
		}
		else
		{
			IF_AVATAR._msg('Imagen subida satisfactoriamente.');
			
			//show image
			var token = Math.random().toString().replace('.', '');
			$(IF_AVATAR.SEL + '#result_img').prop('src', e + '?' + token);

			IF_AVATAR.callbackUpload();
		}
	}

	, _msg: function (msg)
	{
		$(IF_AVATAR.SEL + '#msg').html(msg);
	}

	, _error: function (msg)
	{
		$(IF_AVATAR.SEL + '#error').html(msg);
	}

	, _clear: function ()
	{
		$(IF_AVATAR.SEL + '#msg').empty();
		$(IF_AVATAR.SEL + '#error').empty();
	}

	, _uplFileChange: function (types, fileSize, plgnURL)
	{
		var file = $(IF_AVATAR.SEL + '#file')[0].files[0];

		if (file.size > (fileSize * 1024))
		{
			IF_AVATAR._error('El tamaño excede el m&aacute;ximo permitido.');
			return;
		}

		if ($.inArray(file.type.toLowerCase(), types) < 0)
		{
			IF_AVATAR._error('Formato de archivo no permitido.');
			return;
		}

		IF_AVATAR.upload(
			IF_AVATAR.SEL+'#forma_upload',
			plgnURL + 'upload.php'
			);
	}

	, stopWebCam: function ()
	{
		if(IF_AVATAR.localstream)
		{
			IF_AVATAR.localstream.getTracks()[0].stop();
		}
	}
};