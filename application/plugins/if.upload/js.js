/*****************************************************
 * Clase JavaScript para la carga de archivos IF.UPLOAD
 * v3.2.1
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Dependencias
 * ------------
 * - if.main +1.2.0
 * - Se debe cargar uno de los archivos de localización (l10n.xx.js) 
 * antes de instanciar. (_loader.php NO lo carga, debe cargarse manualmente)
 *****************************************************/
var IF_UPLOAD = function (cnf)
{
	this.CNF = cnf || {};
	this.ID = cnf.id;
	this.UPLOAD_URL = cnf.upload_url;
	this.PLG_URL = cnf.plg_url;
	this.NAMESPACE = cnf.namespace + " ";
	this.FILE_COUNT = cnf.file_count;
	this.UPLOAD_FILE_TYPES = cnf.upload_files_types;
	this.UPLOAD_FILE_SIZE_MAX = cnf.upload_file_size_max;
	this.IMAGE_SIZE_MAX = cnf.image_size_max;
	this.DELETE_CONFIRMATION = cnf.delete_confirmation;
	this.THUMBS = $(this.NAMESPACE + '.thumbs');
	
	//Revisa si el navegador soporta todas las caracteristicas de File API
	if (
		!window.File || !window.FileReader || !window.FileList ||
		!window.Blob
		)
	{
		this._showMsg(IF_UPLOAD.L10N.UPDATE_BROWSER);
		return false;
	}

	var that = this;

	//Procesa fotos que ya han sido cargadas
	this.THUMBS.find('img[data-remote]')
		.on(IF_MAIN.IS_MOBILE ? 'tap' : 'click', function ()
		{
			var img = this;
			that._emulateClickAndDoubleClick(that, function ()
			{
				window.open($(img).attr('src'));
			}, function ()
			{
				that._removeRemoteFile(img);
			});
		})
		.on("dblclick", function (e) {
			e.preventDefault();  //cancel system double-click event
		})
		;

	//Activa la carga de nuevos archivos
	this._addUploadBtn();

};
IF_UPLOAD.prototype = {
	//Anade un archivo nuevo al formulario, hace algunas comprobaciones
	_addFile: function (input)
	{
		var $input = $(input), that = this;

		//revisa si esta vacio el input file
		if (!$input.val())
		{
			return;
		}

		var file = input.files[0];
		var fsize = file.size; //Tamano
		var ftype = file.type; //Tipo

		//Solo tipos permitidos
		var ftype_flag = true;
		for (var i = 0; i < this.UPLOAD_FILE_TYPES.length; i++)
		{
			if (ftype == this.UPLOAD_FILE_TYPES[i])
			{
				ftype_flag = false;
				break;
			}
		}
		if (ftype_flag)
		{
			this._showMsg(IF_UPLOAD.L10N.INVALID_FILE_TYPE);
			return false;
		}

		var addBtn = function ()
		{
			$(that.NAMESPACE + '.add_file')
				.removeClass('add_file')
				.addClass('hide')
				.attr('name', file.name)
				;
			that._addUploadBtn();
		};

		//Verifica Tamaño del archivo
		if (fsize > this.UPLOAD_FILE_SIZE_MAX)
		{
			this._showMsg(IF_UPLOAD.L10N.INVALID_FILE_SIZE);
			return false;
		}

		//Render Thumbnail solo para imagenes, muestra un icono
		//para archivos que no son imagenes
		if (this._mimeSimple(file.type) == 'image')
		{
			var reader = new FileReader();
			reader.onload = function (e)
			{
				that._thumbRender(file, e);
				addBtn();
			};
			reader.readAsDataURL(file);
		} else
		{
			this._thumbIcon(file);
			addBtn();
		}

	}

	//Dibuja el thumbnail (solo imagenes)
	, _thumbRender: function (file, e)
	{
		var that = this;
		$("<img src='" + e.target.result + "' name='" + file.name + "' />")
			.on(IF_MAIN.IS_MOBILE ? 'tap' : 'click', function ()
			{
				var img = this;
				that._emulateClickAndDoubleClick(that, function ()
				{
					window.open(e.target.result);
				}, function ()
				{
					that._removeFile(img);
				});
			})
			.on("dblclick", function (e) {
				e.preventDefault();  //cancel system double-click event
			})
			.prependTo(this.THUMBS)
			;
	}

	, _thumbIcon: function (file)
	{
		var that = this;
		var ext = file.name.split('.').pop().toLowerCase();

		$("<img src='" + this.PLG_URL + "img/filetype/" + ext + ".png' />")
			.attr({
				'name': file.name,
				'title': file.name
			})
			.on('doubleclick doubletap', function ()
			{
				that._removeFile(this);
			})
			.prependTo(this.THUMBS)
			;
	}

	, _countFiles: function ()
	{
		return this.THUMBS.children('img').length;
	}

	, _addUploadBtn: function ()
	{
		//No añadir el boton de carga si ya se excede FILE_COUNT
		if (this.FILE_COUNT >= 0 && this._countFiles() >= this.FILE_COUNT)
		{
			return;
		}

		//No añadir el botón si ya existe uno
		if ($(this.NAMESPACE + '.add_file').length >= 1)
		{
			return;
		}

		var that = this;
		var btn = $('<div class="add_file">'
			+ '<img src="' + this.PLG_URL + 'img/add_file.png" />'
			+ '<input type="file" />'
			+ '</div>'
			)
			.appendTo(this.THUMBS)
			;
		btn.find('input').on('change', function () {
			that._addFile(this);
		});
		btn.find('img').click(function ()
		{
			$(this).parent().find('input').click();
		});
	}

	//Remover un archivo local que aun no se ha subido. El cambio
	//aplica inmediatamente
	, _removeFile: function (file)
	{
		var that = this;
		IF_MODAL.confirm(IF_UPLOAD.L10N.CONFIRM_DELETE_FILE, function (si)
		{
			if (!si)
			{
				return;
			}

			that.THUMBS.find("[name='" + file.name + "']").remove();

			that._addUploadBtn();
		});
	}

	//Remover un archivo remoto YA subido. El cambio aplicará al llamar
	//upload()
	, _removeRemoteFile: function (img)
	{
		var that = this;
		IF_MODAL.confirm(IF_UPLOAD.L10N.CONFIRM_DELETE_FILE, function (si)
		{
			if (!si)
			{
				return;
			}
			var remoteFileName = $(img).attr('data-remote');
			var remoteList = $(that.NAMESPACE + '.remove_remote_files').val();
			$(that.NAMESPACE + '.remove_remote_files').val(
				remoteList + ',' + remoteFileName
				);
			$(img).remove();

			that._addUploadBtn();
		});
	}

	//Muestra un mensaje
	, _showMsg: function (m)
	{
		alert(m);
	}

	//Llamar al momento de guardar el formulario para subir las imagenes.
	//TENER CUIDADO: sube las imagenes asíncronamente, usar el callback
	//si se necesita continuar el flujo LUEGO de que se hallan subido.
	, upload: function (callback)
	{
		var $fileBtn = $(this.NAMESPACE + '.add_file').hide();

		var formData = new FormData();
		formData.append('id', this.ID);
		formData.append('remove_remote_files',
			$(this.NAMESPACE + '.remove_remote_files').val()
			);

		var files = $(this.NAMESPACE + 'input[type=file]').each(function (i)
		{
			var file = this.files[0]
			if (file)
			{
				formData.append("file" + i, file);
			}
		});

		$.ajax({
			url: this.UPLOAD_URL,
			type: 'POST',
			dataType: 'JSON',
			success: function (r)
			{
				$fileBtn.show();
				(callback || $.noop)(r);
			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		});
	}

	//Dtermina si se ha hecho un click o un doble click
	//(desktop y movil) para ejecutar acciones distintas
	, FILE_CLICKS: 0
	, FILE_CLICKS_TIMER: null
	, _emulateClickAndDoubleClick: function (that, clickFn, dblClickFn)
	{
		if (++that.FILE_CLICKS === 1)
		{
			that.FILE_CLICKS_TIMER = setTimeout(function ()
			{
				that.FILE_CLICKS = 0;
				clickFn();
			}, 500);
		} else {
			window.clearTimeout(that.FILE_CLICKS_TIMER);
			that.FILE_CLICKS = 0;
			dblClickFn();
		}
	}

	, _mimeSimple: function (s)
	{
		s = s.toLowerCase();

		if (s.indexOf('image/') >= 0)
		{
			s = 'image';
		} else if (s.indexOf('audio/') >= 0)
		{
			s = 'mp3';
		} else if (s.indexOf('video/') >= 0)
		{
			s = 'mp4';
		} else if (s.indexOf('excel') >= 0)
		{
			s = 'xls';
		} else if (s.indexOf('word') >= 0)
		{
			s = 'doc';
		} else if (s.indexOf('powerpoint') >= 0)
		{
			s = 'pps';
		} else if (s.indexOf('text/plain') >= 0)
		{
			s = 'txt';
		} else if (s.indexOf('pdf') >= 0)
		{
			s = 'pdf';
		} else
		{
			s = 'otro';
		}
		return s;
	}






};
//----------------------------------------------
//Posibles respuestas de error que nos lanza IF_Upload
//----------------------------------------------
/**
 * Ningún error, la imagen se subió y almacenó correctamente.
 * @type Number
 */
IF_UPLOAD.ERROR_NONE = 0;
/**
 * El servidor no pudo guardar el archivo (no pudo crear directorio,
 * posibles errores de permisologías/acceso de directorio, etc)
 * @type Number
 */
IF_UPLOAD.ERROR_FILE_NOT_CREATED = 1;
/**
 * La imagen excede las dimensiones permitidas
 * @type Number
 */
IF_UPLOAD.ERROR_IMAGE_WRONG_SIZE = 2;