<?php
if_plugin(array('if.modal', 'if.avatar', 'toastr'));

$filename = 'avatar/p10';
$avatar = ASSETS_URL . (
	file_exists(ASSETS_PATH . $filename.'.jpg') 
		? $filename.'.jpg' 
		: file_exists(ASSETS_PATH . $filename.'.png') 
			? $filename.'.png' 
			: 'avatar/none.jpg'
	);
?>

<h1>
	if.avatar DEMO
</h1>
<hr />


<div>
	<img src="<?= $avatar ?>" alt="" border="0" id="img"
		 style="border:1px solid #a0a0a0;padding:5px;width:150px"/>
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
	function noneImg()
	{
		$("#img").attr('src', '<?= ASSETS_URL ?>avatar/none.jpg');
	}
	function avatar()
	{
		IF_AVATAR.open({
			title: 'Cambia tu avatar',
			controller: 'IF_Avatar',//DEBERIA ser un controlador que HEREDE de
									//IF_Avatar, lo hago asi por cuestiones
									//de DEMO
			id: '10',
			callbackWebcam: function ()
			{
				updImg();
				toastr.success('Callback: carga con WEBCAM');
			},
			callbackUpload: function ()
			{
				updImg();
				toastr.success('Callback: carga con UPLOAD');
			},
			callbackDelete: function ()
			{
				noneImg();
				toastr.success('Callback: avatar ELIMINADO');
			}
		});
	}
</script>