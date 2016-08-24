<?php
if_plugin(array('if.layer', 'toastr'));
?>

<div id="layers" 
	 style="border-right:1px solid grey;
	 width:50%;height:100%;
	 overflow:auto;
	 float:left;padding:20px">

	<p>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae sem in leo fringilla pellentesque id eu nibh. Nam id justo eget justo consectetur eleifend. Suspendisse pretium consectetur lacus, id condimentum enim dictum nec. Ut sit amet dapibus sapien. Sed eu odio eget nulla hendrerit porta. Phasellus quis consectetur nunc, sit amet sollicitudin ante. Etiam laoreet neque in velit lacinia, eget malesuada ante imperdiet. Sed sit amet odio sem. Phasellus ut justo eu eros porta porttitor. Donec auctor leo odio, a eleifend augue eleifend vel.
	</p>

	<form>
		<div class="form-group">
			<label for="exampleInputEmail1">Email address</label>
			<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
		</div>
		<div class="form-group">
			<label for="exampleInputFile">File input</label>
			<input type="file" id="exampleInputFile">
			<p class="help-block">Example block-level help text here.</p>
		</div>
		<div class="checkbox">
			<label>
				<input type="checkbox"> Check me out
			</label>
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</form>

	<p>
		Donec vel rhoncus mauris. Integer convallis ipsum tellus, sed lobortis libero maximus quis. Integer facilisis augue erat, quis vulputate sem cursus a. Nunc malesuada egestas magna id egestas. Nam malesuada ultrices tellus vitae laoreet. Proin vitae pretium nisi. Aenean eleifend, mauris nec lacinia rutrum, diam eros viverra arcu, sit amet faucibus sem est eget nisi. Curabitur varius eget lacus non placerat. Morbi vitae imperdiet odio. Etiam at interdum metus. Suspendisse potenti. Nam imperdiet auctor diam, a sodales diam interdum ac. Ut metus odio, dignissim et odio nec, facilisis tincidunt velit.
	</p>

</div>
<div style="width:49%;float:right">

	<button type="button" class="btn"
			onclick="openLayer()">
		Abrir layer*
	</button>
	<button type="button" class="btn"
			onclick="openLayerEvents()">
		Abrir layer con eventos*
	</button>
	<button type="button" class="btn"
			onclick="closeLastLayer()">
		Cerrar último layer
	</button>
	<br />
	*Cada vez que pulse el botón se abrirá un nuevo layer
</div>
<script>
	IF_LAYER.init({
		container: '#layers'
	});

	var LAYER_COUNT = 0;
	function openLayer()
	{
		IF_LAYER.open({
			controller: 'demos/if_layer_compos/' + (++LAYER_COUNT),
			title: 'Título del layer #' + LAYER_COUNT,
			animation: 'right'
		});
	}
	function openLayerEvents()
	{
		IF_LAYER.open({
			controller: 'demos/if_layer_compos/' + (++LAYER_COUNT),
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
</script>