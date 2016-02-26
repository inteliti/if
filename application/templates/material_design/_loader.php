<?php
//================================================
// CSSs
//================================================
$src = array(
	SHARED_URL . 'bootstrap/includes/bootstrap.min',
	//SHARED_URL.'bootstrap/includes/bootstrap-theme.min', 
	TMPL_URL . 'css/if.bootstrap.theme.materialdesign', //usaremos theme custom
	SHARED_URL . 'fontawesome/css/font-awesome.min',
);
foreach($src as $l)
{
	echo "<link rel='stylesheet' type='text/css' href='{$l}.css' />";
}

//================================================
// PLUGINS GLOBALES USADOS POR ESTA PLANTILLA
//================================================
$src = array(
	'jquery.enllax',
	'jquery.bigslide',
	'jquery.scrollto',
	'jquery.fadethis',
);
foreach($src as $l)
{
	if_plugin($l);
}

//================================================
// PLUGINS DE PLANTILLA
//================================================
$src = array(
	'jquery.materialripple',
	'jquery.hmbrgrmenu'
);
foreach($src as $l)
{
	if_plugin_tmpl($l);
}
?>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700' 
	  rel='stylesheet' type='text/css'>
<script>
	$(function () {
		$('.nav a, .btn, .ripple').materialripple();
		$(".if_fab").bigSlide({
			side: 'right',
			easyClose: false,
			menuWidth: '320px',
			push: '.if_drawer_push'
		});
		$(window).enllax();
		$('.hmbrgrmenu').hmbrgrmenu();
		
		$(window).fadeThis({
			reverse: false
		});
		
		
	});
</script>