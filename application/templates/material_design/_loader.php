<?php
//================================================
// CSSs
//================================================
$src = array(
	SHARED_URL . 'bootstrap/includes/bootstrap.min',
	//SHARED_URL.'bootstrap/includes/bootstrap-theme.min', 
	TMPL_URL . 'css/if.bootstrap.theme.materialdesign', //usaremos theme custom
	SHARED_URL . 'fontawesome/css/font-awesome.min',
	//plugins
	TMPL_URL . 'js/jquery.materialripple/jquery.materialripple'
);
foreach($src as $l)
{
	echo "<link rel='stylesheet' type='text/css' href='{$l}.css' />";
}

//================================================
// JSs
//================================================
$src = array(
	//plugins
	TMPL_URL . 'js/jquery.materialripple/jquery.materialripple'
);
foreach($src as $l)
{
	echo "<script type='text/javascript' src='{$l}.js'></script>";
}

//================================================
// PLUGINS
//================================================
if_plugin('jquery.enllax');
?>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700' 
	  rel='stylesheet' type='text/css'>
<script>
	$(function () {
		$('.btn, .ripple').materialripple();
	});
</script>