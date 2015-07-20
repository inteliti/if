<?php
$PATH = APP_URL . 'plugins/if.modal/';
?>
<!-- if.modal loader -->

<link rel="stylesheet" type="text/css" href="<?= $PATH; ?>css.css" />
<script type='text/javascript' src='<?= $PATH; ?>if.modal.js'></script>
<script>
	$(function()
	{
		$('body').append(   '<div id="myModal" class="modal fade">'+
								'<div id="dialog" class="modal-dialog modal-lg">'+
									'<div id="ifModal-content" class="modal-content">'+
									'</div>'+
								'</div>'+
							'</div>');
	});
</script>
<!-- /if.modal loader -->
