<?php
//================================================
// LIBRERIAS CORE, SIN ESTAS IF NO FUNCIONA
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
// CSSs BASES
//================================================
$libs = array(
	'bootstrap/min/bootstrap.min',
	'bootstrap/min/bootstrap-theme.min',
	'if/if.bootstrap.materialdesign',
	'fontawesome/css/font-awesome.min',
	'if/if'
);
foreach($libs as $l)
{
	$src = LIBS_URL.$l.".css";
	echo "<link rel='stylesheet' type='text/css' href='{$src}' />";
}

//================================================
// JSs BASES
//================================================
$libs = array(
	//'bootstrap/min/bootstrap.min'
	'bootstrap_material/js/material.min',
);
foreach($libs as $l)
{
	$src = LIBS_URL.$l.".js";
	echo "<script type='text/javascript' src='{$src}'></script>";
}

//================================================
// PLUGINS IF BASES
//================================================
$libs = array(
	'if.main', 'if.modal'
);
foreach($libs as $l)
{
	include_once PLUGINS_PATH."{$l}/_loader.php";
}
?>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.CSFR_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	IF_MAIN.CSFR_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';
	IF_MAIN.init();
</script>