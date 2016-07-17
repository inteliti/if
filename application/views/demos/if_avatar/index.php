<?php
if_plugin('if.modal');
if_plugin('if.avatar');
if_plugin('toastr');
?>


<h1>
	if.avatar DEMO
</h1>
<hr />
Imagen cargada:
<div>
	<img src="<?= ASSETS_URL ?>avatar/p10.jpg" alt="" border="0"
		 id="img"
		 style="border:1px solid #a0a0a0;padding:5px;"/>
</div>

<button type="button" class="btn btn-default" 
		onclick="avatar()">
	Cambiar avatar
</button>
<script>
	function updImg()
	{
		var rand = Math.random();
		$("#img").attr('src', '<?= ASSETS_URL ?>avatar/p10.jpg?' + rand);
	}
	function avatar()
	{
		IF_AVATAR.open({
			title: 'Cambia tu avatar',
			controller: 'IF_Avatar',
			id: '10',
			callbackWebcam: function ()
			{
				IF_AVATAR.close();
				updImg();
				toastr.success('Callback: carga con WEBCAM');
			},
			callbackUpload: function ()
			{
				IF_AVATAR.close();
				updImg();
				toastr.success('Callback: carga con UPLOAD');
			}
		});
	}
</script>