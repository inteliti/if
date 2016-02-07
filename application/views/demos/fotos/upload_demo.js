var UPLOAD_DEMO = {
	
	//mi_nombre_objeto debe ser un nombre unico en el que se instanciara
	//la clase IF_UPLOAD para futura referencia.
	detail: function (id)
	{
		IF_MAIN.loadCompos({
			target: '#upload-detail',
			controller: 'upload/detail/' + (id || '-1') + '/mi_nombre_objeto',
			callback: function () {

			}
		});
	}
	
	,save: function ()
	{
		
		//procesar resto del formulario....
		
		
		//Subir fotos, id se usara para crear el directorio donde se
		//alojaran las fotos. Podria ser p.e. el ID del elemento a guardar
		//(cuidado en los casos de distintos objetos con mismo ID)
		//Para este ejemplo generamos un id aleatorio.
		var id = Math.floor((Math.random() * 1000) + 1);
		mi_nombre_objeto.upload(id, function (errorCode)
		{
			if(!errorCode)
			{
				alert('Archivos subidos exitosamente.');
			}
			else
			{
				alert('Error, codigo: '+errorCode);
			}
		});
		
		
	}
}

