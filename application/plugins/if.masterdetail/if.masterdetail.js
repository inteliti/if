/*****************************************************
 * Clase JavaScript de Maestro Detalle
 * v1.0.0
 * Dependencias: 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/
var IF_MASTERDETAIL = {
	//recibe dos objetos de configuracion: para la MT y para el Detalle
	//El objeto para el MT será rebotado tal cual a Bootgrid, ver docs de
	//Bootgrid para +info http://www.jquery-bootgrid.com/
	//Adicionalmente debe recibir un Modelo de Columnas en colModel,
	init: function (mtCnf, detailCnf)
	{
		if (!mtCnf)
		{
			mtCnf = {};
		}
		if (mtCnf.controller)
		{
			mtCnf.url = IF_MAIN.CI_INDEX + mtCnf.controller;
		}

		//Establecemos algunas opciones por defecto
		mtCnf.ajax = true;
		mtCnf.ajaxSettings = {
			method: "POST",
			cache: false
		};
		mtCnf.selection = true;
		mtCnf.multiSelect = false;
		mtCnf.rowSelect = true;
		mtCnf.keepSelection = true;
		//agregar parametro de proteccion csfr
		if(IF_MAIN.CSFR_NAME.length>0)
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
			var $th = $("<th>" + c.text + "</th>")
					.attr('data-column-id', c.column)
					.attr(c.attr || {})
					.appendTo($tr)
					;
		}
		delete (mtCnf.colModel);

		$table
				.bootgrid(mtCnf)
				.on('selected.rs.jquery.bootgrid', function (e, rows)
				{
					(mtCnf.mtSelected||$.noop)(rows[0].id);
				})
		;

		IF_MASTERDETAIL.loadDetail(detailCnf);
	}

	//rebota la configuracion a IF_MAIN.loadCompos, por lo tanto
	//recibe los mismos parametros. Ver docs de IF_MAIN.loadCompos
	, loadDetail: function (detailCnf)
	{
		if (!detailCnf)
		{
			detailCnf = {};
		}
		detailCnf.target = '#if-md-detail';
		IF_MAIN.loadCompos(detailCnf);
	}

	, reloadMT: function ()
	{
		$("#if-md #if-grid").bootgrid('reload');
	}
};