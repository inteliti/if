/*****************************************************
 * Clase JavaScript de Maestro Detalle
 * v1.2.0 (detalles en changelog)
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Dependencias: 
 * - if.main
 *****************************************************/
var IF_MASTERDETAIL = {
	//Recibe dos objetos de configuracion: para la MT y para el Detalle
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

		//agregar parametro de proteccion csfr
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
				if (IF_MASTERDETAIL.BOOTGRID_WRAPPER_ADDED)
				{
					return;
				}
				$("#if-grid").wrap("<div class='bootgrid-table-wrap'></div>");
				IF_MASTERDETAIL.BOOTGRID_WRAPPER_ADDED = 1;
			})
			;

		IF_MASTERDETAIL.CURRENT_CNF = mtCnf;
		IF_MASTERDETAIL.loadDetail(detailCnf || {});
	}

	//Rebota la configuracion a IF_MAIN.loadCompos, por lo tanto
	//recibe los mismos parametros. Ver docs de IF_MAIN.loadCompos
	, loadDetail: function (detailCnf)
	{
		IF_MAIN.confirmUnsavedData(function ()
		{
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
		//Solo aplica a moviles: mostrar panel de detalle 
		if (IF_MAIN.IS_MOBILE)
		{
			window.history.pushState({ifMTId: row.id}, '', "#detail");
			IF_MASTERDETAIL.showDetail();
		}

		callback(row.id);
	}

	//Movil, Ocultar/Mostrar el detalle, ideal para botones customizados
	, hideDetail: function ()
	{
		$('#if-md-detail').animate({"left": '1000px'}, 'fast').hide();
		IF_MASTERDETAIL.MOBILE_DETAIL_OPENED = 0;
	}

	, showDetail: function ()
	{
		$('#if-md-detail').animate({"left": '0'}, 'fast').show();
		IF_MASTERDETAIL.MOBILE_DETAIL_OPENED = 1;
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
};

//Sobre escribe boton atras/adelante para moviles.
//Dejar FUERA de la clase para evitar overload de memoria cada vez 
//que se cargue un Maestro Detalle
var IF_MASTERDETAIL_MOBILE_POPSTATE = function (e)
{
	var state = e.originalEvent.state;

	console.debug(state)

	//Back button
	if (!state || !state.ifMTId)
	{
		IF_MASTERDETAIL.hideDetail();
		e.preventDefault();
		e.stopPropagation();
		return false;
	}

	//Forward, reabrir el detalle
	else if (state.ifMTId > 0)
	{
		IF_MASTERDETAIL.showDetail();
	}

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

/**
 var ignoreHashChange = true;
 window.onhashchange = function () {
 console.debug(window.location.hash)
 if (!ignoreHashChange) {
 ignoreHashChange = true;
 window.location.hash = Math.random();
 // Detect and redirect change here
 // Works in older FF and IE9
 // * it does mess with your hash symbol (anchor?) pound sign
 // delimiter on the end of the URL
 } else {
 ignoreHashChange = false;
 }
 };
 /**
 var IF_MASTERDETAIL_MOBILE_POPSTATE = function (e)
 {
 alert(window.location.hash)
 if (window.location.hash=='#mobile-back')
 {
 if (IF_MASTERDETAIL.MOBILE_DETAIL_OPENED)
 {
 IF_MASTERDETAIL.hideDetail();
 }
 e.preventDefault();
 e.stopPropagation();
 return false;
 }
 };
 $(window).resize(function ()
 {
 if (IF_MAIN.IS_MOBILE)
 {
 history.pushState({page:1}, '', "#mobile-back");
 $(window).bind('popstate', IF_MASTERDETAIL_MOBILE_POPSTATE);
 } else
 {
 $(window).unbind('popstate', IF_MASTERDETAIL_MOBILE_POPSTATE);
 }
 }).resize();
 /**/