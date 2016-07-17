<?php
if(empty($FILE_NAME))
{
	$FILE_NAME = '';
}
?>

<div id="html5Cam">

	<video id="video" class="foto"></video>
	<canvas id="canvas"></canvas>

	<span id="mark" class="mark"></span>

</div>

<p>
	<button class="btn btn-primary btn-block" id="startbutton">
		<i class="fa fa-camera"></i>
		Tomar foto con WebCam
	</button>
</p>
<div id="msgcam" class="">
	<span>Subiendo imagen...</span>
</div>


<script>
	(function () {

		var
			SEL = '#ifAvatar ',
			video = document.querySelector(SEL + '#video'),
			canvas = document.querySelector(SEL + '#canvas'),
			startbutton = document.querySelector(SEL + '#startbutton'),
			width = 200,
			height = 150;

		navigator.getMedia = (navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);

		navigator.getMedia({
			video: true,
			audio: false
		}, function (stream)
		{
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}

			IF_AVATAR.localstream = stream;
			video.play();
		},
			function (err)
			{
				console.log("An error occured! " + err);
			}
		);

		video.setAttribute('width', width);
		video.setAttribute('height', height);
		canvas.setAttribute('width', width);
		canvas.setAttribute('height', height);

		startbutton.addEventListener('click', function (ev)
		{
			canvas.width = width;
			canvas.height = height;

			canvas.getContext('2d').drawImage(video, 0, 0, width, height);

			upload();
			ev.preventDefault();
		}, false);

		function upload()
		{
			var head = /^data:image\/(png|jpeg);base64,/,
				data = '',
				fd = new FormData(),
				xhr = new XMLHttpRequest()
				;

			setstate('uploading');

			data = canvas.toDataURL('image/jpeg', 0.9).replace(head, '');

			fd.append('contents', data);
			fd.append('filename', '<?= $FILE_NAME ?>');
			fd.append('upload_path', '<?= $UPLOAD_PATH ?>');
			fd.append('square', $('#square').is(":checked") ? 1 : 0);
			xhr.open('POST', '<?= $PLG_URL ?>saveimg_html5.php');
			xhr.addEventListener('error', function (ev) {
				console.log('Upload Error!');
				setstate('upload_error');
			}, false);
			xhr.addEventListener('load', function (ev) {
				setstate('uploaded');
			}, false);
			xhr.send(fd);
		}

		function setstate(s)
		{
			$('#msgcam').show();

			if (s == 'uploading')
			{
				$("#msgcam span").show().html('Subiendo imagen...');
			} else if (s == 'uploaded')
			{
				$("#msgcam span").show().html(
					'Imagen subida satisfactoriamente.'
					);
				IF_AVATAR.callbackWebcam();
			} else if (s == 'upload_error')
			{
				$("#msgcam span").show().html('ERROR al subir imagen.');
			}
		}

	})();
</script>