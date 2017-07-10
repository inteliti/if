<div id="ifAvatar">
	<ul class="nav nav-tabs">
		<li class="active cam">
			<a href="#" onclick="ifAvatarCam()" data-toggle="tab">
				Usar WebCam
			</a>
		</li>
		<li class="upl">
			<a href="#" onclick="ifAvatarUpl()" data-toggle="tab">
				Subir Imagen
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="active" id="cam">

			<?php include $PLG_PATH . "compos_cam.php"; ?>

		</div>
		<div id="upl">

			<?php include $PLG_PATH . "compos_file.php"; ?>

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