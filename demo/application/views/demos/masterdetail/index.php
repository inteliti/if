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
<!-- Instanciacion floja -->
<script type='text/javascript' 
src='<?= LIBS_URL; ?>js/app.masterdetail.js'></script>