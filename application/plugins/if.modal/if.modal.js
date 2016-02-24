/******************************************************************
 * Clase JavaScript para utilizar ventanas modales
 * v2.0
 * 
 * NO RETRO-COMPATIBLE, usar if.modal.1 para proyectos antiguos
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 *****************************************************************/

var IF_MODAL = {
	/**
	 * Clase generica, configuracion:
	 * 
	 * - title (string): titulo
	 * - content (string): contenido del modal, puede contener HTML
	 * - hideTitle (bool): si true, no muestra el título. false por defecto
	 * - btns (obj): objeto de la forma {'texto': callback} que se convertirá
	 * en botones a ser mostrados en la parte inferior del modal.
	 * - timeout (number): Si >= 0, el modal se auto-cierra al transcurrir el 
	 * tiempo en milisegundos. Si < 0 el modal no se cerrará automátcamente.
	 * - autoFocus (number): puede usarse opcionalmente con btns. Representa
	 * un índice en el objeto btns de 0 a btn.length. Si >= 0
	 * el botón cuyo índice sea igual en el objeto btns, recibirá el focus
	 * de forma que se ejecute el callback de dicho boton
	 * al usuario presionar ENTER
	 */
	show: function (cnf)
	{
		var $modal = $('#ifModal'),
			$content = $modal.find('.if_modal_content').empty(),
			$btns = $modal.find('.if_modal_btns').empty()
			;

		//Valores por defecto
		cnf = cnf || {};

		if (cnf.title && !cnf.hideTitle)
		{
			$modal.find('.if_modal_title').empty().html(cnf.title);
		}

		$content.html(cnf.content || '');

		if (cnf.btns)
		{
			for (var i in cnf.btns)
			{
				var callback = cnf.btns[i];
				$("<button type=button>" + i + "</button>")
					.addClass('btn margin0')
					.click(callback)
					.appendTo($btns)
					;
			}
		}

		window.clearTimeout(IF_MODAL.TIMEOUT);
		if (cnf.timeout >= 0)
		{
			IF_MODAL.TIMEOUT = window.setTimeout(IF_MODAL.close, cnf.timeout);
		}

		$modal.fadeIn();

		if (typeof cnf.autoFocus == 'number')
		{
			$btns.children().eq(cnf.autoFocus).focus();
		}
	}

	, confirm: function (m, callback, cnf)
	{
		cnf = cnf || {};
		cnf.content = m;
		cnf.title = cnf.title || 'Confirmación';
		cnf.btns = {
			'Aceptar': function ()
			{
				(callback || $.noop)(1);
			},
			'Cancelar': function ()
			{
				(callback || $.noop)(0);
				IF_MODAL.close();
			}
		};
		IF_MODAL.show(cnf);
	}

	, alert: function (m, cnf)
	{
		cnf = cnf || {};
		cnf.content = m;
		cnf.title = cnf.title || 'Mensaje';
		cnf.btns = {
			'Cerrar': function ()
			{
				IF_MODAL.close();
			}
		};
		cnf.autoFocus = 0;
		IF_MODAL.show(cnf);
	}

	, osd: function (m, secs, cnf)
	{
		cnf = cnf || {};
		cnf.content = m;
		cnf.title = cnf.title || 'Mensaje';
		cnf.timeout = secs * 1000 || 5000;
		IF_MODAL.show(cnf);
	}

	, close: function ()
	{
		$('#ifModal').fadeOut();
	}

	, TIMEOUT: null
};