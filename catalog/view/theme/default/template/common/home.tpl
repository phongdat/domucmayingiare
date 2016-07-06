<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/listpro.css?ver=1.4" />
<?php echo $column_right; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/nivoSlider.css" />
<script src="catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js" type="text/javascript"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#slideshow').nivoSlider();
});
--></script>
<div id="content">
<div class="slideshow">
	<div id="slideshow" class="nivoSlider">
		<?php foreach ($slideshows as $slideshow) { ?>
			<?php if($slideshow['link']) { ?>
				<a href="<?php echo $slideshow['link']; ?>"><img src='<?php echo $slideshow['image']; ?>' /></a>
			<?php } else { ?>
				<img src='<?php echo $slideshow['image']; ?>' />
			<?php } ?>
		<?php } ?>
	</div>
</div>
</div>
<?php foreach ($chomes as $chome) { ?>
<?php if($chome['products']) { ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h2><?php echo $chome['name']; ?></h2>
    </div>
  </div>
  <div class="middle listpro">
	<ul class="list">
      <?php for ($i = 0; $i < sizeof($chome['products']); $i = $i + 4) { ?>
		<li class="lli<?php if(($i+4) >= sizeof($chome['products'])) { echo ' lbottom'; } ?><?php if ($display_price) {echo " ldisplayprice";} ?>">
        <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
		<div class="lpro<?php if($j == $i+3) {echo ' lright nocat';} elseif($j == $i) {echo ' lleft nocat';} ?>">
		  <?php if (isset($chome['products'][$j])) { ?>
          <a href="<?php echo $chome['products'][$j]['href']; ?>"><img src="<?php echo $chome['products'][$j]['thumb']; ?>"  alt="<?php echo $chome['products'][$j]['name']; ?>" /></a>
          <div class="ltitle"><a href="<?php echo $chome['products'][$j]['href']; ?>"><?php echo $chome['products'][$j]['name']; ?></a></div>
		  <div class="lmodel"><b><?php echo $text_model; ?></b> <?php echo $chome['products'][$j]['model']; ?></div>
		  <?php if ($display_price) { ?>
			  <div class="lprice">
			  <?php if (!$chome['products'][$j]['special']) { ?><br/>
			  <b><?php echo $text_price; ?></b> <span class="price"><?php echo $chome['products'][$j]['price']; ?></span><br />
			  <?php } else { ?>
			  <span class="lspecial"><?php echo $chome['products'][$j]['price']; ?></span><br/>
			  <b><?php echo $text_price; ?></b> <span class="price"><?php echo $chome['products'][$j]['special']; ?></span>
			  <?php } ?>
			  </div>
          <?php } ?>
		  <?php } ?>
		</div>
        <?php } ?>
		</li>
      <?php } ?>
	</ul>
  </div>
</div>
<?php } ?>
<?php } ?>
<?php if($popup_status) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$(this).colorbox({
		overlayClose: false,
		escKey: false,
		opacity: 0.5,
		open: true,
		href: '<?php echo $link_popup; ?>'
	});
});
//--></script>
<?php } ?>
<?php echo $footer; ?> 