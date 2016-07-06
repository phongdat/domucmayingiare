<div class="popup" id="popup_order" style="text-align:left;">
<div class="popuptitle" style="width:610px;"><?php echo $heading_title; ?></div>
<div class="popupmiddle" style="width:600px;">
<div class="text_notice"><?php echo $text_message; ?></div>
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
	return false;
});
//--></script>