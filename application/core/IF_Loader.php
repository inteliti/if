<?php
//================================================
// BIBLIOTECAS ESENCIALES
//================================================
$libs = array(
	'jquery.min', 'jquery.mobile-events.min', 'jquery.easing.min',
);
foreach($libs as $l)
{
	$src = LIBS_URL."js/{$l}.js";
	echo "<script type='text/javascript' src='{$src}'></script>";
}

//================================================
// PLUGINS ESENCIALES
//================================================
$libs = array(
	'if.main', 'if.modal'
);
foreach($libs as $l)
{
	if_plugin($l);
}

//================================================
// PLANTILLA
//================================================
include_once TMPL_PATH.'_loader.php';

?>
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.CSFR_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	IF_MAIN.CSFR_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';
	IF_MAIN.init();
</script>