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
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="address">
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_edit_address; ?></b>
      <div class="content">
        <table>
          <tr>
            <td width="150"><?php echo $entry_customername; ?></td>
            <td><input type="text" name="customername" value="<?php echo $customername; ?>" /><span class="required">(<font>*</font>)</span>
              <?php if ($error_customername) { ?>
              <span class="error"><?php echo $error_customername; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_address; ?></td>
            <td><input type="text" name="address" value="<?php echo $address; ?>" /><span class="required">(<font>*</font>)</span>
              <?php if ($error_address) { ?>
              <span class="error"><?php echo $error_address; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_city; ?></td>
            <td><input type="text" name="city" value="<?php echo $city; ?>" /><span class="required">(<font>*</font>)</span>
              <?php if ($error_city) { ?>
              <span class="error"><?php echo $error_city; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><select name="country_id" id="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=account/address/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
                <option value="FALSE"><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
              </select><span class="required">(<font>*</font>)</span>
              <?php if ($error_country) { ?>
              <span class="error"><?php echo $error_country; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td><select name="zone_id">
              </select><span class="required">(<font>*</font>)</span>
              <?php if ($error_zone) { ?>
              <span class="error"><?php echo $error_zone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_default; ?></td>
            <td class="radio"><table><tr><?php if ($default) { ?>
              <td width="5"><input type="radio" name="default" value="1" checked="checked" /></td>
              <td><?php echo $text_yes; ?></td>
              <td width="5"><input type="radio" name="default" value="0" /></td>
              <td><?php echo $text_no; ?></td>
              <?php } else { ?>
              <td width="5"><input type="radio" name="default" value="1" /></td>
              <td><?php echo $text_yes; ?></td>
              <td><input type="radio" name="default" value="0" checked="checked" /></td>
              <td><?php echo $text_no; ?></td>
              <?php } ?></table></tr></td>
          </tr>
        </table>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right"><a onclick="$('#address').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=account/address/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');

$('#country_id').attr('value', '<?php echo $country_id; ?>');
//--></script>
<?php echo $footer; ?> 