/******************************************************************
 * 
 * Clase JavaScript para utilizar ventanas modales
 * v1.1.0
 * 
 * Basado en utilidades de Bootstrap
 * Dependencias: jquery.nestable
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 * 
 * 
 *****************************************************************/

var IF_MODAL =
	{
		/*
		 * 
		 */
		_setSm: function ()
		{
			$('#myModal > div').removeClass('modal-lg');
			$('#myModal > div').addClass('modal-sm');
		}

		//-----------------------------------------------------------------

		/*
		 * 
		 */
		, _setLg: function ()
		{
			$('#myModal > div').removeClass('modal-sm');
			$('#myModal > div').addClass('modal-lg');
		}

		//-----------------------------------------------------------------

		/*
		 * 
		 */
		, alert: function (m, cnf)
		{
			cnf = cnf || {};

			IF_MODAL._setSm();

			var msg = '<p>' + m + '<p>';
			var header = '<div class="modal-header">' +
				'<a class="close" data-dismiss="modal"></a>' +
				'</div>';
			var body = '<div id="ifModal" class="modal-body">' + msg + '</div>';
			var footer = '<div class="modal-footer">' +
				'<a data-dismiss="modal" class="btn">Cerrar</a>' +
				'</div>';

			$('#ifModal-content').html(header + body + footer);

			if (!cnf.width)
			{
				cnf.width = IF_MAIN.IS_MOBILE ?
					(IF_MAIN.VIEWPORT.width - 20) + 'px' : '350px'
					;
			}
			$('#dialog').width(cnf.width);

			$('#myModal').modal('show');
		}

		//-----------------------------------------------------------------

		/*
		 * 
		 */
		, confirm: function (m, callback, cnf)
		{
			cnf = cnf || {};

			IF_MODAL._setSm();
			var msg = '<p>' + m + '<p>';
			var body = '<div id="ifModal" class="modal-body">' + msg + '</div>';
			var footer = '<div class="modal-footer">' +
				'</div>';

			$('#ifModal-content').html(body + footer);

			if (!cnf.width)
			{
				cnf.width = IF_MAIN.IS_MOBILE ?
					(IF_MAIN.VIEWPORT.width - 20) + 'px' : '350px'
					;
			}
			$('#dialog').width(cnf.width);

			$('<button class="btn btn-primary">Aceptar</button>')
				.on('click', function () {
					$('#myModal').modal('hide');
					callback(true);
				}).appendTo(".modal-footer");

			$('<button class="btn btn-default">Cancelar</button>')
				.on('click', function () {
					$('#myModal').modal('hide');
					callback(false);
				}).appendTo(".modal-footer");

			$('#myModal').modal('show');

		}

		//-----------------------------------------------------------------

		/*
		 * 
		 */
		, close: function ()
		{

			$('#myModal').modal('hide');
		}

		/*
		 * PENDIENTE ACOMODAR
		 */
		, osd: function (msg, type, timeout)
		{
			type = type || 'info';

			$('#alerts').html('<div class="alert alert-' + type + '">' +
				'<button type="button" class="close" data-dismiss="alert"></button>' + msg + '</div>');

			timeout = timeout || 2000;

			window.setTimeout(function ()
			{
				IF_MODAL._close_osd();
			}, timeout);
		}
		, _close_osd: function ()
		{
			$('#alerts').html('');
		}

		//-----------------------------------------------------------------

		/*
		 * ajaxCntrllr
		 * 
		 * Muestra una modal con la vista retornada desde el 
		 * controlador usando ajax.
		 * 
		 * @param objetc cnf			objeto para configuracion del modal
		 *		
		 *		cnf.controller			controlador que trae la vista
		 *		cnf.data				data para el controlador
		 *		cnf.title				titulo del modal
		 *		cnf.width				ancho
		 *		cnf.height				alto (POR PROGRAMAR)
		 */
		, ajaxCntrllr: function (cntrllr, data, cnf)
		{
			cnf = cnf || {};

			IF_MODAL._setLg();

			var header = '<div class="modal-header">' +
				'<h4 class="modal-title">' + (cnf.title || '') + '</h4>' +
				'<a class="close" data-dismiss="modal"></a>' +
				'</div>';
			var body = '<div id="ifModal" class="modal-body"></div>';

			$('#ifModal-content').html(header + body);

			//establecemos ancho del modal
			if (!cnf.width)
			{
				cnf.width = IF_MAIN.IS_MOBILE ?
					(IF_MAIN.VIEWPORT.width - 20) + 'px' : '350px'
					;
			}
			$('#dialog').width(cnf.width);

			IF_MAIN.loadCompos({
				controller: cntrllr,
				data: data,
				target: '#ifModal'
			});

			$('#myModal').modal('show');
		}
	};