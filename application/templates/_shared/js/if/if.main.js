/******************************************************************
 * 
 * Clase JavaScript MAIN basado en cwf.main
 * v1.0.0
 * 
 * Clase principal JavaScript del framework if.
 * 
 * Dependencias: jquery, jquery.validation
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 *****************************************************************/

var IF_MAIN = {
	
	CI_INDEX: '',	//Codeigniter index.php url
	DATEPICKER_FORMAT: 'dd/mm/yy',
	CANVAS_SELECTOR: '#canvas',
	
	//NO hacer modificaciones de aqui para abajo!!!

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
	init: function(cnf)
	{
		if (!cnf) cnf = {};
		
		//adaptando altura de canvas a <body>
		var bodyH = $("#body").height();
		$(IF_MAIN.CANVAS_SELECTOR).height(bodyH);
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
	, ajax: function(cnf)
	{
		cnf.type = 'POST';
		
		if (!cnf.dataType)
		{
			 cnf.dataType = 'json';
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
	* 
	* ver: http://api.jquery.com/load/
	*/
	, loadCompos: function(cnf)
	{
		$(cnf.target)
			.empty()
			.addClass('loading')
			.load(
				cnf.url || IF_MAIN.CI_INDEX + cnf.controller,
				cnf.data || null,
				function(r)
				{
					if (cnf.callback)
					{
						cnf.callback(r);
					}
					
					$(this).removeClass('loading');
				}
			)
		;
		return 1;
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
	, loadCanvas: function(cnf)
	{
		if(IF_MAIN.canvasLocked()) return;
		
		IF_MAIN.canvasLock();

		cnf.target = IF_MAIN.CANVAS_SELECTOR;

		//
		cnf._cb = cnf.callback;
		cnf.callback = function(r)
		{
			IF_MAIN.canvasUnlock();
			if(typeof cnf._cb == 'function')
			{
				cnf._cb(r);
			}		
		};

		IF_MAIN.loadCompos(cnf);

		//limpia pasadas hotkeys al actualizar canvas
		if (typeof IF_HOTKEY != 'undefined')
		{
			IF_HOTKEY.clearTempAll();
		}

		//cierra dialogo si se encuentra abierto
		if (typeof IF_MODAL != 'undefined')
		{
			IF_MODAL.close();
		}
	}

	//-----------------------------------------------------------------
	//MONITOR DEL CANVAS
	//-----------------------------------------------------------------
	
	, canvasLock: function()
	{
		IF_MAIN.CANVAS_LOCK++;
	}
	
	//-----------------------------------------------------------------
	
	, canvasUnlock: function()
	{
		IF_MAIN.CANVAS_LOCK--;
		if (IF_MAIN.CANVAS_LOCK < 0)
			IF_MAIN.CANVAS_LOCK = 0;
	}
	
	//-----------------------------------------------------------------
	
	, canvasLocked: function()
	{
		return IF_MAIN.CANVAS_LOCK > 0;
	}
	
	//-----------------------------------------------------------------
	
	, canvasShowLoading: function()
	{
		$(IF_MAIN.CANVAS_SELECTOR).empty()
				.append('<div class="cwfComposLoaderL"></div>')
	}

	//-----------------------------------------------------------------

	, prepareFormForUnsavedData: function(sel)
	{
		$(sel + ' input,' + sel + ' textarea')
				.keypress(MAIN.setUnsavedData)
				;
		$(sel + ' select').change(MAIN.setUnsavedData);
	}

	//-----------------------------------------------------------------

	//Revisar porque depende de CWF_DIALOG
	, confirmUnsavedData: function(callback, cbParams, scope)
	{
		if (IF_MAIN.UNSAVED_DATA)
		{
			CWF_DIALOG.confirm(
					'Se perderán los cambios no guardados. ¿Continuar?'
					, function(si)
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
		}
		else
			callback.call(scope || window, cbParams);
	}

	//-----------------------------------------------------------------

	, setUnsavedData: function(status)
	{
		if (typeof status == 'object' && status.which && status.which == 27)
		{
			return;
		}

		if (typeof status == 'undefined')
			var status = true;
		IF_MAIN.UNSAVED_DATA = status;
	}
	
	//-----------------------------------------------------------------
	//VALIDACION DE FORMULARIOS
	//-----------------------------------------------------------------	
	
	/*
	 * _form_validate
	 * 
	 * Establece validacion de un formulario.
	 * 
	 * @param string form	identificador de formulario a validar
	 * @param objetc cnf	varible de configuracion jquery.validation
	 */
	, _form_validate: function(form, cnf)
	{
		//trabajando...
		cnf.errorClass = 'invalid';
		
		$(form).validate(cnf);
	}
	
};