/*****************************************************
 * Clase JavaScript de Maestro Detalle
 * v1.2.0 (detalles en changelog)
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Dependencias: 
 * - if.main +1.2.0
 *****************************************************/

var IF_MASTERDETAIL = {
	//Recibe dos objetos de configuracion: para la MT y para el Detalle.
	//El objeto para el MT será rebotado tal cual a Bootgrid, ver docs de
	//Bootgrid: http://www.jquery-bootgrid.com/
	//Adicionalmente debe recibir un Modelo de Columnas en colModel,
	init: function (mtCnf, detailCnf)
	{
		mtCnf = mtCnf || {};
		mtCnf.formatters = mtCnf.formatters || {};

		if (mtCnf.controller)
		{
			mtCnf.url = IF_MAIN.CI_INDEX + mtCnf.controller;
		}

		//Establecemos algunas opciones por defecto a Bootgrid
		mtCnf.ajax = true;
		mtCnf.ajaxSettings = {
			method: "POST",
			cache: false
		};
		mtCnf.selection = true;
		mtCnf.multiSelect = false;
		mtCnf.rowSelect = true;
		mtCnf.keepSelection = true;
		mtCnf.sorting = true;
		mtCnf.rowCount = 25;
		mtCnf.searchSettings = {
			delay: 500,
			characters: 3
		};

		//Establecer idioma si previamente se ha cargado un paquete L10N
		if (IF_MASTERDETAIL.L10N)
		{
			mtCnf.labels = IF_MASTERDETAIL.L10N;
		}

		//Agregar parametro de proteccion csfr
		if (IF_MAIN.CSFR_NAME.length > 0)
		{
			mtCnf.post = {};
			mtCnf.post[IF_MAIN.CSFR_NAME] = IF_MAIN.CSFR_TOKEN;
		}

		//Creamos la tabla
		var $table = $("<table id='if-grid'></table>")
			.addClass('table table-hover table-striped')
			.append('<thead><tr></tr></thead>')
			.appendTo('#if-md-mt')
			;
		var $tr = $table.find('thead > tr');

		//Añadimos las Columnas basadas en el Modelo recibido
		for (var i = 0; i < mtCnf.colModel.length; i++)
		{
			var c = mtCnf.colModel[i];
			var formatter = mtCnf.formatters[c.column];

			$("<th>" + c.text + "</th>")
				.attr('data-column-id', c.column)
				.attr('data-formatter', formatter ? c.column : '')
				.attr(c.attr || {})
				.appendTo($tr)
				;
		}
		delete (mtCnf.colModel);

		$table
			.bootgrid(mtCnf)
			.on('click.rs.jquery.bootgrid', function (e, colModel, row)
			{
				//Extrañamente, en este scope no existen funciones
				//basicas de JS como alert() o console.debug()
				//Cambiar de scope...
				IF_MASTERDETAIL._mtSelected(
					(mtCnf.mtSelected || $.noop), e, colModel, row
					);
			})
			.on('loaded.rs.jquery.bootgrid', function (e)
			{
				//FIX: este wrapper es necesario para resizar el grid a 
				//height 100%. Asegurarse que sólo se añade 1 vez.
				if (IF_MASTERDETAIL.BOOTGRID_WRAPPER_ADDED)
				{
					return;
				}
				$("#if-grid").wrap("<div class='bootgrid-table-wrap'></div>");
				IF_MASTERDETAIL.BOOTGRID_WRAPPER_ADDED = 1;
			})
			;

		IF_MASTERDETAIL.loadDetail(detailCnf || {}, detailCnf.pushState || 1);
	}

	//Rebota la configuracion a IF_MAIN.loadCompos, por lo tanto
	//recibe los mismos parametros. Ver docs de IF_MAIN.loadCompos
	, loadDetail: function (detailCnf, dontPushState)
	{
		IF_MAIN.confirmUnsavedData(function ()
		{
			//Solo aplica a moviles: mostrar panel de detalle 
			if (IF_MAIN.IS_MOBILE && !dontPushState)
			{
				window.history.pushState({ifMTId: 1}, '', "#detail");
				IF_MASTERDETAIL.showDetail();
			}

			if (!detailCnf)
			{
				detailCnf = {};
			}
			detailCnf.target = '#if-md-detail';
			IF_MAIN.loadCompos(detailCnf);
		});
	}

	//Recarga la MT. Re-envia al servidor los ultimos parametros
	//establecidos en Bootgrid (search, sort, etc).
	, reloadMT: function ()
	{
		$("#if-md #if-grid").bootgrid('reload');
	}

	//Llamar a esto desde save() en las clases especificas
	, save: function (controller, data, callback)
	{
		IF_MAIN.setUnsavedData(false);
		IF_MASTERDETAIL.loadDetail({
			controller: controller,
			data: data,
			callback: function (r)
			{
				IF_MASTERDETAIL.reloadMT();
				(callback || $.noop)(r);
			}
		});
	}

	, deselect: function ()
	{
	}

	, _mtSelected: function (callback, e, colModel, row)
	{
		callback(row.id);
	}

	//Movil, Ocultar/Mostrar el detalle, ideal para botones customizados
	, hideDetail: function ()
	{
		$('#if-md-detail').animate({"left": '1000px'}, 'fast', null, function ()
		{
			$('#if-md-detail').hide();
		});
	}

	, showDetail: function ()
	{
		$('#if-md-detail').animate({"left": '0'}, 'fast').show();
	}

	//Esta funcion se debe customizar por proyectos.
	//Aqui por ejemplo se pueden instanciar los datepickers, tooltips, etc
	, detailLoaded: function (params)
	{
		params = params || {};

		IF_MAIN.prepareFormForUnsavedData('#if-md-detail form');

		//Configuraciones adicionales
		$("#if-md-detail .datepicker").datepicker(IF_MAIN.DATEPICKER_CONFIG);
	}

	//SIEMPRE Debe llamarse al cerrar el detalle, sea movil o no
	, detailClosed: function ()
	{
		if (IF_MAIN.IS_MOBILE)
		{
			history.back(); //regresa en la pila añadida via history.pushState
		}
	}
};

//Sobre-escribe boton atras/adelante para moviles.
//Dejar FUERA de la clase para evitar overload de memoria cada vez 
//que se inicialize un Maestro Detalle
var IF_MASTERDETAIL_MOBILE_POPSTATE = function (e)
{
	var state = e.originalEvent.state;

	//Back button
	if (!state || !state.ifMTId)
	{
		IF_MASTERDETAIL.hideDetail();
	}

	//Forward, reabrir el detalle
	else if (state.ifMTId > 0)
	{
		IF_MASTERDETAIL.showDetail();
	}

	e.preventDefault();
	e.stopPropagation();
	return false;

};
$(window).bind('resizeEnd', function ()
{
	if (IF_MAIN.IS_MOBILE)
	{
		$(window).bind('popstate', IF_MASTERDETAIL_MOBILE_POPSTATE);
		IF_MASTERDETAIL.hideDetail();
	} else
	{
		$(window).unbind('popstate', IF_MASTERDETAIL_MOBILE_POPSTATE);
		IF_MASTERDETAIL.showDetail();
	}
}).resize();