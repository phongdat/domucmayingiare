<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/news.css" />
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
	<?php if( $date_added) { ?><div class="news_date_add">Ngày đăng: <?php echo $date_added; ?></div><?php } ?>
    <div class="center">
      <h1 class="news"><?php echo $heading_title; ?></h1>
    </div>
  </div>
 <div class="middle">
 <div style="min-height:250px;">
  <?php echo $description; ?>
  </div>

<?php if ($tinmois) { ?>
<div class="heading">Tin mới</div>
<div id="news"><ul>
	<?php foreach ($tinmois as $tinmoi) { ?>
	<li><a  href="<?php echo $tinmoi['href']; ?>"><?php echo $tinmoi['name']; ?></a> <span style="color:#777;"><?php echo $tinmoi['date_added']; ?></span></li>
    <?php } ?> 
</ul></div>
<br/>
<?php } ?> 
<?php if ($tinkhacs) { ?>
<div class="heading">Các tin khác</div>
<div id="news"><ul>
	<?php foreach ($tinkhacs as $tinkhac) { ?>
	<li><a  href="<?php echo $tinkhac['href']; ?>"><?php echo $tinkhac['name']; ?></a> <span style="color:#777;"><?php echo $tinkhac['date_added']; ?></span></li>
    <?php } ?> 
</ul></div>
<?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 