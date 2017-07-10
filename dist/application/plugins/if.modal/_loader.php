<?php
$PATH = APP_URL . 'plugins/if.modal/';
?>
<!-- if.modal loader -->
<link rel="stylesheet" type="text/css" href="<?= $PATH; ?>css.css" />
<script type='text/javascript' src='<?= $PATH; ?>if.modal.js'></script>
<script>
	$(function ()
	{
		$('body').append(
			'<div id="ifModal" class=if_modal>'
			+ '<h1 class=if_modal_title></h1>'
			+ '<div class=if_modal_content></div>'
			+ '<div class=if_modal_btns></div>'
			+ '</div>'
			);
	});
</script>
<!-- /if.modal loader -->
