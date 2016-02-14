/*****************************************************
 * Clase JavaScript para la carga de archivos IF.UPLOAD
 * v3.0.0
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Changelog
 * ---------
 * 3.0.0
 * - Reescrito todo el plugin
 * - Removida dependencia con BD
 *****************************************************/
var IF_UPLOAD = function (cnf)
{
	this.CNF = cnf || {};
	this.ID = cnf.id;
	this.UPLOAD_URL = cnf.upload_url;
	this.PLG_URL = cnf.plg_url;
	this.NAMESPACE = cnf.namespace + " ";
	this.MAX_COUNT_FILE = cnf.max_count_file;
	this.UPLOAD_FILE_TYPES = cnf.upload_files_types;
	this.UPLOAD_FILE_SIZE_MAX = cnf.upload_file_size_max;
	this.DELETE_CONFIRMATION = cnf.delete_confirmation;
	this.THUMBS = $(this.NAMESPACE + '.thumbs');

	//Revisa si el navegador soporta todas las caracteristicas de File API
	if (
		!window.File || !window.FileReader || !window.FileList ||
		!window.Blob
		)
	{
		this._showMsg(
			"Debe actualizar su navegador, se requieren "
			+ "ciertas caracteristicas que su navegador no dispone."
			);
		return false;
	}

	var that = this;

	//Procesa fotos que ya han sido cargadas
	this.THUMBS.find('img[data-remote]').dblclick(function ()
	{
		that._removeRemoteFile(this);
	});

	//Activa la carga de nuevos archivos
	this._addUploadBtn();
};


IF_UPLOAD.prototype = {
	//Anade un archivo nuevo al formulario, hace algunas comprobaciones
	_addFile: function (input)
	{
		var $input = $(input);

		//revisa si esta vacio el input file
		if (!$input.val())
		{
			this._showMsg("No hay archivo");
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
			this._showMsg("<b>" + ftype + "</b> tipo de archivo no soportado.");
			return false;
		}

		//Verifica Tamaño del archivo
		if (fsize > this.UPLOAD_FILE_SIZE_MAX)
		{
			this._showMsg("El tamaño del archivo excede el limite de "
				+ (this.UPLOAD_FILE_SIZE_MAX / 1024)
				+ "Kb permitido."
				);
			return false;
		}

		//Render Thumbnail solo para imagenes, muestra un icono
		//para archivos que no son imagenes
		if (this._mimeSimple(file.type) == 'image')
		{
			var reader = new FileReader();
			var that = this;
			reader.onload = function (e)
			{
				that._thumbRender(file, e);
			};
			reader.readAsDataURL(file);
		} else
		{
			this._thumbIcon(file);
		}

		//anadir nuevo boton para subir otro archivo
		$(this.NAMESPACE + '.add_file')
			.removeClass('add_file')
			.addClass('hide')
			.attr('name', file.name)
			;
		this._addUploadBtn();
	}

	//Dibuja el thumbnail (solo imagenes)
	, _thumbRender: function (file, e)
	{
		var that = this;
		$("<img src='" + e.target.result + "' name='" + file.name + "' />")
			.dblclick(function ()
			{
				that._removeFile(this);
			})
			.prependTo(this.THUMBS)
			;
	}

	, _thumbIcon: function (file)
	{
		var that = this;
		var type = this._mimeSimple(file.type);

		$("<img src='" + this.PLG_URL + "img/files/" + type + ".png' />")
			.attr({
				'name': file.name,
				'title': file.name
			})
			.dblclick(function ()
			{
				that._removeFile(this);
			})
			.prependTo(this.THUMBS)
			;
	}

	, _addUploadBtn: function ()
	{
		var that = this;
		var btn = $('<div class="add_file">'
			+ '<img src="' + this.PLG_URL + 'img/upload.png" />'
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
		IF_MODAL.confirm('¿Confirma eliminar este archivo?', function (si)
		{
			if (!si)
			{
				return;
			}
			that.THUMBS.find("[name='" + file.name + "']").remove();
		});
	}

	//Remover un archivo remoto YA subido. El cambio aplicará al llamar
	//upload()
	, _removeRemoteFile: function (img)
	{
		var that = this;
		IF_MODAL.confirm('¿Confirma eliminar este archivo?', function (si)
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
		});
	}

	//Muestra un mensaje
	, _showMsg: function (m)
	{
		alert(m);
	}

	//Llamar al momento de guardar el formulario para subir las imagenes.
	//TENER CUIDADO: sube las imagenes asincronamente, usar el callback
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

}
;



