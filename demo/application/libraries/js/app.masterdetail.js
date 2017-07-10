IF_MASTERDETAIL.init({
	controller: 'demos/masterdetail_mt',
	colModel: [
		{
			column: 'id',
			text: 'ID',
			attr: {
				'data-identifier': 'true',
				'data-type': 'numeric'
			}
		}, {
			column: 'name',
			text: 'Nombre'
		}, {
			column: 'received',
			text: 'Recibido',
			attr: {
				'data-order': 'desc'
			}
		}, {
			column: 'link',
			text: 'Enlace',
			attr: {
				'data-sortable': 'false',
				'data-formatter': 'link'
			}
		}
	],
	mtSelected: function (id)
	{
		IF_MASTERDETAIL.loadDetail({
			controller: 'demos/masterdetail_detail/' + id
		});
	}
},
{
	controller: 'demos/masterdetail_intro'
});


IF_LAYER.init({
	container: '#if-md-detail',
	animation: 'right',
	limit: '5'
});
function openLayer()
{
	IF_LAYER.open({
		title: 'Cabecera del layer',
		controller: 'demos/if_layer_compos'
	});
}
