<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/listpro.css?ver=1.4" />
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle listpro">
    <div class="sort">
      <div class="div1">
        <select name="sort" onchange="location=this.value">
          <?php foreach ($sorts as $sorts) { ?>
          <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
          <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
      <div class="div2"><?php echo $text_sort; ?></div>
    </div>
      <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
	<ul class="list">
      <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
		<li class="lli<?php if(($i+4) >= sizeof($products)) { echo ' lbottom'; } ?><?php if ($display_price) {echo " ldisplayprice";} ?>">
        <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
		<div class="lpro<?php if($j == $i+3) {echo ' lright';} elseif($j == $i) {echo ' lleft';} ?>">
		  <?php if (isset($products[$j])) { ?>
          <a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>"  alt="<?php echo $products[$j]['name']; ?>" /></a>
          <div class="ltitle"><a href="<?php echo $products[$j]['href']; ?>"><?php echo $products[$j]['name']; ?></a></div>
		  <div class="lmodel"><b><?php echo $text_model; ?></b> <?php echo $products[$j]['model']; ?></div>
		  <?php if ($display_price) { ?>
			  <div class="lprice">
			  <?php if (!$products[$j]['special']) { ?><br/>
			  <b><?php echo $text_price; ?></b> <span class="price"><?php echo $products[$j]['price']; ?></span><br />
			  <?php } else { ?>
			  <span class="lspecial"><?php echo $products[$j]['price']; ?></span><br/>
			  <b><?php echo $text_price; ?></b> <span class="price"><?php echo $products[$j]['special']; ?></span>
			  <?php } ?>
			  </div>
          <?php } ?>
		  <?php } ?>
		</div>
        <?php } ?>
		</li>
      <?php } ?>
	</ul>
      <?php } ?>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 