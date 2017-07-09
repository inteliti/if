<?php
if(empty($FILE_NAME))
{
	$FILE_NAME = '';
}
?>

<div id="ifAvatarCam">
	<div id="html5Cam">

		<video id="video" class="foto"></video>
		<canvas id="canvas"></canvas>

		<span id="mark" class="mark"></span>

	</div>

	<button class="btn btn-primary btn-block" id="startbutton">
		<i class="fa fa-camera"></i>
		Tomar foto con WebCam
	</button>

	<div class="text-center text-danger" id="error"></div>
	<div class="text-center text-primary" id="msg"></div>
</div>

<script>
	IF_AVATAR.CAM.init();
</script>