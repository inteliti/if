<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IF</title>
	
	<?php include APPPATH . 'core/_if_loader.php'; ?>
	
	<link rel='stylesheet' type='text/css' href='<?= TMPL_URL ?>css/app.css' />
</head>
<body>
	<div class="container" id="canvas">
		<?php include APPPATH . "views/{$VIEW}.php"; ?>	
	</div>
</body>
</html>