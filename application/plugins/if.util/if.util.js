/*****************************************************
 * Clase JavaScript de funciones de utilidad variada
 * v1.0.0
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/
var IF_UTIL = {
	
	/*ATRIBUTOS*/
	
	_selectEstados : null,
	_selectCiudades : null,
	_estados  : ['AMAZONAS','ANZOATEGUI','APURE','ARAGUA','BARINAS','BOLIVAR',
				'CARABOBO','COJEDES','DELTA AMACURO','DISTRITO CAPITAL','FALCON','GUARICO',
				'LARA','MERIDA','MIRANDA','MONAGAS','NUEVA ESPARTA','PORTUGUESA','SUCRE',
				'TACHIRA','TRUJILLO','VARGAS','YARACUY','ZULIA'],
	_ciudades : {'AMAZONAS' : ['PUERTO AYACUCHO'],
				'ANZOATEGUI' : ['ANACO', 'BARCELONA', 'EL TIGRE', 'PUERTO LA CRUZ'],
				'APURE' : ['GUASDUALITO','SAN FERNANDO'],
				'ARAGUA' : ['CAGUA','EL LIMON','LA VICTORIA','MARACAY','SANTA RITA','TURMERO','VILLA DE CURA'],
				'BARINAS' : ['BARINAS'],
				'BOLIVAR' : ['CAICARA DEL ORINOCO','CIUDAD BOLIVAR','CIUDAD GUAYANA','UPATA'],
				'CARABOBO' : ['PUERTO CABELLO','GUACARA','GUIGUE','MARIARA','VALENCIA'],
				'COJEDES' : ['TINAQUILLO','SAN CARLOS'],
				'DELTA AMACURO' : ['TUCUPITA'],
				'DISTRITO CAPITAL' : ['CARACAS'],
				'FALCON' : ['CORO','PUNTO FIJO'],
				'GUARICO' : ['CALABOZO','SAN JUAN DE LOS MORROS','VALLE DE LA PASCUA'],
				'LARA' : ['BARQUISIMETO','CARORA','EL TOCUYO','QUIBOR'],
				'MERIDA' : ['EJIDO','EL VIJIA','MERIDA'],
				'MIRANDA' : ['CHARALLAVE','CUA','GUARENAS','GUATIRE','LOS TEQUES','OCUMARE DEL TUY','SANTA LUCIA','SANTA TERESA DEL TUY'],
				'MONAGAS' : ['MATURIN'],
				'NUEVA ESPARTA' : ['PORLAMAR'],
				'PORTUGUESA' : ['ACARIGUA','ARAURE','GUANARE'],
				'SUCRE' : ['CARUPANO','CUMANA'],
				'TACHIRA' : ['SAN CRISTOBAL','TARIBA'],
				'TRUJILLO' : ['VALERA'],
				'VARGAS' : ['LA GUAIRA'],
				'YARACUY' : ['SAN FELIPE','YARITAGUA'],
				'ZULIA' : ['CABIMAS','CIUDAD OJEDA','MARACAIBO','SANTA BARBARA','MACHIQUES','LA CONCEPCION','LOS PUERTOS DE ALTAGRACIA']}
	
	/*METODOS*/
	
	/*
	 * Metodo para llenar dos campos del tipo select con los valores de las ciudades y estados de Venezuela
	 */
	, fillEstadosCiudades : function(targetEstados, targetCiudades, estado, ciudad)
	{
		var option = $('<option value=""></option>');
		IF_UTIL._selectEstados = $(targetEstados);
		IF_UTIL._selectCiudades = $(targetCiudades);
		
		for (var i = 0; i < IF_UTIL._estados.length; i++){
			IF_UTIL._selectEstados.append(option.clone().attr('value',IF_UTIL._estados[i]).html(IF_UTIL._estados[i]));
		}
		
		if(estado)
		{
			IF_UTIL._fillCiudades(estado,ciudad);
		}
		
		IF_UTIL._selectEstados.on('change',function(){
			var estado = $(this).val();
			IF_UTIL._fillCiudades(estado);
		});
	}
	/*
	 * Metodo privado para llenar con las ciudades de Venezuela un campo del tipo select
	 */
	, _fillCiudades : function(estado,ciudad)
	{
		IF_UTIL._selectCiudades.html('');
		var option = $('<option value=""></option>');
		IF_UTIL._selectEstados.val(estado);
		for (var i = 0; i < IF_UTIL._ciudades[estado].length; i++){
			IF_UTIL._selectCiudades.append(option.clone().attr('value',IF_UTIL._ciudades[estado][i]).html(IF_UTIL._ciudades[estado][i]));
		}
		if(ciudad)
		{
			IF_UTIL._selectCiudades.val(ciudad);
		}
	}
	/*
	 * Metodo para eliminar los ultimos N caracteres de una cadena de caracteres
	 */
	, removeLastN : function(str,n)
	{
		var length = str.length;
		var end = length > n ? length - n : 0;
		return str.substring( 0 , end );
	}
	/*
	 * Metodo para cargar un documento de dentidad en un campo con un select 
	 * para el tipo de persona y un campo de texto para el numero
	 */
	, fillDocIdentidad : function(target,docIdentidad)
	{
		if(docIdentidad.length>0)
		{
			var cedulaRif = /^[JGVE][-][0-9]{8}[-]*[0-9]*$/;
			if(cedulaRif.test(docIdentidad))
			{
				var pre = docIdentidad.substring( 0 , 1 );
				var pos = docIdentidad.substring( 2 , docIdentidad.length );
				
				$( target + ' select[name=pre_doc_identidad]' ).val(pre);
				$( target + ' input[name=pos_doc_identidad]' ).val(pos);
			}
			else
			{
				console.log('aparentemete no coincide con un documento de identidad valido');
			}
		}
		else
		{
			console.log('aparentemete no hay doc identidad');
		}
	}
	/*
	 * Metodo que permite obtener los valores de un campo con un select 
	 * para el tipo de persona y un campo de texto para el numero
	 */
	, getDocIdentidad : function(target)
	{
		var pre = $( target + ' select[name=pre_doc_identidad]' ).val();
		var pos = $( target + ' input[name=pos_doc_identidad]' ).val();
		
		return pre + '-' + pos;
	}
	/*
	 * Metodo para dar formato a un numero
	 */
	, formatNumber : function(numero, decimales, separador_decimal, separador_miles){ 
		numero=parseFloat(numero);
		if(isNaN(numero)){
			return "";
		}

		if(decimales!==undefined){
			// Redondeamos
			numero=numero.toFixed(decimales);
		}

		// Convertimos el punto en separador_decimal
		numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

		if(separador_miles){
			// Añadimos los separadores de miles
			var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
			while(miles.test(numero)) {
				numero=numero.replace(miles, "$1" + separador_miles + "$2");
			}
		}

		return numero;
	}
};
