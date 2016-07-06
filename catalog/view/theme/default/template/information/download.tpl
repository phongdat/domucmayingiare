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
 	<table width="98%" border="0" cellpadding="0" style="margin:5px;border-collapse: collapse">
	<?php foreach ($downloads as $download) { ?>
		<tr>
		<td valign="top">
		<div style="text-align:left;padding:10px 5px 5px;"><b><?php echo $download['name']; ?></b> - <a href="<?php echo $download['filename']; ?>"><?php echo $heading_title; ?></a> - <span style="font-size:11px;color:#888"><?php echo $download['date_added']; ?></span></div>
		</td>
		</tr>
    <?php } ?>
	</table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 