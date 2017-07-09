/****************************************
 * v2.0.1
 * 
 * DEPENDENCIAS
 * - if.main 1.2+
 * - if.modal 2.1+
 ****************************************/

var IF_AVATAR = {
	SEL: '#ifAvatar ',
	ERROR: 0,
	SUCCESS: 1,
	localstream: null,
	/**
	 * Llamar a esta funcion para mostrar la ventana de carga de avatar.
	 * Configuración:
	 * - controller: controlador CODEIGNITER que procesara la imagen
	 * (el cual debe heredar de controlador IF_Avatar.php) 
	 * - id: id NUMERICO del objeto dueño del avatar
	 * - crop (obj | opcional): habilita crop (desabilitado por defecto)
	 * - title (opcional): título del modal
	 * - onUpload (opcional): se ejecuta al subir el avatar. Recibe dos
	 * parametros:
	 *		1- el path absoluto a la imagen que se acaba de subir o la
	 *		constante ERROR en error
	 *		2- string que indica el tipo de subida: "webcam" o "file"
	 * - onDelete (opcional): se ejecuta al borrar el avatar. Recibe las
	 * constantes SUCCESS en exito o ERROR en error 
	 */
	open: function (CNF)
	{
		IF_AVATAR.CNF = CNF;
		IF_AVATAR.callback = CNF.callback || $.noop;

		IF_MODAL.show({
			title: CNF.title || 'Avatar',
			controller: CNF.controller + '/load_avatar/'
				+ CNF.id + '/' + (IF_MAIN.IS_MOBILE ? '1' : '0'),
			btns: {
				'Borrar Avatar': IF_AVATAR._elim,
				'Cerrar': IF_AVATAR.close
			}
		});
	}

	, close: function ()
	{
		IF_AVATAR.CAM.stopWebCam();
		IF_MODAL.close();
	}

	//elimina el avatar (funcion PRIVADA)
	, _elim: function ()
	{
		var
			CNF = IF_AVATAR.CNF
			;

		IF_MODAL.confirm(
			'¿Confirma eliminar el avatar permanentemente?', function (si)
			{
				if (!si) {
					IF_AVATAR.close();
					return;
				}

				$.ajax({
					url: IF_MAIN.CI_INDEX + CNF.controller
						+ '/delete_avatar/' + CNF.id,
					type: 'POST',
					success: CNF.onDelete
				});
			});
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
};


IF_AVATAR.FILE = {
	SEL: '#ifAvatarFile '
	, init: function (types, maxSize)
	{
		var
			SEL = IF_AVATAR.FILE.SEL,
			CNF = IF_AVATAR.CNF
			;

		CNF.types = types;
		CNF.maxSize = maxSize;

		$(SEL + '#file').change(IF_AVATAR.FILE._uplFileChange);

	}

	, _uplFileChange: function ()
	{
		var
			SEL = IF_AVATAR.FILE.SEL,
			CNF = IF_AVATAR.CNF,
			types = CNF.types,
			fileSize = CNF.maxSize,
			file = $(SEL + '#file')[0].files[0]
			;

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

		if (CNF.crop)
		{
			IF_AVATAR.CROP.init(file, 'file');
		} else
		{
			IF_AVATAR.FILE.upload(file);
		}
	}

	, upload: function (file)
	{
		IF_AVATAR._clear();

		var
			CNF = IF_AVATAR.CNF
			;

		var reader = new FileReader();
		reader.onload = function (e)
		{
			$.ajax({
				url: IF_MAIN.CI_INDEX + CNF.controller + '/file_upload',
				type: 'POST',
				data: {
					id: CNF.id,
					img_data: e.target.result
				},
				success: function (e)
				{
					CNF.onUpload(e, 'file');
				},
				error: function (e)
				{
					CNF.onUpload(IF_AVATAR.ERROR, 'file');
				}
			});
		};
		reader.readAsDataURL(file);
	}

};

IF_AVATAR.CAM = {
	SEL: '#ifAvatarCam '
	, init: function ()
	{
		var
			SEL = IF_AVATAR.CAM.SEL,
			video = $(SEL + '#video')[0],
			canvas = $(SEL + '#canvas')[0],
			width = 200,
			height = 150
			;

		canvas.width = width;
		canvas.height = height;

		IF_AVATAR.CAM.CANVAS = canvas;

		navigator.getMedia = (navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);

		navigator.getMedia({
			video: true,
			audio: false
		}, function (stream)
		{
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}

			IF_AVATAR.localstream = stream;

			setTimeout(function () {
				video.play();
			}, 200);
		},
			function (err)
			{
				console.log("An error occured! " + err);
			}
		);

		video.setAttribute('width', width);
		video.setAttribute('height', height);
		canvas.setAttribute('width', width);
		canvas.setAttribute('height', height);

		$(SEL + "#startbutton").click(IF_AVATAR.CAM.upload);
	}

	, upload: function (ev)
	{
		var
			SEL = IF_AVATAR.CAM.SEL,
			CNF = IF_AVATAR.CNF,
			video = $(SEL + '#video')[0],
			canvas = $(SEL + '#canvas')[0],
			uploadPath = IF_MAIN.CI_INDEX + CNF.controller + '/cam_upload',
			width = 200,
			height = 150
			;

		canvas.getContext('2d').drawImage(video, 0, 0, width, height);

		var data = canvas.toDataURL('image/jpeg', 0.9);

		if (CNF.crop)
		{
			function dataURItoBlob(dataURI) {
				var binary = atob(dataURI.split(',')[1]);
				var array = [];
				for (var i = 0; i < binary.length; i++) {
					array.push(binary.charCodeAt(i));
				}
				return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
			}

			IF_AVATAR.CROP.init(dataURItoBlob(data), 'webcam');
		} else
		{
			$.ajax({
				url: uploadPath,
				type: 'POST',
				data: {
					id: CNF.id,
					img_data: data
				},
				success: function (e)
				{
					CNF.onUpload(e, 'webcam');
				},
				error: function (e)
				{
					CNF.onUpload(IF_AVATAR.ERROR, 'webcam');
				}
			});
		}
	}

	, stopWebCam: function ()
	{
		if (IF_AVATAR.localstream)
		{
			IF_AVATAR.localstream.getTracks()[0].stop();
		}
	}
};

//Plugin usado: https://fengyuanchen.github.io/cropper/
IF_AVATAR.CROP = {
	TYPE: ''
	, init: function (file, type)
	{
		var CNF = IF_AVATAR.CNF;

		IF_AVATAR.CROP.TYPE = type;

		var reader = new FileReader();
		reader.onload = function (e)
		{
			$(IF_AVATAR.SEL).load(
				IF_MAIN.CI_INDEX + CNF.controller + '/crop_load',
				function ()
				{
					var $img = $('#crop #crop_img');
					$img
						.attr('src', e.target.result)
						.cropper(CNF.crop)
						;
				})
				;
		};
		reader.readAsDataURL(file);
	}

	, upload: function (img)
	{
		var CNF = IF_AVATAR.CNF;

		$.ajax({
			url: IF_MAIN.CI_INDEX + IF_AVATAR.CNF.controller + '/crop_upload',
			type: 'POST',
			data: {
				img_data: img.src,
				id: CNF.id
			},
			success: function (e)
			{
				CNF.onUpload(e, IF_AVATAR.CROP.TYPE);
			},
			error: function (e)
			{
				CNF.onUpload(IF_AVATAR.ERROR, IF_AVATAR.CROP.TYPE);
			}
		});
	}
};
