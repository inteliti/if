/*****************************************************
 * Clase JavaScript para la carga de archivos IF.UPLOAD
 * v2.0.0
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/

/*****************************************************
 * Constructor
 *****************************************************/
var IF_UPLOAD = function() {};

/*****************************************************
 * Prototipo de clase IF_UPLOAD
 *****************************************************/
IF_UPLOAD.prototype = {
	
	/*
	 * ATRIBUTOS
	 */
	PLG_URL : null,
	PLG_PATH : null,
	UPLOAD_URL : null,
	MAX_COUNT_FILE : 1,
	FILES : null,
	FILES_COUNT : 0,
	UPLOAD_AREA : null,
	UPLOAD_AREA_INPUT : null,
	UPLOAD_AREA_OUTPUT : null,
	UPLOAD_FILE_TYPES : null,
	UPLOAD_FILE_SIZE_MAX: null,
	DELETE_CONFIRMATION: true,
	
	/*
	 * METODO INIT
	 * Sirve para inicalizar el plugin
	 */
	init: function(cnf)
	{
		//Se inicalizan todos los parametros de entrada
		this.PLG_URL = cnf.plg_url;
		this.PLG_PATH = cnf.plg_path;
		this.UPLOAD_URL = cnf.upload_url;
		this.MAX_COUNT_FILE = cnf.max_count_file;
		this.FILES = cnf.files_array;
		this.UPLOAD_AREA = cnf.upload_area;
		this.UPLOAD_AREA_INPUT = cnf.upload_area_input; 
		this.UPLOAD_AREA_OUTPUT = cnf.upload_area_output;
		this.UPLOAD_FILE_TYPES = cnf.upload_files_types;
		this.UPLOAD_FILE_SIZE_MAX = cnf.upload_file_size_max;
		this.DELETE_CONFIRMATION = cnf.delete_confirmation;
		
		//restore count of files to 0, before calculate
		this.FILES_COUNT = 0;
		
		for (var k in this.FILES) {
			if (this.FILES.hasOwnProperty(k)) {
			   ++this.FILES_COUNT;
			}
		}
		
		//that para usarse dentro de otro conexto
		var that = this;
		
		//inicializacion de listener para saber cuando se ha cargado un archivo
		$(this.UPLOAD_AREA + ' #if-upload-image-file').on('change',function(e){
			that._submit();
		});
		
		//inicializacion en controles de archivos ya cargados
		this._initControlsCaption();
		
		//si esta full la carga de archivos se oculta la opcion de carga al usuario
		if(this._isFullCountFiles())
		{
			$(this.UPLOAD_AREA_INPUT).hide();
			$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
	/*
	 * Incializa controles para manipular archivos
	 */
	_initControlsCaption : function()
	{
		$(this.UPLOAD_AREA + ' .if-upload-thumbnail').hover(
			function(){
				$(this).find('.if-upload-caption').slideDown(250); //.fadeIn(250)
			},
			function(){
				$(this).find('.if-upload-caption').slideUp(250); //.fadeOut(205)
			}
		);
		this._initControls();
	}
	,
	/*
	 * Incializa controles para manipular archivos
	 */
	_initControls : function()
	{
		var that = this;
		
		$(this.UPLOAD_AREA + ' .eli-img-btn').off();
		
		$(this.UPLOAD_AREA + ' .eli-img-btn').on('click',function(ev){
			ev.preventDefault();
			ev.stopPropagation();
			var filename = $(this).parent().parent().parent().parent().data('filename');
			that.removeFile(filename);
		});
		
		$(this.UPLOAD_AREA + ' .izq-img-btn').off();
		
		$(this.UPLOAD_AREA + ' .izq-img-btn').on('click',function(ev){
			ev.preventDefault();
			ev.stopPropagation();
			var filename = $(this).parent().parent().parent().parent().data('filename');
			that.desplazarIzq(filename);
		});
		
		$(this.UPLOAD_AREA + ' .der-img-btn').off();
		
		$(this.UPLOAD_AREA + ' .der-img-btn').on('click',function(ev){
			ev.preventDefault();
			ev.stopPropagation();
			var filename = $(this).parent().parent().parent().parent().data('filename');
			that.desplazarDer(filename);
		});
		
	}
	,
	/*
	 * Funcion que cargar archivo al servidor
	 */
	_submit : function()
	{
		//Revisa si el navegador soporta todas las caracteristicas de File API
		if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			//revisa si esta vacio el input file
			if( !$(this.UPLOAD_AREA + ' #if-upload-image-file').val())
			{
				$(this.UPLOAD_AREA_OUTPUT+' > p').html("No hay archivo");
				return false;
			}

			//Obtiene el tamaño del archivo
			var fsize = $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0].size;
			//Obtiene el tipo del archivo
			var ftype = $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0].type;
			
			/****************************
			 * SOLO ARCHIVOS PERMITIDOS
			 ****************************/
			//BANDERA PARA SABER SI EL TIPO DE ARCHIVO ES ACEPTADO
			var ftype_flag = true;
			for(var i=0;i<this.UPLOAD_FILE_TYPES.length;i++)
			{
				if(ftype==this.UPLOAD_FILE_TYPES[i])
				{
					ftype_flag = false;
					break;
				}	
			}
			//SI PASA TODO EL CICLO Y QUEDA COMO TRUE ENTONCES HAY QUE INTERRUMPIR EJECUCION DE CARGA DE ARCHIVO
			if(ftype_flag)
			{
				$(this.UPLOAD_AREA_OUTPUT+' > p').html(
						"<b>"+ftype+"</b> tipo de archivo no soportado!");
				return false;
			}
			/****************************
			 * FIN SOLO ARCHIVOS PERMITIDOS
			 ****************************/
			
			//Verifica Tamaño del archivo
			if(fsize>this.UPLOAD_FILE_SIZE_MAX) 
			{
				$(this.UPLOAD_AREA_OUTPUT+' > p').html("<b>"+this._bytesToSize(fsize) +
					"</b> muy grande! <br />por favor reduzca el tamaño del archivo."+
					"<br /> El límite es: " +
					this._bytesToSize(this.UPLOAD_FILE_SIZE_MAX));
				return false;
			}
			
			//Inicialización de parametros de subida AJAX
			var		fd = new FormData(),
					xhr = new XMLHttpRequest()
					name = this._getEmptyFileName()
					;
			
			//Si se tiene un nombre disponible 
			//se procede a la subida del archivo
			if(name)
			{
				fd.append('contents', $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0]);
				fd.append('name', name);
				fd.append('upload_url', this.UPLOAD_URL );
				
				xhr.open('POST', this.PLG_URL + 'savefile.php');
				xhr.addEventListener('error', function(ev){
					console.log('Upload Error!');
				}, false);
				xhr.addEventListener('load', function(ev) {
					//$('input[name=file_foto]').val('<?= $FILE_NAME ?>.jpg');
					console.log(ev);
				}, false);
				
				var that = this;
				
				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4)
					{
						$(that.UPLOAD_AREA + ' #if-upload-image-file').val('');
						var r = JSON.parse(xhr.responseText);
						if(r.success)
						{
							that._insertFile(r.name,r.filename,ftype);
						}
					}
				}
				
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				
				$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').addClass('if-loading');
				$(this.UPLOAD_AREA_INPUT).hide();
				
				xhr.send(fd);
			}
			else
			{
				$(this.UPLOAD_AREA_OUTPUT+' > p').html("no puedes subir mas archivos");
				return false;
			}
			
			return true;
					
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$(this.UPLOAD_AREA_OUTPUT+' p').html("Lo siento, pero necesitamos que por favor actualice su navegador, se requieren "+
				"ciertas caracteristicas que su navegador no dispone!");
			return false;
		}
	}
	,
	/*
	 * Funcion que inserta el archivo en el area de subida de archivos
	 */
	_insertFile: function(name,filename,ftype)
	{
		$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').removeClass('if-loading');
		$(this.UPLOAD_AREA_INPUT).show();
		
		if($(this.UPLOAD_AREA + " input[name="+name+"]").length>0)
		{
			//sobrescribir el input si existe
			$(this.UPLOAD_AREA + " input[name="+name+"]").val(filename);
		}
		else
		{
			//crear el input si no existe
			$(this.UPLOAD_AREA_OUTPUT+' > .row > #if-upload-image-loader').before(
				'<input type="hidden" name="' + name + '" value="' + filename + '" />'
			);
		}
		
		if($(this.UPLOAD_AREA + " input[name="+name+"]").next('#if-upload-thumb-'+name+'').length>0)
		{
			$(this.UPLOAD_AREA + " input[name="+name+"]").next().remove();
		}
		
		var file_str = '';
		var desp_str = '';
		
		if(ftype=='image/jpeg' || ftype=='image/png' || ftype=='image/gif')
		{
			file_str = 	'<img '+
						'class="img-thumbnail"'+
						'alt="' + name + '"'+
						'src="' + this.UPLOAD_URL + 'thumb_' + filename + '?' + hex_md5(Math.random()) + '">';
		}
		else
		{
			file_str = '<img '+
						'class="img-thumbnail"'+
						'alt="PDF" '+
						'src="'+this.PLG_URL+'img/PDF-Icon.jpg">';

		}
		
		desp_str = 	'<a class="label label-info izq-img-btn" '+
						' title="Desplazar izquierda">'+
						'<'+
					'</a>'+
					'<a	class="label label-info der-img-btn" '+
						'title="Desplazar derecha">'+
						'>'+
					'</a>';

		
		//Colocar la imagen despues del input que guarda ruta de la imagen
		$(this.UPLOAD_AREA + " input[name="+name+"]").after(
				'<div id="if-upload-thumb-'+name+'" class="col-md-2" data-filename="'+name
					+'" data-file="'+filename+'" data-ftype="'+ftype+'">'+
					'<div class="if-upload-thumbnail">' +
						'<div class="if-upload-caption">'+
							'<p>'+
								'<a '+
								  ' class="label label-danger eli-img-btn" '+
								  ' title="Borrar imágen">'+
									'Borrar'+
								'</a>'+
							'</p>'+
							'<p>'+
								desp_str +
							'</p>'+
						'</div>'+
						file_str +
					'</div>'+
				'</div>'
		);
		
		this.FILES[name] = filename;
		this._initControlsCaption();
		
		if(this._isFullCountFiles())
		{
			$(this.UPLOAD_AREA_INPUT).hide();
			$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
	/*
	 * 
	 */
	removeFile: function(img)
	{
		var that = this;
		//La eliminacion es local, no se puede borrar del sistema 
		//de archivos hasta tanto no se actualice la bd
		
		if(this.DELETE_CONFIRMATION)
		{
			IF_MODAL.confirm('¿Esta usted seguro de eliminar este archivo?',
				function(r){
					if(r)
					{
						that._shiftFiles(img);
					}

			});
		}
		else
		{
			that._shiftFiles(img);
		}

	}
	,
	/*
	 * Recibe como parametro el archivo que se borro,
	 * es decir donde empieza el shift. Todos los archivos despues de este
	 * deben correrse a la izquierda
	 */
	_shiftFiles : function (start)
	{
		var prev_i = null;
		var i_n = 1;
		var canShift = false;
		
		for (var i in this.FILES)
		{
			if(canShift)
			{
				if(prev_i!==null)
				{
					//si hay regitrada un archivo en esta posicion
					if(this.FILES[i].length>0)
					{
						var ftype = $(this.UPLOAD_AREA + " #if-upload-thumb-" + i).data("ftype");
						this._insertFile(prev_i,this.FILES[i],ftype);
						prev_i = i;
					}
					else
					{
						//REVISAR! A la seguda eliminacion se oculta carga de archivos
						this.FILES[prev_i] = "";
						$(this.UPLOAD_AREA + " input[name="+prev_i+"]").val("");
						$(this.UPLOAD_AREA + " input[name="+prev_i+"]").next().remove();
						prev_i = i;
					}
				}
			}
			else
			{
				if(i===start)
				{
					canShift = true;
					prev_i = i;
				}
			}
			
			if(i_n===this.FILES_COUNT)
			{
				this.FILES[i] = "";
				$(this.UPLOAD_AREA + " input[name="+i+"]").val("");
				$(this.UPLOAD_AREA + " input[name="+i+"]").next().remove();
			}
			
			i_n++;
		} 
		
		if(!this._isFullCountFiles())
		{
			$(this.UPLOAD_AREA_INPUT).show();
		}
		
	}
	,
	/*
	 * Funcion para desplazar archivos a la izquierda
	 */
	desplazarIzq : function(file)
	{
		var file2 = file;
		
		var prev_i = null;
		
		for (var i in this.FILES)
		{ 
			if(i===file)
			{
				var file1 = prev_i;
				break;
				
			}
			prev_i = i;
		} 
		
		if(file1!==null)
		{
			this._switchFiles(file1,file2);
		}
		else
		{
			console.log("No se puede mover mas a la izquierda");
		}
	}
	,
	/*
	 * Funcion para desplazar archivos a la derecha
	 */
	desplazarDer : function(file)
	{
		var file1 = file;
		
		var prev_i = null;
		var file2 = null;
		
		for (var i in this.FILES)
		{ 
			if(prev_i===file && this.FILES[i].length>0)
			{
				file2 = i;
				break;
			}
			prev_i = i;
		} 
		
		if(file2!==null)
		{
			this._switchFiles(file1,file2);
		}
		else
		{
			console.log("No se puede mover mas a la derecha");
		}
	}
	,
	/*
	 * Intercambia posicion de dos archivos
	 */
	_switchFiles : function(file1,file2)
	{
		var filename1 = this.FILES[file1];
		var filename2 = this.FILES[file2];
		
		var ftype1 = $(this.UPLOAD_AREA + " #if-upload-thumb-" + file1).data("ftype");
		var ftype2 = $(this.UPLOAD_AREA + " #if-upload-thumb-" + file2).data("ftype");

		this._insertFile(file1,filename2,ftype2);
		this._insertFile(file2,filename1,ftype1);
	}
	,
	/*
	 * Obtiene proximo nombre de archivo disponible
	 */
	_getEmptyFileName: function()
	{
		for (var i in this.FILES)
		{ 
			if(this.FILES[i].length===0)
			{
				return i;
			}
		} 
		return false;
	}
	,
	/*
	 * Funcion para conocer si esta lleno el contenedor de archivos
	 */
	_isFullCountFiles : function()
	{
		for (var i in this.FILES)
		{ 
			if(this.FILES[i].length===0)
			{
				return false;
			}
		} 
		return true;
	}
	,
	/*
	 * Transaforma los bytes en un tamaño legible por un ser humano
	 */
	_bytesToSize: function(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return '0 Bytes';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
};





