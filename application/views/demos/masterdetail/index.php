<?php include PLUGINS_PATH . "if.masterdetail/_loader.php"; ?>
<?php
if_plugin('if.layer');
?>

<!--
Este es el Markup base OBLIGATORIO para IF_MASTERDETAIL
Debe estar presente antes de llamar a init().
//-->
<div id="if-md">
	<div class="col-sm-5" id="if-md-mt"></div>
	<div class="col-sm-7" id="if-md-detail"></div>
</div>

<!--
Ejemplo de llamada a init()
//-->
<script>
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
</script>