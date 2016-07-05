/******************************************************************
 * v1.0.0
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
	 * @returns {void}
	 */
	init: function (cnf)
	{
		IF_LAYER.CONTAINER = $(cnf.container);
		IF_LAYER.LAYERS = 0;

		$(IF_LAYER.CONTAINER).addClass('if_layer_container');
		
		IF_LAYER.CONTAINER_ORIGINAL_OVERFLOW =
			IF_LAYER.CONTAINER.css('overflow')
			;
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
		var INDEX = ++IF_LAYER.LAYERS;

		//apagar overflow de contenedor padre
		IF_LAYER.CONTAINER.css('overflow', 'hidden');

		(cnf.beforeOpen || $.noop)(INDEX);

		var $o = $(
			"<div class=if_layer id=if_layer-" + INDEX + "></div>"
			)
			.appendTo(IF_LAYER.CONTAINER)
			.animate({
				width: 100 - (INDEX * 3) + '%'
			}, {
				duration: 400,
				complete: function ()
				{
					(cnf.afterOpen || $.noop)();
				}
			})
			.css({
				'z-index': 10 + INDEX
			})
			.load(
				cnf.controller ? IF_MAIN.CI_INDEX + cnf.controller : cnf.url,
				cnf.data || {},
				cnf.afterLoad || $.noop
				)
			;

		(cnf.afterOpen || $.noop)(INDEX);
	}

	, get: function (index)
	{
		return $("#if_layer-" + index);
	}

	/**
	 * @param {fn} cb Callback (opcional)
	 * @returns {undefined}
	 */
	, close: function (cb)
	{
		var $l = IF_LAYER.get(IF_LAYER.LAYERS)
			.animate({
				width: 0
			}, {
				duration: 400,
				complete: function ()
				{
					IF_LAYER.LAYERS--;
					$l.remove();
					(cb || $.noop)();

					if (IF_LAYER.LAYERS <= 0)
					{
						IF_LAYER.LAYERS = 0;

						//Reactivar overflow de contenedor padre
						IF_LAYER.CONTAINER.css(
							'overflow'
							, IF_LAYER.CONTAINER_ORIGINAL_OVERFLOW
							)
							;
					}
				}
			})
			;

	}
};