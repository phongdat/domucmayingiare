<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>
<div id="footer">
  <div class="div0">
	<div class="f_showroom">
		<div class="f_top">
		  <h3 class="f_top_name"><?php echo $owner; ?></h3>
		</div>
		<div class="f_middle">
			<div class="fsinfo fs_address"><b>Địa chỉ:</b> <span><?php echo $address;?></span></div>
			<div class="fsinfo fs_hotline"><b>Hotline:</b> <span><?php echo $hotline;?></span></div>
			<div class="fsinfo fs_telephone"><b>Điện thoại:</b> <span><?php echo $telephone;?></span></div>
			<?php if($fax) { ?><div class="fsinfo fs_fax"><b>Fax:</b> <span><?php echo $fax;?></span></div><?php } ?>
			<?php echo $footer; ?>
		</div>
	</div>
	<?php foreach ($cinformations as $cinformation) { ?>
	<div class="f_info">
		<div class="f_top">
		  <h3 class="f_top_name"><?php echo $cinformation['name']; ?></h3>
		</div>
		<div class="f_middle">
			<div id="news">
				<ul>
				<?php foreach ($cinformation['informations'] as $information) { ?>
					<li><a href="<?php if($information['link']){ echo $information['link']; } else { echo $information['href']; } ?>"><?php echo $information['name']; ?></a></li>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	<div style="clear:both;"></div>
  </div>
  <div class="div2"><?php echo $text_powered_by; ?><br/><?php echo $text_powered; ?></div>
  <div class="div1">
	<div class="menufooter">
		<a class="left" href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
		<a href="<?php echo $gioithieu; ?>"><?php echo $text_gioithieu; ?></a>
		<a href="<?php echo $tintuc; ?>"><?php echo $text_news; ?></a>
		<a href="<?php echo $special; ?>"><?php echo $text_special; ?></a>
		<a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
		<a class="right" href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a>
	</div>
  </div>
  <?php if($hotkeyword) { ?>
  <div class="hotkeyword">
	<div class="hkey_info"><?php echo $hotkeyword; ?></div>
  </div>
  <?php } ?>
  <div style="clear:both;"></div>
</div>
</div>
</body></html>