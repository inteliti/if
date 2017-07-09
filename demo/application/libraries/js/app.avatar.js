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
		controller: 'Avatar', 

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