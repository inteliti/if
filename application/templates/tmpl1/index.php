<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>UNIRADmin</title>
	
	<?php include APPPATH . 'core/_if_loader.php'; ?>
	
	<link rel='stylesheet' type='text/css' href='<?= TMPL_PATH ?>css/app.css' />
</head>
<body>
	<?php include 'navbar.php' ?>
	<div class="container" id="canvas">
		<?= include APPPATH.'views/demos/masterdetail.php'; ?>
	</div>
</body>
</html>