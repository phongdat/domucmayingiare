<?php if (!$logged) { ?>
	<a onclick="<?php echo $loginpopup; ?>" class="cb_account"><span class="cbaspan"><span class="cbasp login"></span><?php echo $text_login; ?></span></a>
	<a onclick="location.href='<?php echo $create_account; ?>'"><span class="cbaspan"><span class="cbasp create"></span><?php echo $text_create; ?></span></a>
<?php }  else { ?>
  <a onclick="<?php echo $logoutpopup; ?>" class="cb_account"><span class="cbaspan"><span class="cbasp logout"></span><?php echo $text_logout; ?></span></a>
  <a href="<?php echo $account; ?>"><span class="cbaspan"><span class="cbasp account"></span><?php echo $text_account; ?></span></a>

<?php } ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.cb_account').colorbox({
		overlayClose: false,
		initialHeight: "220",
		initialWidth: "322",
		escKey: false,
		opacity: 0.5
	});
});
//--></script>