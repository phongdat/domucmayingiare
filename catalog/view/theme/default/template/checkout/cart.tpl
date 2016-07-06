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
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart">
      <table class="cart">
        <tr>
          <th align="center"><?php echo $column_remove; ?></th>
          <th align="center"><?php echo $column_image; ?></th>
          <th align="left"><?php echo $column_name; ?></th>
          <th align="left"><?php echo $column_model; ?></th>
          <th align="right"><?php echo $column_quantity; ?></th>
		  <?php if($display_price) { ?>
          <th align="right"><?php echo $column_price; ?></th>
          <th align="right"><?php echo $column_total; ?></th>
		  <?php } ?>
        </tr>
        <?php $class = 'odd'; ?>
        <?php foreach ($products as $product) { ?>
        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
        <tr class="<?php echo $class; ?>">
          <td align="center"><input type="checkbox" name="remove[<?php echo $product['key']; ?>]" /></td>
          <td align="center"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
          <td align="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php if (!$product['stock']) { ?>
            <span style="color: #FF0000; font-weight: bold;">***</span>
            <?php } ?>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
            </div></td>
          <td align="left"><?php echo $product['model']; ?></td>
          <td align="right"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" /></td>
		  <?php if($display_price) { ?>
          <td align="right"><?php echo $product['price']; ?></td>
          <td align="right"><?php echo $product['total']; ?></td>
		  <?php } ?>
        </tr>
        <?php } ?>
		<?php if($display_price) { ?>
        <tr>
          <td colspan="7" align="right">
		  <div style="width: 100%; display: inline-block;">
			<table style="float: right; display: inline-block;">
			  <?php foreach ($totals as $total) { ?>
			  <tr>
				<td align="right"><?php echo $total['title']; ?></td>
				<td align="right"><?php echo $total['text']; ?></td>
			  </tr>
			  <?php } ?>
			</table>
			<br />
		  </div>
		  </td>
        </tr>
		<?php } ?>
      </table>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="$('#cart').submit();" class="button"><span><?php echo $button_update; ?></span></a></td>
            <td align="center"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_shopping; ?></span></a></td>
            <td align="right"><a onclick="<?php echo $checkout; ?>" class="colorbox button"><span><?php echo $button_order; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#cart-online').remove();
//--></script>
<?php echo $footer; ?> 