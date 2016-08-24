/******************************************************************
 * Clase JavaScript MAIN basado en cwf.main
 * v1.3.0
 * 
 * Clase principal JavaScript del framework if.
 * 
 * Dependencias: jquery
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 *****************************************************************/

var IF_MAIN = {
	CI_INDEX: '', //Codeigniter index.php url
	CANVAS_SELECTOR: '#canvas',
	CSFR_NAME: '',
	CSFR_TOKEN: '',
	DATEPICKER_CONFIG: {
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		language: 'es',
		format: 'dd/mm/yy'
	},
	//No modificar estas
	CANVAS_LOCK: 0,
	UNSAVED_DATA: 0,
	//-----------------------------------------------------------------

	/*
	 * init
	 * 
	 * Instrucciones de inicializacion 
	 * 
	 * @param objetc cnf		variable de configuracion 
	 *							con pares clave/valor
	 */
	init: function (cnf)
	{
		cnf = cnf || {};

		//adaptando altura de canvas a <body>
		var bodyH = $("#body").height();
		$(IF_MAIN.CANVAS_SELECTOR).height(bodyH);

		//Listener para modo móvil/no-móvil. Es necesario para algunos
		//dispositivos que se pueden rotar y cambiar de modo "en vivo"
		var $win = $(window);
		$win.on('resizeEnd', function ()
		{
			IF_MAIN.VIEWPORT = {
				width: $win.width(),
				height: $win.height()
			};
			IF_MAIN.IS_MOBILE = IF_MAIN.VIEWPORT.width <= 768;
		}).trigger('resizeEnd');
	}

	//-----------------------------------------------------------------

	/*
	 * ajax
	 *
	 * Funcion para realizar solicitudes ajax. 
	 * Depende de libreria jQuery.
	 *
	 * @param objetc cnf		variable de configuracion 
	 *							con pares clave/valor
	 * ver: http://api.jquery.com/jQuery.ajax/
	 */
	, ajax: function (cnf)
	{
		cnf.type = 'POST';
		cnf.dataType = cnf.dataType || 'json';
		cnf.data = cnf.data || {};

		if (IF_MAIN.CSFR_NAME.length > 0)
		{
			cnf.data[IF_MAIN.CSFR_NAME] = IF_MAIN.CSFR_TOKEN;
		}

		cnf.url = cnf.url || IF_MAIN.CI_INDEX + cnf.controller;
		cnf.success = cnf.callback;

		$.ajax(cnf);
	}

	//-----------------------------------------------------------------

	/*
	 * loadCompos
	 *
	 * Cargar datos (composite) desde el servidor hacia un objetivo 
	 * especifico en la vista (div html). Depende de libreria jQuery.
	 *
	 * @param objetc cnf		variable de configuracion 
	 *							con pares clave/valor
	 *		
	 *		cnf.target:			identificador de objetivo (#div)
	 *		cnf.fadeAnimation	bool que indica si hace un efecto fade al cargar un elemento
	 * 
	 * ver: http://api.jquery.com/load/
	 */
	, loadCompos: function (cnf)
	{
		$(cnf.target)
			.empty()
			.addClass('loading')
			.load(
				cnf.url || IF_MAIN.CI_INDEX + cnf.controller,
				cnf.data || null,
				function (r)
				{
					(cnf.callback || $.noop)(r);
					$(this).removeClass('loading');
				}
			)
			;
	}

	//-----------------------------------------------------------------

	/*
	 * loadCanvas
	 * 
	 * Carga una vista en el canvas de la aplicacion.
	 * 
	 * @param objetc cnf		variable de configuracion 
	 *							con pares clave/valor
	 */
	, loadCanvas: function (cnf)
	{
		if (IF_MAIN.canvasLocked())
			return;

		IF_MAIN.canvasLock();

		cnf.target = IF_MAIN.CANVAS_SELECTOR;

		cnf._cb = cnf.callback; //temporal
		cnf.callback = function (r)
		{
			IF_MAIN.canvasUnlock();
			(cnf._cb || $.noop)(r);
		};

		IF_MAIN.loadCompos(cnf);

		//cierra dialogo si se encuentra abierto
		if (typeof IF_MODAL != 'undefined')
		{
			IF_MODAL.close();
		}
	}

	//-----------------------------------------------------------------
	//MONITOR DEL CANVAS
	//-----------------------------------------------------------------

	, canvasLock: function ()
	{
		IF_MAIN.CANVAS_LOCK++;
	}

	, canvasUnlock: function ()
	{
		IF_MAIN.CANVAS_LOCK--;
		if (IF_MAIN.CANVAS_LOCK < 0)
			IF_MAIN.CANVAS_LOCK = 0;
	}

	, canvasLocked: function ()
	{
		return IF_MAIN.CANVAS_LOCK > 0;
	}

	, canvasShowLoading: function ()
	{
		$(IF_MAIN.CANVAS_SELECTOR).empty()
			.append('<div class="cwfComposLoaderL"></div>');
	}

	//-----------------------------------------------------------------

	//Escucha cambios en los formularios de sel
	, prepareFormForUnsavedData: function (sel)
	{
		$(sel + ' input,' + sel + ' textarea')
			.keypress(MAIN.setUnsavedData)
			;
		$(sel + ' select').change(MAIN.setUnsavedData);
	}

	//-----------------------------------------------------------------

	//Revisar porque depende de CWF_DIALOG
	, confirmUnsavedData: function (callback, cbParams, scope)
	{
		if (IF_MAIN.UNSAVED_DATA)
		{
			CWF_DIALOG.confirm(
				'Se perderán los cambios no guardados. ¿Continuar?'
				, function (si)
				{
					if (si)
					{
						IF_MAIN.UNSAVED_DATA = 0;
						callback.call(scope || window, cbParams);
					}
				}
			, null
				, {
					modal: 1
				}
			);
		} else
			callback.call(scope || window, cbParams);
	}

	//-----------------------------------------------------------------

	, setUnsavedData: function (status)
	{
		if (typeof status == 'object' && status.which && status.which == 27)
		{
			return;
		}

		if (typeof status == 'undefined')
			var status = true;
		IF_MAIN.UNSAVED_DATA = status;
	}
};

//===============================================
//FUNCIONES DE UTILIDAD
//===============================================
//Serialización de Formulario como JSON
$.fn.serializeObject = function ()
{
	var o = {};
	var a = this.serializeArray();
	$.each(a, function () {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

//resizeEnd: Custom event para ejecutar algo al final de un resize
//(mejora rendimiento, especialmente en movil).
$(window).resize(function ()
{
	if (this.resizeTO)
	{
		clearTimeout(this.resizeTO);
	}
	this.resizeTO = setTimeout(function ()
	{
		$(this).trigger('resizeEnd');
	}, 250);
});

//Parsing de fecha a ISO 8601
Date.prototype.toISO8601 = function ()
{
	var
		day = this.getDate(),
		mon = this.getMonth() + 1,
		hour = this.getHours(),
		minute = this.getMinutes(),
		second = this.getSeconds()
		;

	if (day < 10)
		day = '0' + day;
	if (mon < 10)
		mon = '0' + mon;
	if (hour < 10)
		hour = '0' + hour;
	if (minute < 10)
		minute = '0' + minute;
	if (second < 10)
		second = '0' + second;

	return this.getFullYear()
		+ '-' + mon + '-' + day + ' ' + hour + ':' + minute + ':' + second
		;
};
