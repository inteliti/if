/******************************************************************
 * 
 * Clase JavaScript para utilizar ventanas modales
 * v2.1.0
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
	 * - title (string|opcional): titulo de la ventana
	 * - content (string|opcional): contenido del modal, puede contener HTML.
	 * - url (string|opcional): si se indica, el modal intentará cargar su
	 * contenido a traves de AJAX usando este URL. Tiene precedencia
	 * sobre content.
	 * - controller (string|opcional): controlador CODEIGNITER para cargar
	 * contenido del modal a traves de AJAX. Tiene precedencia sobre url.
	 * - data (obj|opcional): data a pasar con la llamada AJAX.
	 * - callback (fn|opcional): se ejecuta luego de cargarse contenido
	 * con AJAX.
	 * - hideTitle (bool|opcional): si true, no muestra el título. 
	 * false por defecto
	 * - btns (obj|opcional): objeto de la forma {'texto': callback} 
	 * que se convertirá en botones a ser mostrados en la parte 
	 * inferior del modal.
	 * - timeout (number|opcional): Si >= 0, el modal se auto-cierra
	 * al transcurrir el  tiempo en milisegundos.  Si < 0 el modal no
	 * se cerrará automátcamente.
	 * - autoFocus (number|opcional): puede usarse opcionalmente 
	 * con btns. Representa un índice en el objeto btns de 0 a btn.length.
	 * Si >= 0 el botón cuyo índice sea igual en el objeto btns, 
	 * recibirá el focus de forma que se ejecute el callback de dicho boton
	 * al usuario presionar ENTER
	 */
	show: function (cnf)
	{
		var $modal = $('#ifModal'),
			$title = $modal.find('.if_modal_title'),
			$content = $modal.find('.if_modal_content').empty(),
			$btns = $modal.find('.if_modal_btns').empty()
			;

		//Valores por defecto
		cnf = cnf || {};

		//titulo
		if (cnf.hideTitle)
		{
			$title.empty();
		}
		if (cnf.title && !cnf.hideTitle)
		{
			$title.html(cnf.title);
		}

		//contenido
		if (cnf.url || cnf.controller)
		{
			$content
				.empty()
				.addClass('if_modal_loading')
				.load(
					cnf.controller ? IF_MAIN.CI_INDEX + cnf.controller : cnf.url,
					cnf.data || {},
					function (r)
					{
						$content.removeClass('if_modal_loading');
						(cnf.callback || $.noop)(r);
					}
				);
		} else
		{
			$content.html(cnf.content || '');
		}

		//botones
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

		//timeout
		window.clearTimeout(IF_MODAL.TIMEOUT);
		if (cnf.timeout >= 0)
		{
			IF_MODAL.TIMEOUT = window.setTimeout(IF_MODAL.close, cnf.timeout);
		}

		//mostrar
		$modal.fadeIn();

		//autofocus
		if (typeof cnf.autoFocus == 'number')
		{
			$btns.children().eq(cnf.autoFocus).focus();
		}
	}

	/**
	 * Configuracion adicional:
	 * - dontClose: NO cierra el modal automáticamente al pulsar los botones
	 * 
	 * @param {type} m
	 * @param {type} callback
	 * @param {type} cnf
	 * @returns {undefined}
	 */
	, confirm: function (m, callback, cnf)
	{
		cnf = cnf || {};
		cnf.content = m;
		cnf.title = cnf.title || 'Confirmación';
		cnf.btns = {
			'Aceptar': function ()
			{
				(callback || $.noop)(true);

				if (!cnf.dontClose)
					IF_MODAL.close();
			},
			'Cancelar': function ()
			{
				(callback || $.noop)(false);

				if (!cnf.dontClose)
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
		$('#ifModal')
			.fadeOut('normal', function ()
			{
				//liberar memoria
				$('#ifModal').find('.if_modal_content').empty();
			})
			;
	}

	, TIMEOUT: null
};