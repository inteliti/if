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
			$is_img = 1;
		}
		else
		{
			$src = $PLG_URL . "img/filetype/{$ext}.png";
			$is_img = 0;
		}
		?>
		<img src="<?= $src ?>" data-remote="<?= $v ?>"
			 data-remote-path="<?= $FILES_URL . $v ?>" data-is-img="<?= $is_img ?>"
			 alt="" border="0" />
		 <?php endforeach ?>
</div>
<ul class="text-muted texts">
	<li class="text-zoom"></li>
	<li class="text-del"></li>
</ul>