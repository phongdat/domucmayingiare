<div class="popup" id="popup_order" style="text-align:left;">
	<div class="popuptitle" style="width:610px;"><?php echo $heading_title; ?></div>
	<div class="popupmiddle" style="width:600px;">
		<div class="warning_content">
			<div id="warning"></div>
		</div>
		<b style="margin-bottom: 2px; display: block;font-size:14px;"><?php echo $text_your_details; ?></b>
		<?php if (!$logged) { ?>
		<div class="content">
			<table>
			  <tr>
				<td width="150"><?php echo $entry_customername; ?></td>
				<td><input id="customername" type="text" name="customername" /><span class="required">(<font>*</font>)</span>
				</td>
			  </tr>
			  <tr>
				<td><?php echo $entry_email; ?></td>
				<td><input id="email" type="text" name="email" /><span class="required">(<font>*</font>)</span>
				</td>
			  </tr>
			  <tr>
				<td><?php echo $entry_telephone; ?></td>
				<td><input id="telephone" type="text" name="telephone" /><span class="required">(<font>*</font>)</span>
				</td>
			  </tr>
			</table>
		</div>
		<?php } else { ?>
		<div class="content">
			<table>
			  <tr>
				<td width="150"><?php echo $entry_customername; ?></td>
				<td><?php echo $customername; ?><input id="customername" type="hidden" name="customername" value="<?php echo $customername; ?>" />
				</td>
			  </tr>
			  <tr>
				<td><?php echo $entry_email; ?></td>
				<td><?php echo $email; ?><input id="email" type="hidden" name="email" value="<?php echo $email; ?>" />
			  
				</td>
			  </tr>
			  <tr>
				<td><?php echo $entry_telephone; ?></td>
				<td><?php echo $telephone; ?><input id="telephone" type="hidden" name="telephone" value="<?php echo $telephone; ?>" />
				</td>
			  </tr>
			</table>
		</div>
		<?php } ?>
		<div class="address" style="margin-bottom:10px;">
		  <b style="margin-bottom: 2px; display: block;font-size:14px;"><?php echo $text_your_address; ?></b>
		  <div class="content">
			<table>
			  <tr>
				<td width="150"><?php echo $entry_address; ?></td>
				<td><input type="text" name="address" value="<?php echo $address; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_city; ?></td>
				<td><input type="text" name="city" value="<?php echo $city; ?>" /></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_country; ?></td>
				<td><select name="country_id" id="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=account/create/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
					<option value="FALSE"><?php echo $text_select; ?></option>
					<?php foreach ($countries as $country) { ?>
					<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
					<?php } ?>
				  </select></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_zone; ?></td>
				<td><select name="zone_id">
				  </select></td>
			  </tr>
			</table>
		  </div>
		</div>
		<div class="buttons">
		<table>
		  <tr>
			<td align="right"><a onclick="confirm();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
		  </tr>
		</table>
		</div>
	</div>
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=checkout/cart/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
$('#country_id').attr('value', '<?php echo $country_id; ?>');
<?php if(!$getProducts) { ?>
	window.location.reload();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
function confirm() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=checkout/cart/confirm',
		dataType: 'json',
		data: 'customername=' + encodeURIComponent($('input[name=\'customername\'][id=\'customername\']').val()) + '&email=' + encodeURIComponent($('input[name=\'email\'][id=\'email\']').val()) + '&telephone=' + encodeURIComponent($('input[name=\'telephone\'][id=\'telephone\']').val()) + '&address=' + encodeURIComponent($('input[name=\'address\']').val()) + '&city=' + encodeURIComponent($('input[name=\'city\']').val()) + '&country_id=' + encodeURIComponent($('select[name=\'country_id\']').val()) + '&zone_id=' + encodeURIComponent($('select[name=\'zone_id\']').val()),
		beforeSend: function() {
			$('.success, .warning, .error').remove();
			$('#cboxOverlay').after('<div class="waiting"><p><?php echo $text_wait; ?></p></div>');
		},
		complete: function() {
			$('.waiting').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#warning').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$(document).colorbox({
					overlayClose: false,
					escKey: false,
					opacity: 0.5,
					open: true,
					href: 'index.php?route=checkout/success'
				});
				$('#cart-online').load('index.php?route=common/header/cart');
				
				$('input[name=\'customername\']').val('');
				$('input[name=\'email\']').val('');
				$('input[name=\'telephone\']').val('');
				$('input[name=\'address\']').val('');
				$('input[name=\'city\']').val('');
				$('select[name=\'country_id\']').val('');
				$('select[name=\'zone_id\']').val('');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$('.popup input').keydown(function(e) {
	if (e.keyCode == 13) {
		confirm();
	}
});
//--></script>
</div>