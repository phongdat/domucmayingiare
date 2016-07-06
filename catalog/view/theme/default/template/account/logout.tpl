<div class="popup">
	<div class="popupmiddle" style="padding:10px;">
	<div class="text_notice"><?php echo $text_message; ?></div>
	<table width="100%">
	<tr>
		<td align="center"><a id="button_yes" class="button"><span><?php echo $button_yes; ?></span></a></td>
	</tr>
	</table>
	</div>
</div>
<script type="text/javascript"><!--
$('.popupmiddle #button_yes').click(function(event) {
			$(document).ready(function(){$('#cboxClose').click();});
			$('#showuser').load('index.php?route=common/header/account');
			$('#cart-online').load('index.php?route=common/header/cart');
	return false;
});
//--></script>