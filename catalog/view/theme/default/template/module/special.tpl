<?php if($home_select) { ?>
<script type="text/javascript">
	  $(document).ready(function(){
		$('.listsp').bxSlider({
		speed: 250,
		autoStart: false
		});
	  });
</script>
<div class="box">
	<div class="right"></div>
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div class="middle special">
	<ul class="listsp">
      <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
		<li><ul class="sblist">
        <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
        <?php if (isset($products[$j])) { ?>
		<li <?php if($products[$j]['brief_description']) { ?> id="li<?php echo $products[$j]['product_id']; ?>"<?php } ?>>
		<a href="<?php echo $products[$j]['href']; ?>" class="sblist_info">
		  <div class="list_desc">
          <div class="list_image"><img src="<?php echo $products[$j]['thumb']; ?>"  alt="<?php echo $products[$j]['name']; ?>" /></div>
          <div class="list_name"><?php echo $products[$j]['name']; ?></div>
		  <?php if ($display_price) { ?>
			  <div class="list_price">
			  <?php if (!$products[$j]['special']) { ?>
			  <span class="price"><?php echo $text_price; ?> <font><?php echo $products[$j]['price']; ?></font></span>
			  <?php } else { ?>
			  <span class="special"><font><?php echo $products[$j]['price']; ?></font></span><br/>
			  <span class="price"><?php echo $text_price; ?> <font><?php echo $products[$j]['special']; ?></font></span>
			  <?php } ?>
			  </div>
          <?php } ?>
		  </div>
		  <div class="list_bdesc">
			<span class="lbdesc"><?php echo str_replace('&nbsp;','',$products[$j]['brief_description']); ?></span>
			<?php if($products[$j]['promotion']) { ?><span class="lprom"><b><?php echo $text_special; ?> </b> <?php echo $products[$j]['promotion']; ?></span><?php } ?>
		  </div>
		</a>
		</li>
        <?php } ?>
        <?php } ?>
		</ul></li>
      <?php } ?>
	</ul>
	<script type="text/javascript"><!--
	$('.sblist li').each(function(i, element) {
	var lid = $(element).attr("id");
	$(document).ready(function() {
		$('#' + lid).hoverIntent(makeTall,makeShort);
	});
	function makeTall(){
	  $('#' + lid + " .sblist_info").stop(true, false).animate({
		top: "-120"
	  }, 150 );
	}
	function makeShort(){
	  $('#' + lid + " .sblist_info").stop(true, false).animate({
		top: "0"
	  }, 150 );
	}
	});
	//--></script>
  <?php if($special_href) { ?><a class="sb_xemthem" href="<?php echo $special_href; ?>">XEM THÊM</a><?php } ?>
  </div>
</div>
<?php } ?>