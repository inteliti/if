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
// BOOTSTRAP
//================================================
$libs = array(
	'bootstrap.min'
);
foreach($libs as $l)
{
	$src = LIBS_URL."bootstrap/min/{$l}.js";
	echo "<script type='text/javascript' src='{$src}'></script>";
}
$libs = array(
	'bootstrap.min', 'bootstrap-theme.min'
);
foreach($libs as $l)
{
	$src = LIBS_URL."bootstrap/min/{$l}.css";
	echo "<link rel='stylesheet' type='text/css' href='{$src}' />";
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
$libs = array(
	'if'
);
foreach($libs as $l)
{
	$src = LIBS_URL."if/{$l}.css";
	echo "<link rel='stylesheet' type='text/css' href='{$src}' />";
}
?>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' 
	  rel='stylesheet' type='text/css'>
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.CSFR_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	IF_MAIN.CSFR_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';
	IF_MAIN.init();
</script>