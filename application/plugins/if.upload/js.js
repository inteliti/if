/*****************************************************
 * Clase JavaScript para la carga de archivos IF.UPLOAD
 * v3.0.0
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Changelog
 * ---------
 * 3.0.0
 * - Reescrito todo para simplificar el plugin entero
 *****************************************************/
var IF_UPLOAD = function (cnf)
{
	this.CNF = cnf || {};
	this.UPLOAD_URL = cnf.upload_url;
	this.CANVAS = cnf.canvas + " ";
	this.UPLOAD_AREA = this.CANVAS + ".thumbs";
	this.MAX_COUNT_FILE = cnf.max_count_file;
	this.UPLOAD_FILE_TYPES = cnf.upload_files_types;
	this.UPLOAD_FILE_SIZE_MAX = cnf.upload_file_size_max;
	this.DELETE_CONFIRMATION = cnf.delete_confirmation;

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

	//Carga de archivos
	this._addUploadBtn();
};


IF_UPLOAD.prototype = {
	//Anade un archivo nuevo al formulario, hace algunas comprobaciones
	_addFile: function (input)
	{
		var $input = $(input);
		$input.removeClass('add_file').addClass('hide');

		//revisa si esta vacio el input file
		if (!$input.val())
		{
			this._showMsg("No hay archivo");
			return false;
		}

		var file = input.files[0];
		var fsize = file.size; //Tamano
		var ftype = file.type; //Tipo
		$input.attr('name', file.name);

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

		//Render Thumbnail
		var reader = new FileReader();
		var that = this;
		reader.onload = function (e)
		{
			that._thumbRender(file, e);
		};
		reader.readAsDataURL(file);
		
		//anadir nuevo boton para subir otro archivo
		this._addUploadBtn();
	}

	//Dibuja el thumbnail
	, _thumbRender: function (file, e)
	{
		var that = this;
		$("<img src='" + e.target.result + "' name='" + file.name + "' />")
			.dblclick(function ()
			{
				that._removeFile(this);
			})
			.prependTo(this.CANVAS + '.thumbs')
			;
	}

	, _addUploadBtn: function ()
	{
		var that = this;
		$('<input type="file" class="add_file" />')
			.on('change', function () {
				that._addFile(this);
			})
			.appendTo(this.CANVAS + '.thumbs')
			;
	}

	//Remueve ambos thumbnail y el input file
	, _removeFile: function (file)
	{
		var name = $(file).attr('name');
		$(this.CANVAS + '.thumbs').find("[name='" + name + "']").remove();
	}

	//Muestra un mensaje
	, _showMsg: function (m)
	{
		alert(m);
	}

	//Llamar al momento de guardar el formulario para subir las imagenes.
	//TENER CUIDADO: sube las imagenes asincronamente, usar el callback
	//si se necesita continuar el flujo LUEGO de que se hallan subido.
	, upload: function (id, callback)
	{
		var $fileBtn = $(this.CANVAS + '.add_file').hide();

		var formData = new FormData();
		formData.append('id', id);
		var files = $(this.CANVAS+'input[type=file]').each(function (i)
		{
			formData.append("file"+i, this.files[0]);
		});
		
		$.ajax({
			url: this.UPLOAD_URL,
			type: 'POST',
			dataType: 'JSON',
			success : function (r)
			{
				$fileBtn.show();
				(callback||$.noop)(r);
			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		});
	}

}
;



