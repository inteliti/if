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

<!-- Instanciacion floja -->
<script type='text/javascript' 
src='<?= LIBS_URL; ?>js/app.avatar.js'></script>
