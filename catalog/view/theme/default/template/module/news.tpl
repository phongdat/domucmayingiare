<script type="text/javascript">
	  $(document).ready(function(){
		$('.sb_news').bxSlider({
		speed: 250,
		autoStart: false
		});
	  });
</script>
<div class="box news">
  <div class="right"></div>
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div class="middle">
    <ul class="sb_news">
      <?php for ($i = 0; $i < sizeof($newss); $i = $i + 5) { ?>
		<li><ul>
			<?php for ($j = $i; $j < ($i + 5); $j++) { ?>
				<?php if (isset($newss[$j])) { ?>
				<li <?php if($j%5 == 0) { echo 'class="sb_top"'; } ?>>
					<a href="<?php echo $newss[$j]['href']; ?>">
						<div class="sn_image"><img alt="<?php echo $newss[$j]['name']; ?>" src="<?php echo $newss[$j]['image']; ?>" /></div>
						<div class="sn_name"><?php echo $newss[$j]['name']; ?></div>
						<?php if($newss[$j]['date_added']) { ?><div class="sn_date_added"><?php echo $newss[$j]['date_added']; ?></div><?php } ?>
					</a>
				</li>
				<?php } ?>
			<?php } ?>
		</ul></li>
      <?php } ?>
    </ul>
	<a class="sb_xemthem" href="<?php echo $news_href; ?>">XEM THÊM</a>
  </div>
</div>