/******************************************************************
 * v1.1.0
 * 
 * Dependencias: if.main v1.2.0+
 * 
 * Derechos Reservados (c) 2016 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 *****************************************************************/

var IF_LAYER = {
	/**
	 * Debe llamarse una única vez cuando se carga el plugin.
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
			if (nodos.length > 1 || !nodoNuevo.hasClass('if_layer'))
			{
				IF_LAYER.restoreContainer();
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
	 * - data (opcional): data a pasar por AJAX
	 * - beforeOpen (opcional): evento que se dispara ANTES de abrir el layer
	 * - afterOpen (opcional): evento que se dispara DESPUES de abrir el layer
	 * - afterLoad (opcional): evento que se dispara DESPUES de cargar
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

		var $o = $(
			"<div class=if_layer id=if_layer-" + INDEX + "></div>"
			)
			.appendTo(IF_LAYER.CONTAINER)
			.css({
				'z-index': 101 + INDEX,
				top: IF_LAYER.CONTAINER.scrollTop() + 'px'
			})
			.load(
				cnf.controller ? IF_MAIN.CI_INDEX + cnf.controller : cnf.url,
				cnf.data || {},
				function ()
				{
					(cnf.afterLoad || $.noop)(INDEX);
				}
			)
			;

		var animObj = {
			duration: 400,
			complete: function ()
			{
				(cnf.afterOpen || $.noop)(INDEX);
			}
		};

		//Apertura
		if (IF_LAYER.CNF.animation == 'right')
		{
			$o
				.css({
					left: '101%'
				})
				.animate({
					width: '100%',
					left: (INDEX * 3) + '%'
				}, animObj);
		} else
		{
			$o.animate({
				width: 100 - (INDEX * 3) + '%'
			}, animObj);
		}
	}

	/**
	 * @param {fn} cb Callback (opcional)
	 * @returns {undefined}
	 */
	, close: function (cb)
	{
		var $l = IF_LAYER.get(IF_LAYER.LAYERS);

		var animObj = {
			duration: 400,
			complete: function ()
			{
				IF_LAYER.LAYERS--;
				$l.remove();
				(cb || $.noop)();

				if (IF_LAYER.LAYERS <= 0)
				{
					IF_LAYER.LAYERS = 0;
					IF_LAYER.restoreContainer();
				}
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

	, restoreContainer: function ()
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

	, get: function (index)
	{
		return $("#if_layer-" + index);
	}
};