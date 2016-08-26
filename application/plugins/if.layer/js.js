/******************************************************************
 * Singleton para la generación de layers
 * 
 * @namespace IF_LAYER
 * @version 1.4.0
 * @requires if.main 1.2+
 * @author Gregorio Bolivar
 * @copyright 2016 INTELITI SOLUCIONES, C.A.
 * @license Para su uso sólo con autorización.
 *****************************************************************/
var IF_LAYER = {
	/**
	 * Inicializa el Singleton (debe llamarse una unica vez)
	 * 
	 * @param {object} cnf Objeto de configuración
	 * @param {string} cnf.container Selector jquery con el contenedor
	 * que contendrá los layers
	 * @param {string} [cnf.animation=left] left o right. Dirección de animación
	 * de apertura/cierre.
	 * @param {int} [cnf.limit] Establecer un límite a la cantidad de layers
	 * que se pueden abrir (infinito por defecto).
	 * @param {fn} [cnf.onLimit] Se dispara cuando se alcanza el límite
	 * de layers impuestos por limit.
	 * @returns {void}
	 */
	init: function (cnf)
	{
		//Configuración por defecto
		if (!cnf.animation)
		{
			cnf.animation = 'left';
		}

		IF_LAYER.CONTAINER = $(cnf.container);
		IF_LAYER.CNF = cnf;
		IF_LAYER.LAYERS = 0;
		IF_LAYER._onLimit = cnf.onLimit || $.noop;

		IF_LAYER.CONTAINER.addClass('if_layer_container');

		IF_LAYER.CONTAINER_ORIGINAL_OVERFLOW =
			IF_LAYER.CONTAINER.css('overflow')
			;

		//Escuchar DOM del contenedor
		IF_LAYER.CONTAINER.observe("childlist", function (record)
		{
			var nodos = record.addedNodes;
			var nodoNuevo = $(record.addedNodes[0]);
			if (nodos.length > 1 && !nodoNuevo.hasClass('if_layer'))
			{
				IF_LAYER._restoreContainer();
			}
		});
	}

	/**
	 * Abre un nuevo layer. Llamadas sucesivas abrirán layers sobre layers.
	 * Los eventos reciben como único parámetro el INDICE del layer
	 * que se está abriendo, se puede entonces usar IF_LAYER.get(INDICE)
	 * para obtener un objeto jQuery con el layer.
	 * 
	 * @param {object} cnf Objeto de configuración
	 * @param {string} cnf.url URL para cargar contenido en el layer a
	 *  través de AJAX
	 * @param {string} cnf.controller Controlador CODEIGNITER que se 
	 * llamará dentro del layer a través de AJAX (tiene prioridad sobre cnf.url)
	 * @param {string} [cnf.title] Título del header
	 * @param {object} [cnf.data] Data a pasar en la llamada AJAX
	 * @param {fn} [cnf.beforeOpen] Se dispara ANTES de abrir el layer
	 * (el layer aun no existe en DOM)
	 * @param {fn} [cnf.afterOpen] Se dispara DESPUES que la animación de
	 * apertura del layer finaliza (el layer ya existe en DOM) pero ANTES
	 * de la carga del contenido
	 * @param {fn} [cnf.afterLoad] Se dispara DESPUES de cargar el contenido
	 * @param {fn} [cnf.beforeClose] Se dispara al llamar al método close()
	 * pero ANTES de cerrar el layer (el layer aun existe en el DOM)
	 * @param {fn} [cnf.afterClose] Se dispara DESPUES de finalizar la animación
	 * de cierre del layer (el layer ya NO existe en DOM)
	 * @returns {void}
	 */
	, open: function (cnf)
	{
		if (IF_LAYER.CNF.limit && IF_LAYER.LAYERS >= IF_LAYER.CNF.limit)
		{
			IF_LAYER._onLimit();
			return;
		}

		var
			INDEX = ++IF_LAYER.LAYERS,
			IS_DIR_RIGHT = IF_LAYER.CNF.animation == 'right',
			OFFSET = 20 * INDEX
			;

		//apagar overflow de contenedor padre
		IF_LAYER.CONTAINER.css('overflow', 'hidden');
		IF_LAYER.CONTAINER_RESTORED = 0;

		(cnf.beforeOpen || $.noop)(INDEX);

		//Layout del layer
		var $o = $(
			"<div class=if_layer id=if_layer-" + INDEX + ">"
			+ "<div class=if_layer_header>"
			+ "<i title='Cerrar'></i>"
			+ "<b>" + (cnf.title || '&nbsp;') + "</b>"
			+ "</div>"
			+ "<div class=if_layer_content></div>"
			+ "</div>"
			)
			.appendTo(IF_LAYER.CONTAINER)
			.addClass(IS_DIR_RIGHT?'r':'')
			.css({
				'z-index': 101 + INDEX,
				top: IF_LAYER.CONTAINER.scrollTop() + 'px',
				left: IS_DIR_RIGHT ? '101%' : '-101%',
				width: 'calc(100% - ' + OFFSET + 'px)'
			})
			.data('beforeClose', cnf.beforeClose || $.noop)
			.data('afterClose', cnf.afterClose || $.noop)
			;

		//boton de cierre
		$o.find('.if_layer_header > i').click(IF_LAYER.close);

		//Carga de contenido
		IF_MAIN.loadCompos({
			target: $o.find('.if_layer_content'),
			url: cnf.url ? cnf.url : null,
			controller: cnf.controller ? cnf.controller : null,
			data: cnf.data || {},
			callback: function ()
			{
				(cnf.afterLoad || $.noop)(INDEX);
			}
		});

		var animObj = {
			duration: 400,
			easing: 'easeInOutCirc',
			complete: function ()
			{
				(cnf.afterOpen || $.noop)(INDEX);
			}
		};

		//Desactivar overflow del layer inferior
		IF_LAYER.get(IF_LAYER.LAYERS - 1)
			.find('.if_layer_content')
			.css('overflow', 'hidden')
			;

		//Apertura
		$o.animate({
			left: IS_DIR_RIGHT ? OFFSET : 0
		}, animObj);
	}

	/**
	 * Cierra el último layer abierto.
	 * @returns {void}
	 */
	, close: function ()
	{
		var
			INDEX = IF_LAYER.LAYERS,
			$l = IF_LAYER.get(INDEX),
			afterClose
			;

		$l.data('beforeClose')(INDEX);

		var animObj = {
			duration: 400,
			easing: 'easeInOutCirc',
			complete: function ()
			{
				afterClose = $l.data('afterClose');

				IF_LAYER.LAYERS--;
				$l.remove(); //layer YA no existe

				//Reactivar overflow del layer inferior
				IF_LAYER.get(IF_LAYER.LAYERS)
					.find('.if_layer_content')
					.css('overflow', 'auto')
					;

				if (IF_LAYER.LAYERS <= 0)
				{
					IF_LAYER.LAYERS = 0;
					IF_LAYER._restoreContainer();
				}

				afterClose(INDEX);
			}
		};

		if (IF_LAYER.CNF.animation == 'right')
		{
			$l.animate({
				left: '101%'
			}, animObj);
		} else
		{
			$l.animate({
				left: '-101%'
			}, animObj);
		}
	}

	/**
	 * 
	 * 
	 * @private
	 * @returns {undefined}
	 */
	, _restoreContainer: function ()
	{
		if (IF_LAYER.CONTAINER_RESTORED)
		{
			return;
		}

		IF_LAYER.CONTAINER.css(
			'overflow'
			, IF_LAYER.CONTAINER_ORIGINAL_OVERFLOW
			)
			;
		IF_LAYER.CONTAINER_RESTORED = 1;
		IF_LAYER.LAYERS = 0;

	}

	/**
	 * Retorna un objeto jQuery con el layer cuyo índice es index
	 * @param {int} index Indice (desde 1) del layer
	 * @returns {jQuery}
	 */
	, get: function (index)
	{
		return $("#if_layer-" + index);
	}
};