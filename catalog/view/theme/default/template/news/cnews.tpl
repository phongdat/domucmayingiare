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
    <?php if ($description) { ?>
    <div style="margin:0 5px 15px;"><?php echo $description; ?></div>
    <?php } ?>
 	<table width="98%" border="0" cellpadding="0" style="margin:5px;border-collapse: collapse">
	<?php foreach ($newss as $news) { ?>
		<tr>
		<td valign="top" colspan="2">
		<div style="font-weight:bold;text-align:left;padding:10px 5px 5px;"><a  href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></div>
		</td>
		</tr>
		<tr>
		<?php if ($news['image'] == "image/no_image.jpg") { ?>
		<td valign="top" colspan="2" style="font-size:12px;padding:0 5px">
		<?php if($news['date_added']) { ?><div class="cn_date_added"><?php echo $news['date_added']; ?></div> <?php } ?>
		<?php echo $news['description_no_image']; ?></td>
		<?php } else { ?>
		<td width="1%" valign="top">
		<a   href="<?php echo $news['href']; ?>"><img alt="<?php echo $news['name']; ?>" width="100" height="80" src="<?php echo $news['image']; ?>" /></a>
		</td>
		<td valign="top" style="padding:5px">
		<?php if($news['date_added']) { ?><div class="cn_date_added"><?php echo $news['date_added']; ?></div> <?php } ?>
		<?php echo $news['description']; ?>
		</td>
		<?php } ?>
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