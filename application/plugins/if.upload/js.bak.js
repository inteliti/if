var IF_UPLOAD = {
	
	PLG_URL : null,
	UPLOAD_PATH : null,
	MAX_COUNT_FILE : 1,
	FILES : null,
	FILES_COUNT : 0,
	UPLOAD_AREA : '#if-upload-area',
	UPLOAD_AREA_INPUT : '#if-upload-input',
	UPLOAD_AREA_OUTPUT : '#if-upload-output',
	
	init: function(cnf)
	{
		IF_UPLOAD.PLG_URL = cnf.plg_url;
		IF_UPLOAD.UPLOAD_PATH = cnf.upload_path;
		IF_UPLOAD.MAX_COUNT_FILE = cnf.max_count_file;
		IF_UPLOAD.FILES = cnf.files_array;
		
		//restore count of files to 0, before calculate
		IF_UPLOAD.FILES_COUNT = 0;
		
		for (var k in IF_UPLOAD.FILES) {
			if (IF_UPLOAD.FILES.hasOwnProperty(k)) {
			   ++IF_UPLOAD.FILES_COUNT;
			}
		}
		
		//console.log(IF_UPLOAD.FILES);
		
		$('#if-upload-image-file').on('change',function(e){
			IF_UPLOAD._submit();
		});
		
		IF_UPLOAD._initControlsCaption();
		
		if(IF_UPLOAD._isFullCountFiles())
		{
			$('#if-upload-input').hide();
			$('#btn-upload-file').hide();
			$('#if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
	_initControlsCaption : function()
	{
		$('.if-upload-thumbnail').hover(
			function(){
				$(this).find('.if-upload-caption').slideDown(250); //.fadeIn(250)
			},
			function(){
				$(this).find('.if-upload-caption').slideUp(250); //.fadeOut(205)
			}
		);
	}
	
	,
	_submit : function()
	{
		//check whether browser fully supports all File API
		if (window.File && window.FileReader && window.FileList && window.Blob)
		{
			if( !$('#if-upload-image-file').val()) //check empty input filed
			{
				$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' > p').html("No hay archivo");
				console.log('No hay archivo');
				return false
			}

			var fsize = $('#if-upload-image-file')[0].files[0].size; //get file size
			var ftype = $('#if-upload-image-file')[0].files[0].type; // get file type


			//allow only valid image file types 
			switch(ftype)
			{
				case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
					break;
				default:
					$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' > p').html(
							"<b>"+ftype+"</b> tipo de archivo no soportado!");
					return false
			}

			//Allowed file size is less than 1 MB (1048576)
			if(fsize>1048576) 
			{
				$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' > p').html("<b>"+UPLOAD._bytesToSize(fsize) +
					"</b> muy grande! <br />por favor reduzca el tamano del archivo.");
				return false
			}
								
			var		fd = new FormData(),
					xhr = new XMLHttpRequest()
					name = IF_UPLOAD._getEmptyFileName()
					;
					
			if(name)
			{
				fd.append('contents', $('#if-upload-image-file')[0].files[0]);
				fd.append('name', name);
				fd.append('upload_path', IF_UPLOAD.UPLOAD_PATH );
				xhr.open('POST', IF_UPLOAD.PLG_URL + 'savefile.php');
				xhr.addEventListener('error', function(ev) {
					console.log('Upload Error!');
				}, false);
				xhr.addEventListener('load', function(ev) {
					//$('input[name=file_foto]').val('<?= $FILE_NAME ?>.jpg');
					//console.log(ev);
				}, false);
				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4) {
						$('#if-upload-image-file').val('');
						var r = JSON.parse(xhr.responseText);
						if(r.success)
						{
							IF_UPLOAD._insertImage(r.name,r.filename);
						}
					}
				}
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				$('#if-upload-image-loader > div').addClass('if-loading');
				$('#btn-upload-file').hide();
				xhr.send(fd);
			}
			else
			{
				$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' > p').html("no puedes subir mas archivos");
				return false
			}
					
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' p').html("Por favor actualice su navegador, se requieren "+
				"ciertas caracteristicas que su navegador no dispone!");
			return false;
		}
	}
	,
	_insertImage: function(name,filename)
	{
		$('#if-upload-image-loader > div').removeClass('if-loading');
		$('#btn-upload-file').show();
		
		if($("input[name="+name+"]").length>0)
		{
			//sobrescribir el input si existe
			$("input[name="+name+"]").val(filename);
		}
		else
		{
			//crear el input si no existe
			$(IF_UPLOAD.UPLOAD_AREA_OUTPUT+' > .row > #if-upload-image-loader').before(
				'<input type="hidden" name="' + name + '" value="' + filename + '" />'
			);
		}
		
		if($("input[name="+name+"]").next('#if-upload-thumb-'+name+'').length>0)
		{
			$("input[name="+name+"]").next().remove();
		}
		
		//Colocar la imagen despues del input que guarda ruta de la imagen
		$("input[name="+name+"]").after(
				'<div id="if-upload-thumb-'+name+'" class="col-md-2">'+
					'<div class="if-upload-thumbnail">' +
						'<div class="if-upload-caption">'+
							'<p>'+
								'<a onclick="IF_UPLOAD.removeImage(\''+name+'\')" '+
								  ' class="label label-danger" '+
								  ' title="Borrar imágen">'+
									'Borrar'+
								'</a>'+
							'</p>'+
							'<p>'+
								'<a onclick="IF_UPLOAD.desplazarIzq(\''+name+'\')" '+
								  ' class="label label-info" '+
								  ' title="Desplazar izquierda">'+
									'<'+
								'</a>'+
								'<a	onclick="IF_UPLOAD.desplazarDer(\''+name+'\')" '+
									'class="label label-info" '+
									'title="Desplazar derecha">'+
									'>'+
								'</a>'+
							'</p>'+
						'</div>'+
						'<img '+
							'class="img-thumbnail"'+
							'alt="' + name + '"'+
							'src="' + IF_UPLOAD.UPLOAD_PATH + filename + '?' + hex_md5(Math.random()) + '">'+
					'</div>'+
				'</div>'
		);
		IF_UPLOAD.FILES[name] = filename;
		IF_UPLOAD._initControlsCaption();
		//console.log(IF_UPLOAD.FILES);
		if(IF_UPLOAD._isFullCountFiles())
		{
			$('#if-upload-input').hide();
			$('#btn-upload-file').hide();
			$('#if-upload-image-loader > div').removeClass('if-upload-image-new');
		}
	}
	,
	removeImage: function(img)
	{
		//La eliminacion es local, no se puede borrar del sistema 
		//de archivos hasta tanto no se actualice la bd
		
		
		IF_UPLOAD._shiftImages(img);
		
		
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
		
		for (var i in IF_UPLOAD.FILES)
		{ 
			//console.log('Iteracion '+ i_n );
			if(canShift)
			{
				if(prev_i!==null)
				{
					//si hay regitrada una imagen en esta posicion
					if(IF_UPLOAD.FILES[i].length>0)
					{
						//console.log('mover '+ i +' a '+prev_i);
						IF_UPLOAD._insertImage(prev_i,IF_UPLOAD.FILES[i]);
						prev_i = i;
					}
					else
					{
						//console.log('mover vacio a '+ i);
						IF_UPLOAD.FILES[prev_i] = "";
						$("input[name="+prev_i+"]").val("");
						$("input[name="+prev_i+"]").next().not("#if-upload-image-loader").remove();
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
			
			//console.log("el i n es "+ i_n+" y el len es "+IF_UPLOAD.FILES_COUNT);
			if(i_n===IF_UPLOAD.FILES_COUNT)
			{
				//console.log('mover vacio al ultimo '+ i);
				IF_UPLOAD.FILES[i] = "";
				$("input[name="+i+"]").val("");
				$("input[name="+i+"]").next().not("#if-upload-image-loader").remove();
			}
			
			
			i_n++;
			
			//console.log(IF_UPLOAD.FILES);
		} 
		
		if(!IF_UPLOAD._isFullCountFiles())
		{
			$('#if-upload-input').show();
			$('#btn-upload-file').show();
			$('#if-upload-image-loader > div').addClass('if-upload-image-new');
		}
		
	}
	,
	desplazarIzq : function(img)
	{
		var img2 = img;
		
		var prev_i = null;
		
		for (var i in IF_UPLOAD.FILES)
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
			IF_UPLOAD._switchImages(img1,img2);
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
		
		for (var i in IF_UPLOAD.FILES)
		{ 
			if(prev_i===img && IF_UPLOAD.FILES[i].length>0)
			{
				img2 = i;
				break;
			}
			prev_i = i;
		} 
		
		if(img2!==null)
		{
			IF_UPLOAD._switchImages(img1,img2);
		}
		else
		{
			console.log("No se puede mover mas a la derecha");
		}
	}
	,
	_switchImages : function(img1,img2)
	{
		var filename1 = IF_UPLOAD.FILES[img1];
		var filename2 = IF_UPLOAD.FILES[img2];

		IF_UPLOAD._insertImage(img1,filename2);
		IF_UPLOAD._insertImage(img2,filename1);
	}
	,
	_getEmptyFileName: function()
	{
		for (var i in IF_UPLOAD.FILES)
		{ 
			if(IF_UPLOAD.FILES[i].length===0)
			{
				return i;
			}
		} 
		return false;
	}
	,
	_isFullCountFiles : function()
	{
		for (var i in IF_UPLOAD.FILES)
		{ 
			if(IF_UPLOAD.FILES[i].length===0)
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
	
}


