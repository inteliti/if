
<!-- Third party libs -->
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap.min.css' />
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/bootstrap-theme.min.css' />
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/jquery.ui.min.css' />
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/third/icons.css' />

<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.ui.min.js'></script>
<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/md5.js'></script>

<!-- BEGIN IF LOADER -->
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/if/all.css' />

<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.main.js'></script>
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.init();
</script>
<!-- END IF LOADER -->

<?php
//PLUGINS
$plgns = array(
	
);
foreach($plgns as $v)
{
	include APPPATH . "plugins/{$v}/_loader.php";
}

//JS LIBS
$libs = array(
	//thirdparty
	
	//app
	
);
foreach($libs as $l)
{
	echo "<script type='text/javascript' src='"
	. SHARED_URL . "js/{$l}.js'></script>";
}

?>