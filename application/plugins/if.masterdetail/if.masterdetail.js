/*****************************************************
 * Clase JavaScript de Maestro Detalle
 * v1.1.0
 * Dependencias: 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 * 
 * Versiones
 * 1.0.0
 * - Inicial
 * 1.1.0
 * - Añadido soporte para Data Formatters de Bootgrid
 * - Añadido método save()
 * - Añadido método detailLoaded()
 * - Localizacion a español de Bootgrid
 *****************************************************/
var IF_MASTERDETAIL = {
	//Recibe dos objetos de configuracion: para la MT y para el Detalle
	//El objeto para el MT será rebotado tal cual a Bootgrid, ver docs de
	//Bootgrid para +info http://www.jquery-bootgrid.com/
	//Adicionalmente debe recibir un Modelo de Columnas en colModel,
	init: function (mtCnf, detailCnf)
	{
		mtCnf = mtCnf || {};
		mtCnf.formatters = mtCnf.formatters || {};

		IF_MASTERDETAIL.AUTOSCROLL_OFFSET = mtCnf.autoScrollOffset || 0;

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
		mtCnf.labels = {
			noResults: "No se encontraron resultados.",
			search: 'Búsqueda',
			refresh: 'Recargar',
			loading: 'Cargando...',
			infos: 'Mostrando {{ctx.start}} - {{ctx.end}} de {{ctx.total}}'
		};

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

		IF_MASTERDETAIL.loadDetail(detailCnf || {});
	}

	//rebota la configuracion a IF_MAIN.loadCompos, por lo tanto
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
		//Solo aplica a moviles: mover vista al detalle
		if (IF_MAIN.IS_MOBILE)
		{
			var offset = $('#if-md-detail').offset().top -
				IF_MASTERDETAIL.AUTOSCROLL_OFFSET
				;
			$('head,body').scrollTo(offset, 'fast');
		}

		callback(row.id);
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