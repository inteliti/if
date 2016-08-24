/******************************************************************
 * v1.3.1
 * 
 * Dependencias: if.main v1.2.0+
 * 
 * Derechos Reservados (c) 2016 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 *****************************************************************/

var IF_LAYER = {
	/**
	 * Inicializa el singleton (debe llamarse una unica vez)
	 * 
	 * @param {object} cnf
	 * - Container: selector jquery con el contenedor que contendrá los
	 * layers
	 * - animation (opcional): left o right. Dirección de animación
	 * de apertura/cierre. left por defecto.
	 * - limit (opcional): establecer un límite a la cantidad de layers
	 * que se pueden abrir (infinito por defecto).
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

		$(IF_LAYER.CONTAINER).addClass('if_layer_container');

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
	 * @param {object} cnf
	 * - url: url que se llamará dentro del layer a través de AJAX
	 * - controller: controlador CODEIGNITER que se llamará dentro del layer
	 * a través de AJAX (tiene prioridad sobre url)
	 * - title (string|opcional): Título del header
	 * - data (obj|opcional): data a pasar por AJAX
	 * - beforeOpen (fn|opcional): se dispara ANTES de abrir el layer
	 * (el layer aun no existe en DOM)
	 * - afterOpen (fn|opcional): se dispara DESPUES que la animación de
	 * apertura del layer finaliza (el layer ya existe en DOM) pero ANTES
	 * de la carga del contenido
	 * - afterLoad (fn|opcional): se dispara DESPUES de cargar el contenido
	 * - beforeClose (fn|opcional) se dispara al llamar al método close()
	 * pero ANTES de cerrar el layer (el layer aun existe en el DOM)
	 * - afterClose (fn|opcional): se dispara DESPUES de finalizar la animación
	 * de cierre del layer (el layer ya NO existe en DOM)
	 *  el contenido AJAX en el layer
	 *  
	 * @returns {void}
	 */
	, open: function (cnf)
	{
		if (IF_LAYER.CNF.limit && IF_LAYER.LAYERS >= IF_LAYER.CNF.limit)
		{
			console.debug('Límite de layers alcanzado.');
			return;
		}

		var INDEX = ++IF_LAYER.LAYERS;

		//apagar overflow de contenedor padre
		IF_LAYER.CONTAINER.css('overflow', 'hidden');
		IF_LAYER.CONTAINER_RESTORED = 0;

		(cnf.beforeOpen || $.noop)(INDEX);

		//Layout del layer
		var $o = $(
			"<div class=if_layer id=if_layer-" + INDEX + ">"
			+ "<div class=if_layer_header>"
			+ "<i class='fa fa-chevron-left'></i>"
			+ "<b>" + (cnf.title || '&nbsp;') + "</b>"
			+ "</div>"
			+ "<div class=if_layer_content></div>"
			+ "</div>"
			)
			.appendTo(IF_LAYER.CONTAINER)
			.css({
				'z-index': 101 + INDEX,
				top: IF_LAYER.CONTAINER.scrollTop() + 'px'
			})
			.data('beforeClose', cnf.beforeClose || $.noop)
			.data('afterClose', cnf.afterClose || $.noop)
			;

		//boton de cierre
		$o.find('.if_layer_header > i').click(IF_LAYER.close);

		var animObj = {
			duration: 400,
			complete: function ()
			{
				(cnf.afterOpen || $.noop)(INDEX);

				//carga de contenido de layer DESPUES de animación
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
			}
		};

		//Desactivar overflow del layer inferior
		IF_LAYER.get(IF_LAYER.LAYERS - 1)
			.find('.if_layer_content')
			.css('overflow', 'hidden')
			;

		//Apertura
		if (IF_LAYER.CNF.animation == 'right')
		{
			//Reajuste del botón de cierre
			$o.addClass('right');
			$o.find('.if_layer_header > i')
				.removeClass('fa-chevron-left')
				.addClass('fa-chevron-right')
				;

			var left = (INDEX * 3);
			$o
				.css({
					left: '101%'
				})
				.animate({
					width: (100 - left) + '%',
					left: left + '%'
				}, animObj);
		} else
		{
			$o.animate({
				width: 100 - (INDEX * 3) + '%'
			}, animObj);
		}
	}

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

		//eliminar contenido interno del layer
		$l.empty();

		if (IF_LAYER.CNF.animation == 'right')
		{
			$l.animate({
				width: 0,
				left: '101%'
			}, animObj);
		} else
		{
			$l.animate({
				width: 0
			}, animObj);
		}
	}

	, _restoreContainer: function ()
	{
		if (IF_LAYER.CONTAINER_RESTORED)
		{
			return;
		}

		//_('container restored');

		IF_LAYER.CONTAINER.css(
			'overflow'
			, IF_LAYER.CONTAINER_ORIGINAL_OVERFLOW
			)
			;
		IF_LAYER.CONTAINER_RESTORED = 1;
		IF_LAYER.LAYERS = 0;

	}

	, get: function (index)
	{
		return $("#if_layer-" + index);
	}
};