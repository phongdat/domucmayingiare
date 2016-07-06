<div class="box">
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div id="information" class="middle">
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['name']; ?></a></li>
      <?php } ?>
    </ul>
  </div>

</div>