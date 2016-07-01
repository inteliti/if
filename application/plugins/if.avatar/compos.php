<?php
$PLG_URL = PLUGINS_URL . 'if.avatar/';
$PLG_PATH = PLUGINS_PATH .'if.avatar/';

$this->load->helper("url");

?>

<div id="ifAvatar">
	<ul class="nav nav-tabs">
		<li class="active">
			
			<a href="#cam" data-toggle="tab">Usar WebCam</a>
		
		</li>
		<li>
		
			<a href="#upl" data-toggle="tab">Subir Imagen</a>
		
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="cam">
			
			<?php include $PLG_PATH . "__cam_html5.php"; ?>

		</div>
		<div class="tab-pane fade" id="upl">

			<?php include $PLG_PATH . "__upload.php"; ?>

		</div>
	</div>
</div>