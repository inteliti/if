IF_LAYER.init({
	container: '#layers',
	limit: 10,
	onLimit: function ()
	{
		toastr.error('Se alcanzó el límite máximo de layers (10).');
	}
});

var LAYER_COUNT = 0;
function openLayer()
{
	IF_LAYER.open({
		controller: 'demos/if_layer_compos/' + (++LAYER_COUNT),
		title: 'Título del layer #' + LAYER_COUNT
	});
}
function openLayerEvents()
{
	IF_LAYER.open({
		controller: 'demos/if_layer_compos/' + (++LAYER_COUNT),
		title: 'Título del layer #' + LAYER_COUNT,
		beforeOpen: function (index)
		{
			toastr.info('beforeOpen [layer index: ' + index + ']');
		}
		, afterOpen: function (index)
		{
			toastr.info('afterOpen [layer index: ' + index + ']');
		}
		, afterLoad: function (index)
		{
			toastr.info('afterLoad [layer index: ' + index + ']');
		}
		, beforeClose: function (index)
		{
			toastr.info('beforeClose [layer index: ' + index + ']');
		}
		, afterClose: function (index)
		{
			toastr.info('afterClose [layer index: ' + index + ']');
		}
	});
}
function closeLastLayer()
{
	IF_LAYER.close();
}
openLayer();