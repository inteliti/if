<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/jquery.min.js'></script>
<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/bootstrap.min.js'></script>
<script type='text/javascript'
src='<?=SHARED_URL; ?>js/third/md5.js'></script>

<!-- BEGIN IF LOADER -->
<link rel='stylesheet' type='text/css' href='<?=SHARED_URL; ?>css/if/all.css' />
<!-- END IF LOADER -->

<?php
//PLUGINS DE IF
$plgns = array(
	'if.main','if.modal','if.html5validator',
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
<script>
	IF_MAIN.CI_INDEX = '<?= INDEX_URL; ?>';
	IF_MAIN.CSFR_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
	IF_MAIN.CSFR_TOKEN = '<?= $this->security->get_csrf_hash(); ?>';
	IF_MAIN.init();
</script>