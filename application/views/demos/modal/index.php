<?php if_plugin('toastr'); ?>
<h1>
	if.modal 2.0
</h1>
<hr />

<button type="button" class="btn btn-raised" onclick="IF_MODAL.close()">
	IF_MODAL.close()
</button>
<button type="button" class="btn btn-raised" onclick="if_alert()">
	IF_MODAL.alert();
</button>
<button type="button" class="btn btn-raised" onclick="if_confirm()">
	IF_MODAL.confirm();
</button>
<button type="button" class="btn btn-raised" onclick="if_osd()">
	IF_MODAL.osd();
</button>


<script>
	function if_alert()
	{
		IF_MODAL.alert('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec consectetur dolor non orci faucibus vehicula.');
	}
	function if_confirm()
	{
		IF_MODAL.confirm('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec consectetur dolor non orci faucibus vehicula.', function (si)
		{
			toastr.info('Usuario pulsó: ' + (si ? 'Aceptar' : 'Cancelar'));
		});
	}
	function if_osd()
	{
		IF_MODAL.osd('Este mensaje se cerrará atomaticamente en 5 segundos', 5);
	}
</script>