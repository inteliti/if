<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>IF - Demos</title>

		<?php include APPPATH . 'core/If_Loader.php'; ?>

		<link rel='stylesheet' type='text/css' href='<?= TMPL_URL ?>css/app.css' />
	</head>
	<body>
		
		<?php include APPPATH . "views/{$VIEW}.php"; ?>

	</body>
</html>