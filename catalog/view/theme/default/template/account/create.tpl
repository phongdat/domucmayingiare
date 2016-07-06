<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <div id="warning"></div>
      <b style="margin-bottom: 2px; display: block;font-size:14px;"><?php echo $text_your_details; ?></b>
      <div class="content">
        <table>
          <tr>
            <td width="150"><?php echo $entry_customername; ?></td>
            <td><input type="text" name="customername" /><span class="required">(<font>*</font>)</span>
              <div id="customername"></div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><input type="text" name="email" /><span class="required">(<font>*</font>)</span>
              <div id="email"></div>
            </td>
          </tr>
          <tr>
            <td width="150"><?php echo $entry_password; ?></td>
            <td><input type="password" name="password" /><span class="required">(<font>*</font>)</span>
              <div id="password"></div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_confirm; ?></td>
            <td><input type="password" name="confirm" /><span class="required">(<font>*</font>)</span>
              <div id="confirm"></div>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_telephone; ?></td>
            <td><input type="text" name="telephone" /><span class="required">(<font>*</font>)</span>
              <div id="telephone"></div>
            </td>
          </tr>
        </table>
      </div>
	<div class="address" style="margin-bottom:10px;">
      <b style="margin-bottom: 2px; display: block;font-size:14px;"><?php echo $text_your_address; ?></b>
      <div class="content">
        <table>
          <tr>
            <td width="150"><?php echo $entry_address; ?></td>
            <td><input type="text" name="address" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><input type="text" name="city" /></td>
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
	<input type="hidden" name="newsletter" value="1" />
      <?php if ($text_agree) { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>
            <td width="5" style="padding-top: 5px;"><input type="checkbox" name="agree" value="1" /></td>
            <td align="right" width="70"><a onclick="createajax();" class="button"><span><?php echo $button_create; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } else { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="right"><a onclick="createajax();" class="button"><span><?php echo $button_create; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
  <script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=account/create/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
$('#country_id').attr('value', '<?php echo $country_id; ?>');
//--></script>
<script type="text/javascript"><!--
function createajax() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=account/create/createajax',
		dataType: 'json',
		data: 'customername=' + encodeURIComponent($('input[name=\'customername\']').val()) + '&email=' + encodeURIComponent($('input[name=\'email\']').val()) + '&password=' + encodeURIComponent($('input[name=\'password\']').val()) + '&confirm=' + encodeURIComponent($('input[name=\'confirm\']').val()) + '&telephone=' + encodeURIComponent($('input[name=\'telephone\']').val()) + '&address=' + encodeURIComponent($('input[name=\'address\']').val()) + '&city=' + encodeURIComponent($('input[name=\'city\']').val()) + '&country_id=' + encodeURIComponent($('select[name=\'country_id\']').val()) + '&zone_id=' + encodeURIComponent($('select[name=\'zone_id\']').val()) + '&newsletter=' + encodeURIComponent($('input[name=\'newsletter\']').val()) + '&agree=' + encodeURIComponent($('input[name=\'agree\']:checked').val() ? $('input[name=\'agree\']:checked').val() : ''),
		beforeSend: function() {
			$('.success, .warning, .error').remove();
			$('#warning').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#warning').after('<div class="warning">' + data.error + '</div>');
			}

			if (data.success) {
				$('#showuser').load('index.php?route=common/header/account');
				$('.bt-cart').load('index.php?route=common/header/cart');
				$('#content').load('index.php?route=account/success');
				
				$('input[name=\'customername\']').val('');
				$('input[name=\'email\']').val('');
				$('input[name=\'password\']').val('');
				$('input[name=\'confirm\']').val('');
				$('input[name=\'telephone\']').val('');
				$('input[name=\'newsletter\']').val('');
				$('input[name=\'address\']').val('');
				$('input[name=\'city\']').val('');
				$('select[name=\'country_id\']').val('');
				$('select[name=\'zone_id\']').val('');
				$('input[name=\'agree\']:checked').attr('checked', '');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$('.content input').keydown(function(e) {
	if (e.keyCode == 13) {
		createajax();
	}
});
//--></script>
<?php echo $footer; ?> 