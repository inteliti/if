<?php
$PLG_URL = PLUGINS_URL . 'if.avatar/';
$PLG_PATH = PLUGINS_PATH . 'if.avatar/';
$this->load->helper("url");
?>
<div id="ifAvatar">
	<ul class="nav nav-tabs">
		<li class="active cam">
			<a href="javascript:ifAvatarCam()" data-toggle="tab">
				Usar WebCam
			</a>
		</li>
		<li class="upl">
			<a href="javascript:ifAvatarUpl()" data-toggle="tab">
				Subir Imagen
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="active" id="cam">

			<?php include $PLG_PATH . "__cam_html5.php"; ?>

		</div>
		<div id="upl">

			<?php include $PLG_PATH . "__upload.php"; ?>

		</div>
	</div>
</div>
<script>
	function ifAvatarCam()
	{
		$("#ifAvatar #upl").hide();
		$("#ifAvatar #cam").fadeIn();
		$("#ifAvatar .nav li.upl").removeClass('active');
		$("#ifAvatar .nav li.cam").addClass('active');
	}
	function ifAvatarUpl()
	{
		$("#ifAvatar #cam").hide();
		$("#ifAvatar #upl").fadeIn();
		$("#ifAvatar .nav li.cam").removeClass('active');
		$("#ifAvatar .nav li.upl").addClass('active');
	}
</script>