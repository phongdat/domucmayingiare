<?php if ($logged) { ?>
<div class="popup logout-content">
	<div class="popupmiddle">
	<div class="text_notice confirm_logout"><?php echo $text_confirm_logout; ?></div>
	<table width="100%">
	<tr>
		<td align="right"><a id="button_yes" class="button"><span><?php echo $button_yes; ?></span></a></td>
		<td align="left"><a id="button_no" class="button"><span><?php echo $button_no; ?></span></a></td>
	</tr>
	</table>
	</div>
</div>
<script type="text/javascript"><!--
$('#cboxClose').hide();
$('.popupmiddle #button_yes').click(function(event) {
			$('.popup').load('index.php?route=account/logout');
			$('#showuser').load('index.php?route=common/header/account');
			$('#cart-online').load('index.php?route=common/header/cart');
	return false;
});
$('.popupmiddle #button_no').click(function(event) {
			$(document).ready(function(){$('#cboxClose').click();});
	return false;
});
//--></script>
<?php } else { ?>
<div class="popup">
	<div class="popupmiddle" style="padding:10px;">
	<div class="text_notice"><?php echo $text_confirm_logouted; ?></div>
	<table width="100%">
	<tr>
		<td align="center"><a id="button_yes" class="button"><span><?php echo $button_yes; ?></span></a></td>
	</tr>
	</table>
	</div>
</div>
<script type="text/javascript"><!--
$('#cboxClose').hide();
$('.popupmiddle #button_yes').click(function(event) {
			$(document).ready(function(){$('#cboxClose').click();});
			$('#showuser').load('index.php?route=common/header/account');
			$('#cart-online').load('index.php?route=common/header/cart');
	return false;
});
//--></script>
<?php } ?>