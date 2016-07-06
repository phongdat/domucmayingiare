<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/support.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="tabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input size="40"  name="support_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($support_description[$language['language_id']]) ? $support_description[$language['language_id']]['name'] : ''; ?>" />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_csupport; ?></td>
            <td><select name="csupport_id">
                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                <?php foreach ($csupports as $csupport) { ?>
                <?php if ($csupport['csupport_id'] == $csupport_id) { ?>
                <option value="<?php echo $csupport['csupport_id']; ?>" selected="selected"><?php echo $csupport['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $csupport['csupport_id']; ?>"><?php echo $csupport['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_yahoo; ?></td>
            <td><input size="40"  name="support_description[<?php echo $language['language_id']; ?>][yahoo_id]" value="<?php echo isset($support_description[$language['language_id']]) ? $support_description[$language['language_id']]['yahoo_id'] : ''; ?>" />
              </td>
          </tr>
          <tr>
            <td><?php echo $entry_skype; ?></td>
            <td><input size="40"  name="support_description[<?php echo $language['language_id']; ?>][skype_id]" value="<?php echo isset($support_description[$language['language_id']]) ? $support_description[$language['language_id']]['skype_id'] : ''; ?>" />
              </td>
          </tr>
          <tr>
            <td><?php echo $entry_sdt; ?></td>
            <td><input size="40"  name="support_description[<?php echo $language['language_id']; ?>][telephone]" value="<?php echo isset($support_description[$language['language_id']]) ? $support_description[$language['language_id']]['telephone'] : ''; ?>" />
              </td>
          </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>');
<?php } ?>	  
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>