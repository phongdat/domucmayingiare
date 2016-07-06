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
    <div class="content" style="text-align:center;">
        <table width="100%">
		<tr>
            <td align="right" width="40%"><b><?php echo $entry_email; ?></b></td>
            <td align="left" width="60%"><input type="text" name="email" /></td>
		</tr>
		<tr>
            <td align="right"><b><?php echo $entry_password; ?></b></td>
            <td align="left"><input type="password" name="password" /></td>
        </tr>
		<tr>
			<td></td>
			<td align="left"><a onclick="login();" class="button"><span><?php echo $button_login; ?></span></a></td>
		</tr>
		</table>
		<br/>
		<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten_password; ?></a> | <a href="<?php echo $create; ?>"><?php echo $text_account; ?></a>
    </div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>

</div>
<script type="text/javascript"><!--
function login() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=account/login/login',
		dataType: 'json',
		data: 'email=' + encodeURIComponent($('input[name=\'email\']').val()) + '&password=' + encodeURIComponent($('input[name=\'password\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#warning').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#warning').after('<div class="warning" style="font-size:10px;">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#warning').after('<div class="success">' + data.success + '</div>');
				window.location.href="<?php echo $account ; ?>";
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$('#content input').keydown(function(e) {
	if (e.keyCode == 13) {
		login();
	}
});
//--></script>
<?php echo $footer; ?> 