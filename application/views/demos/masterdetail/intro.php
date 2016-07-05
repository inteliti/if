<h1>
	<i class="glyphicon glyphicon-user"></i>
	Personas &raquo; Usuarios
</h1>
<h4>
	<i class="glyphicon glyphicon-hand-left"></i>
	Seleccione un elemento de la lista para ver sus detalles en
	este panel.
</h4>

<a href="javascript:IF_MASTERDETAIL.reloadMT()"
   class="btn btn-default">
	Prueba: reload MT
</a>



<script>
	IF_LAYER.init({
		container: '#if-md-detail',
		animation: 'right',
		limit: '5'
	});
	function openLayer()
	{
		IF_LAYER.open({
			controller:'demos/if_layer_compos'
		});
	}
</script>