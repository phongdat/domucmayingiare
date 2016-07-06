<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="languages" class="tabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
		<div id="tabs" class="tabs" style="margin-bottom:0;">
			<a tab="#tab_general">Tổng quan</a>
			<a tab="#tab_seo">SEO Google</a>
		</div>
		<div id="tab_general" class="tab_more">
			<table class="form">
			  <tr>
				<td><span class="required">*</span> <?php echo $entry_name; ?></td>
				<td><input name="information_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['name'] : ''; ?>" />
				  <?php if (isset($error_name[$language['language_id']])) { ?>
				  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
				  <?php } ?></td>
			  </tr>          
			  <tr>
				<td><?php echo $entry_description; ?></td>
				<td><textarea name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?></textarea></td>
			  </tr>
			</table>
		</div>
		<div id="tab_seo" class="tab_more">
			<table class="form">       
			  <tr>            
				  <td>SEO <?php echo $entry_name; ?></td>
				  <td><input name="information_description[<?php echo $language['language_id']; ?>][name_seo]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['name_seo'] : ''; ?>" /></td> 
			  </tr>
			  <tr>
				<td>Meta mô tả</td>
				<td><textarea name="information_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
			  </tr>
			  <tr>
				<td>Keywords</td>
				<td><textarea name="information_description[<?php echo $language['language_id']; ?>][keywords]" cols="40" rows="5"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['keywords'] : ''; ?></textarea></td>
			  </tr>
			</table>
		</div>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
            <td><?php echo $entry_cinformation; ?></td>
            <td><select name="cinformation_id">
                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                <?php foreach ($cinformations as $cinformation) { ?>
                <?php if ($cinformation['cinformation_id'] == $cinformation_id) { ?>
                <option value="<?php echo $cinformation['cinformation_id']; ?>" selected="selected"><?php echo $cinformation['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $cinformation['cinformation_id']; ?>"><?php echo $cinformation['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select></td>
        </tr>	
        <tr>
          <td>Liên kết thay thế</td>
          <td><input name="link" value="<?php echo $link; ?>" /></td>
        </tr>
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
$.tabs('#languages.tabs a'); 
$.tabs('#tabs.tabs a'); 
//--></script>
<?php echo $footer; ?>