<?php
if_plugin(array('if.modal', 'if.avatar', 'toastr'));

$filename = 'avatar/p10.jpg';
$avatar = ASSETS_URL . (
	file_exists(ASSETS_PATH . $filename) ? $filename : 'avatar/none.jpg'
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
	function updImg(imgPath)
	{
		var rand = Math.random(); //forzar recarga de imagen
		$("#img").attr('src', imgPath + '?' + rand);
	}
	function noneImg()
	{
		$("#img").attr('src', '<?= ASSETS_URL ?>avatar/none.jpg');
	}
	function avatar()
	{
		IF_AVATAR.open({
			title: 'Cambia tu avatar',
			
			//DEBERIA ser un controlador que HEREDE de IF_Avatar,
			//lo hago asi por cuestiones de DEMO
			controller: 'IF_Avatar', 
			
			//ID unico que identifica al objeto dueño del avatar.
			//El avatar será renombrado con este ID (ej: 10.jpg)
			id: '10', 

			//Establecer para habilitar corte de imagen (desabilitado 
			//por defecto). Recibe la misma config de cropper, ver:
			//https://github.com/fengyuanchen/cropper/blob/master/README.md
			//
			crop: {
				aspectRatio: 1 / 1 //1/1, 4/3, 16/9, etc
			},
			
			//Callbacks
			onUpload: function (imgPath, type)
			{
				updImg(imgPath);
				toastr.success('Callback: avatar SUBIDO. TIPO = ' + type);
			},
			onDelete: function (response)
			{
				noneImg();
				toastr.success('Callback: avatar ELIMINADO');
			}
		});
	}
</script>