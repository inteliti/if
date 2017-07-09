<input type="hidden" name="PLG_URL" value="<?= addslashes($PLG_URL) ?>" />
<input type="hidden" name="CONFIG" value='<?= json_encode($CONFIG) ?>' />
<input type="hidden" class="remove_remote_files" value="" />
<div class="thumbs">
	<?php foreach($FILES as $v): ?>
		<?php
		$a = explode('.', $v);
		$ext = strtolower(array_pop($a));
		?>
		<?php
		if(
			$ext == 'jpg' || $ext == 'jpeg' ||
			$ext == 'png' || $ext == 'gif' || $ext == 'bmp'
		)
		{
			$src = $FILES_URL . $v;
		}
		else
		{
			$src = $PLG_URL . "img/filetype/{$ext}.png";
		}
		?>
		<img src="<?= $src ?>" data-remote="<?= $v ?>"
			 alt="" border="0" />
		 <?php endforeach ?>
</div>
<ul class="text-muted texts">
	<li class="text-zoom"></li>
	<li class="text-del"></li>
</ul>