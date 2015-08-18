
<!-- Third party libs -->
<link rel='stylesheet' type='text/css' 
	  href='<?=SHARED_URL; ?>css/third/bootstrap/bootstrap.min.css' />
<link rel='stylesheet' type='text/css' 
	  href='<?=SHARED_URL; ?>css/third/bootstrap/bootstrap-theme.min.css' />
<link rel='stylesheet' type='text/css'
	  href='<?=SHARED_URL; ?>css/third/jquery.ui.min.css' />
<link rel='stylesheet' type='text/css'
	  href='<?=SHARED_URL; ?>css/third/icons.css' />

<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/jquery.ui.min.js'></script>
<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
<script type='text/javascript' 
src='<?=SHARED_URL; ?>js/third/md5.js'></script>
<script type='text/javascript' 
src='<?=SHARED_URL; ?>js/third/jquery.validate.min.js'></script>
<script type='text/javascript' src='<?=SHARED_URL; ?>js/third/jquery.validate.additional-methods.min.js'></script>

<!-- BEGIN IF LOADER -->
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/if/all.css' />

<script type='text/javascript' src='<?=SHARED_URL; ?>js/if/if.main.js'></script>
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.CSFR_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	IF_MAIN.CSFR_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';
	IF_MAIN.init();
</script>
<!-- END IF LOADER -->

<?php
//PLUGINS
$plgns = array(
	'if.modal','if.upload',
);
foreach($plgns as $v)
{
	include APPPATH . "plugins/{$v}/_loader.php";
}

//JS LIBS
$libs = array(
	//thirdparty
	
	//app
	'app/upload_demo'
);
foreach($libs as $l)
{
	echo "<script type='text/javascript' src='"
	. SHARED_URL . "js/{$l}.js'></script>";
}

?>