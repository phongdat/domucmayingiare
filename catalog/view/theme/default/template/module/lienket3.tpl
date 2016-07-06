<?php if($lienket_heading_title == "yes"){ ?>
<div class="box">
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div class="middle lienket">
<?php echo $code; ?>
  </div>
</div>
<?php } else { ?>
<div class="middle lienket">
<?php echo $code; ?>
</div>
<?php } ?>
