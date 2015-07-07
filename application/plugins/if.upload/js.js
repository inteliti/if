/*****************************************************
 * Constructor
 *****************************************************/
var IF_UPLOAD = function() {};

/*****************************************************
 * Prototipo de clase IF_UPLOAD
 *****************************************************/
IF_UPLOAD.prototype = {
	
	PLG_URL : null,
	PLG_PATH : null,
	UPLOAD_PATH : null,
	MAX_COUNT_FILE : 1,
	FILES : null,
	FILES_COUNT : 0,
	UPLOAD_AREA : null,
	UPLOAD_AREA_INPUT : null,
	UPLOAD_AREA_OUTPUT : null,
	UPLOAD_FILE_TYPES : null,
	UPLOAD_FILE_SIZE_MAX: null,
	
	init: function(cnf)
	{
		this.PLG_URL = cnf.plg_url;
		this.PLG_PATH = cnf.plg_path;
		this.UPLOAD_PATH = cnf.upload_path;
		this.MAX_COUNT_FILE = cnf.max_count_file;
		this.FILES = cnf.files_array;
		
		this.UPLOAD_AREA = cnf.upload_area;
		this.UPLOAD_AREA_INPUT = cnf.upload_area_input; 
		this.UPLOAD_AREA_OUTPUT = cnf.upload_area_output;
		this.UPLOAD_FILE_TYPES = cnf.upload_files_types;
		this.UPLOAD_FILE_SIZE_MAX = cnf.upload_file_size_max;
		
		//restore count of files to 0, before calculate
		this.FILES_COUNT = 0;
		
		for (var k in this.FILES) {
			if (this.FILES.hasOwnProperty(k)) {
			   ++this.FILES_COUNT;
			}
		}
		
		//console.log(this.FILES);
		
		var that = this;
		
		$(this.UPLOAD_AREA + ' #if-upload-image-file').on('change',function(e){
			that._submit();
		});
		
		this._initControlsCaption();
		
		if(this._isFullCountFiles())
		{
			$(this.UPLOAD_AREA + ' #if-upload-input').hide();
			$(this.UPLOAD_AREA + ' #btn-upload-file').hide();
			$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
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
	_initControls : function()
	{
		var that = this;
		
		$(this.UPLOAD_AREA + ' .eli-img-btn').on('click',function(){
			var filename = $(this).parent().parent().parent().parent().data('filename');
			that.removeImage(filename);
		});
		
		$(this.UPLOAD_AREA + ' .izq-img-btn').on('click',function(){

			var filename = $(this).parent().parent().parent().parent().data('filename');
			console.log(filename);
			that.desplazarIzq(filename);
		});
		
		$(this.UPLOAD_AREA + ' .der-img-btn').on('click',function(){

			var filename = $(this).parent().parent().parent().parent().data('filename');
			console.log(filename);
			that.desplazarDer(filename);
		});
		
	}
	
	
	,
	_submit : function()
	{
		//check whether browser fully supports all File API
		if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			if( !$(this.UPLOAD_AREA + ' #if-upload-image-file').val()) //check empty input filed
			{
				$(this.UPLOAD_AREA_OUTPUT+' > p').html("No hay archivo");
				return false
			}

			var fsize = $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0].size; //get file size
			var ftype = $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0].type; // get file type


			//allow only valid image file types 
			var ftype_flag = true; //BANDERA PARA SABER SI EL TIPO DE ARCHIVO ES ACEPTADO, 
			
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
				return false
			}
			
			if(fsize>this.UPLOAD_FILE_SIZE_MAX) 
			{
				
				$(this.UPLOAD_AREA_OUTPUT+' > p').html("<b>"+this._bytesToSize(fsize) +
					"</b> muy grande! <br />por favor reduzca el tamano del archivo."+
					"<br /> El límite es: " +
					this._bytesToSize(this.UPLOAD_FILE_SIZE_MAX));
				return false
			}
								
			var		fd = new FormData(),
					xhr = new XMLHttpRequest()
					name = this._getEmptyFileName()
					;
					
			if(name)
			{
				fd.append('contents', $(this.UPLOAD_AREA + ' #if-upload-image-file')[0].files[0]);
				fd.append('name', name);
				fd.append('upload_path', this.UPLOAD_PATH );
				xhr.open('POST', this.PLG_URL + 'savefile.php');
				xhr.addEventListener('error', function(ev) {
					console.log('Upload Error!');
				}, false);
				xhr.addEventListener('load', function(ev) {
					//$('input[name=file_foto]').val('<?= $FILE_NAME ?>.jpg');
					//console.log(ev);
				}, false);
				
				var that = this;
				
				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4) {
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
				return false
			}
			
			return true;
					
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$(this.UPLOAD_AREA_OUTPUT+' p').html("Por favor actualice su navegador, se requieren "+
				"ciertas caracteristicas que su navegador no dispone!");
			return false;
		}
	}
	,
	_insertFile: function(name,filename,ftype)
	{
		$(this.UPLOAD_AREA + ' #if-upload-image-loader').removeClass('if-loading');
		console.log($(this.UPLOAD_AREA_INPUT));
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
		
		
		file_str = 	'<img '+
					'class="img-thumbnail"'+
					'alt="' + name + '"'+
					'src="' + this.UPLOAD_PATH + 'thumb_' + filename + '?' + hex_md5(Math.random()) + '">';
		desp_str = 	'<a onclick="IF_UPLOAD.desplazarIzq(\''+name+'\')" '+
						' class="label label-info" '+
						' title="Desplazar izquierda">'+
						'<'+
					'</a>'+
					'<a	onclick="IF_UPLOAD.desplazarDer(\''+name+'\')" '+
						'class="label label-info" '+
						'title="Desplazar derecha">'+
						'>'+
					'</a>';
		
		//Colocar la imagen despues del input que guarda ruta de la imagen
		$(this.UPLOAD_AREA + " input[name="+name+"]").after(
				'<div id="if-upload-thumb-'+name+'" class="col-md-2" data-filename="'+name+'" data-file="'+filename+'">'+
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
		//console.log(this.FILES);
		if(this._isFullCountFiles())
		{
			console.log($(this.UPLOAD_AREA_INPUT));
			$(this.UPLOAD_AREA_INPUT).hide();
			$(this.UPLOAD_AREA + ' #if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
	removeImage: function(img)
	{
		var that = this;
		//La eliminacion es local, no se puede borrar del sistema 
		//de archivos hasta tanto no se actualice la bd
		IF_MODAL.confirm('¿Esta usted seguro de eliminar este archivo?',
			function(r){
				if(r)
				{
					that._shiftImages(img);
				}
				
		});
	}
	,
	//recibe como parametro la imagen que se borro,
	//es decir donde empieza el shift. todas las imagenes despues de esas
	//deben correrse a la izquierda
	_shiftImages : function (start)
	{
		
		var prev_i = null;
		var i_n = 1;
		var canShift = false;
		
		for (var i in this.FILES)
		{ 
			//console.log('Iteracion '+ i_n );
			if(canShift)
			{
				if(prev_i!==null)
				{
					//si hay regitrada una imagen en esta posicion
					if(this.FILES[i].length>0)
					{
						//console.log('mover '+ i +' a '+prev_i);
						this._insertImage(prev_i,this.FILES[i]);
						prev_i = i;
					}
					else
					{
						//console.log('mover vacio a '+ i);
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
			
			//console.log("el i n es "+ i_n+" y el len es "+this.FILES_COUNT);
			if(i_n===this.FILES_COUNT)
			{
				//console.log('mover vacio al ultimo '+ i);
				this.FILES[i] = "";
				$(this.UPLOAD_AREA + " input[name="+i+"]").val("");
				$(this.UPLOAD_AREA + " input[name="+i+"]").next().remove();
			}
			
			
			i_n++;
			
			//console.log(this.FILES);
		} 
		
		if(!this._isFullCountFiles())
		{
			$(this.UPLOAD_AREA + ' #if-upload-input').show();
		}
		
	}
	,
	desplazarIzq : function(img)
	{
		var img2 = img;
		
		var prev_i = null;
		
		for (var i in this.FILES)
		{ 
			if(i===img)
			{
				var img1 = prev_i;
				break;
				
			}
			prev_i = i;
		} 
		
		if(img1!==null)
		{
			this._switchImages(img1,img2);
		}
		else
		{
			console.log("No se puede mover mas a la izquierda");
		}
	}
	,
	desplazarDer : function(img)
	{
		var img1 = img;
		
		var prev_i = null;
		var img2 = null;
		
		for (var i in this.FILES)
		{ 
			if(prev_i===img && this.FILES[i].length>0)
			{
				img2 = i;
				break;
			}
			prev_i = i;
		} 
		
		if(img2!==null)
		{
			this._switchImages(img1,img2);
		}
		else
		{
			console.log("No se puede mover mas a la derecha");
		}
	}
	,
	_switchImages : function(img1,img2)
	{
		var filename1 = this.FILES[img1];
		var filename2 = this.FILES[img2];

		this._insertImage(img1,filename2);
		this._insertImage(img2,filename1);
	}
	,
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
	_bytesToSize: function(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return '0 Bytes';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
};





