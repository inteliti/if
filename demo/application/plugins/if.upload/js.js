/*****************************************************
 * Clase JavaScript para la carga de archivos IF.UPLOAD
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
	this.CNF = cnf;
	this.ID = cnf.id || -1;
	this.NAMESPACE = cnf.target + " ";
	this.$NAMESPACE = $(this.NAMESPACE);
	this.CONTROLLER = cnf.controller + "/";
	this.LAST_UPLOAD_RESPONSE = null;

	//Revisa si el navegador soporta todas las caracteristicas de File API
	if (
		!window.File || !window.FileReader || !window.FileList ||
		!window.Blob
		)
	{
		this._showMsg(IF_UPLOAD.L10N.UPDATE_BROWSER);
		return false;
	}

	$(this.NAMESPACE).addClass("if-upload");
};
IF_UPLOAD.prototype = {
	//Carga el composite con los archivos en target.
	//Pasar -1 en ID para un objeto nuevo que aun no tiene id
	loadComposite: function (callback)
	{
		var that = this;

		IF_MAIN.loadCompos({
			target: this.NAMESPACE,
			controller: this.CONTROLLER + 'detail_compos/' + this.ID,
			callback: function ()
			{
				that._composLoaded.call(that);
				(callback || $.noop)(that);
			}
		});
	}

	, _composLoaded: function ()
	{
		var $plgUrl = this.$NAMESPACE.find("[name=PLG_URL]");
		this.PLG_URL = $plgUrl.val();
		$plgUrl.remove();

		var $config = this.$NAMESPACE.find("[name=CONFIG]");
		this.CONFIG = JSON.parse($config.val());
		$config.remove();
		console.debug(this.CONFIG);

		this.$THUMBS = this.$NAMESPACE.find('.thumbs');

		//Procesa fotos que ya han sido cargadas
		var that = this;
		this.$THUMBS.find('img[data-remote]')
			.on(IF_MAIN.IS_MOBILE ? 'tap' : 'click', function ()
			{
				var img = this;
				that._emulateClickAndDoubleClick(that, function ()
				{
					var filePath = $(img).data('is-img')===1 ? $(img).attr('src') : $(img).data('remote-path');
					window.open(filePath);
					
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

		//Strings
		this._setStrings();
	}

	//Llamar al momento de guardar el formulario para subir las imagenes.
	//TENER CUIDADO: sube las imagenes asíncronamente, usar el callback
	//si se necesita continuar el flujo LUEGO de que se hallan subido.
	, upload: function (callback)
	{
		var that = this;
		var $fileBtn = $(this.NAMESPACE + '.add_file').hide();

		var formData = new FormData();
		formData.append('id', this.ID);
		formData.append('remove_remote_files',
			$(this.NAMESPACE + '.remove_remote_files').val()
			);

		$(this.NAMESPACE + 'input[type=file]').each(function (i)
		{
			var file = this.files[0];
			if (file)
			{
				formData.append("file" + i, file);
			}
		});

		$.ajax({
			url: IF_MAIN.CI_INDEX + this.CONTROLLER + "ajax_save",
			type: 'POST',
			dataType: 'JSON',
			success: function (r)
			{
				that.LAST_UPLOAD_RESPONSE = r;
				that.loadComposite();
				(callback || $.noop)(r);
			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		});
	}

	, setId: function (newId, callback)
	{
		if (!this.LAST_UPLOAD_RESPONSE) {
			return;
		}

		this.ID = newId;

		var that = this;
		$.ajax({
			url: IF_MAIN.CI_INDEX + this.CONTROLLER + "ajax_rename_folder/"
				+ newId + "/" + this.LAST_UPLOAD_RESPONSE.id,
			success: function (r)
			{
				that.loadComposite();
				(callback || $.noop)(that);
			}
		}
		);
	}

	//Anade un archivo nuevo al formulario, hace algunas comprobaciones
	, _addFile: function (input)
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
		for (var i = 0; i < this.CONFIG.FILE_TYPE.length; i++)
		{
			if (ftype == this.CONFIG.FILE_TYPE[i])
			{
				ftype_flag = false;
				break;
			}

			//si es permitido los archivos .rar hay   
			//que evaluar por la extension del archivo
			if (this.CONFIG.FILE_TYPE[i] === 'application/x-rar-compressed')
			{
				if (file.name.indexOf('.rar') !== -1)
				{
					ftype_flag = false;
					break;
				}
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
		if (fsize > this.CONFIG.FILE_SIZE_MAX)
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
			.prependTo(this.$THUMBS)
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
			.prependTo(this.$THUMBS)
			;
	}

	, _countFiles: function ()
	{
		return this.$THUMBS.children('img').length;
	}

	, _addUploadBtn: function ()
	{
		//No añadir el boton de carga si ya se excede FILE_COUNT
		if (this.CONFIG.FILE_COUNT >= 0 &&
			this._countFiles() >= this.CONFIG.FILE_COUNT)
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
			.appendTo(this.$THUMBS)
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

			that.$THUMBS.find("[name='" + file.name + "']").remove();

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

	, _setStrings: function ()
	{
		this.$NAMESPACE.find('.text-zoom').html(
			IF_MAIN.IS_MOBILE ?
			IF_UPLOAD.L10N.VIEW_TEXT_CLICK_TO_ZOOM_MOBILE :
			IF_UPLOAD.L10N.VIEW_TEXT_CLICK_TO_ZOOM_DESKTOP
			);
		this.$NAMESPACE.find('.text-del').html(
			IF_MAIN.IS_MOBILE ?
			IF_UPLOAD.L10N.VIEW_TEXT_DELETE_FILE_MOBILE :
			IF_UPLOAD.L10N.VIEW_TEXT_DELETE_FILE_DESKTOP
			);
	}




};
//----------------------------------------------
//Posibles respuestas de error que nos lanza IF_Upload
//----------------------------------------------
/**
 * Ningún error, archivo se subió y almacenó correctamente.
 * @type Number
 */
IF_UPLOAD.STATUS_OK = 0;
/**
 * El servidor no pudo guardar el archivo (no pudo crear directorio,
 * posibles errores de permisologías/acceso de directorio, etc)
 * @type Number
 */
IF_UPLOAD.STATUS_FILE_NOT_CREATED = 1;
/**
 * La imagen excede las dimensiones permitidas
 * @type Number
 */
IF_UPLOAD.STATUS_IMAGE_WRONG_SIZE = 2;
/**
 * Constante de conveniencia para legibilidad. Usar al crear una instancia de IF_UPLOAD para un objeto que aun no tiene ID
 * @type Number
 */
IF_UPLOAD.NEW_OBJECT = -1;