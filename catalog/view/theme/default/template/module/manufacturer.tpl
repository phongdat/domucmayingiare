<div class="box">
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div class="middle" id="information">
	<ul>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
      <li class="select"><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
      <?php } else { ?>
      <li><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
	</ul>
  </div>
</div>
