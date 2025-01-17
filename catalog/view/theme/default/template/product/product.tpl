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
  <div class="middle">
    <div style="width: 100%;">
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="width: 283px; vertical-align: top;" align="center"><div class="image"><a onclick="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="fancybox"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" id="image" style="margin-bottom: 3px;" /></a></div>
		  </td>
          <td style="padding-left: 15px; vertical-align: top;">
		    <table width="100%">
              <?php if ($display_price) { ?>
              <tr>
                <td style="font-size:16px;"><b><?php echo $text_price; ?></b></td>
                <td style="font-size:16px;"><?php if (!$special) { ?>
                  <span style="color: #900; font-weight: bold;"><?php echo $price; ?></span>
                  <?php } else { ?>
                  <span style="color: #900; font-weight: bold;text-decoration: line-through;"><?php echo $price; ?></span> <span style="color: #F00;font-weight: bold;"><?php echo $special; ?></span>
                  <?php } ?></td>
              </tr>
			  <?php } ?>
              <tr>
                <td><b><?php echo $text_availability; ?></b></td>
                <td><span style="color:#00722D;"><?php echo $stock; ?></span></td>
              </tr>
              <tr>
                <td><b><?php echo $text_model; ?></b></td>
                <td><?php echo $model; ?></td>
              </tr>
              <?php if ($manufacturer) { ?>
              <tr>
                <td><b><?php echo $text_manufacturer; ?></b></td>
                <td><a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></td>
              </tr>
              <?php } ?>
			  <?php if ($warranty) { ?>
              <tr>
                <td><b>Bảo hành:</b></td>
                <td>
					<span style="color:#00f;"><?php echo $warranty; ?> tháng</span>
				</td>
              </tr>
			  <?php } ?>
			  <?php if ($promotion) { ?>
              <tr>
                <td><b>Khuyến mãi:</b></td>
                <td>
					<span style="color:#f00;"><?php echo $promotion; ?></span>
				</td>
              </tr>
			  <?php } ?>
              <tr>
                <td><b><?php echo $text_average; ?></b></td>
                <td>
                  <img src="catalog/view/theme/default/image/stars_<?php echo $average . '.png'; ?>" alt="<?php echo $text_stars; ?>" style="margin-top: 2px;" />
                  </td>
              </tr>
            </table>
            <br />
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="product">
			  <?php if ($display_price) { ?>
              <?php if ($options) { ?>
              <b><?php echo $text_options; ?></b><br />
              <div class="content4">
                <table style="width: 100%;">
                  <?php foreach ($options as $option) { ?>
                  <tr>
                    <td><?php echo $option['name']; ?>:<br />
                      <select name="option[<?php echo $option['option_id']; ?>]">
                        <?php foreach ($option['option_value'] as $option_value) { ?>
                        <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
                        <?php if ($option_value['price']) { ?>
                        <?php echo $option_value['prefix']; ?><?php echo $option_value['price']; ?>
                        <?php } ?>
                        </option>
                        <?php } ?>
                      </select></td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <?php } ?>
			  <?php } ?>
              <?php if ($display_price) { ?>
              <?php if ($discounts) { ?>
              <b><?php echo $text_discount; ?></b><br />
              <div class="content3">
                <table style="width: 100%;">
                  <tr>
                    <td style="text-align: right;"><b><?php echo $text_order_quantity; ?></b></td>
                    <td style="text-align: right;"><b><?php echo $text_price_per_item; ?></b></td>
                  </tr>
                  <?php foreach ($discounts as $discount) { ?>
                  <tr>
                    <td style="text-align: right;"><?php echo $discount['quantity']; ?></td>
                    <td style="text-align: right;"><?php echo $discount['price']; ?></td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <?php } ?>
              <?php } ?>
              <div class="content2" style="padding:5px;"><?php echo $text_qty; ?>
                <input type="text" name="quantity" size="3" value="1" />
                <a onclick="$('#product').submit();" id="add_to_cart" class="button"><span><?php echo $button_add_to_cart; ?></span></a>
			  </div>
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            </form>
			</td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div id="content">
	<div class="tab_product">
    <div class="tabs">
		<?php if($description) { ?><a tab="#tab_description"><?php echo $tab_description; ?></a><?php } ?>
		<?php if($technical_description) { ?><a tab="#tab_technical_description"><?php echo $tab_technical_description; ?></a><?php } ?>
		<a tab="#tab_related"><?php echo $tab_related; ?></a>
		<a tab="#tab_image"><?php echo $tab_image; ?></a>
		<a tab="#tab_review"><?php echo $tab_review; ?></a>
	</div>
	</div>
    <?php if($description) { ?><div id="tab_description" class="tab_page"><?php echo $description; ?></div><?php } ?>
	<?php if($technical_description) { ?><div id="tab_technical_description" class="tab_page"><?php echo $technical_description; ?></div><?php } ?>
    <div id="tab_related" class="tab_page">
      <?php if ($products) { ?>
		<ul class="list">
		  <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
			<li class="lli<?php if(($i+4) >= sizeof($products)) { echo ' lbottom'; } elseif($i == 0) { echo ' ltop'; } ?><?php if ($display_price) {echo " ldisplayprice";} ?>">
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
		<div class="buttons">
		  <table>
			<tbody><tr>
			  <td align="right"><a href="<?php echo $xemtatca; ?>" class="button"><span><?php echo $text_xemtatca; ?></span></a></td>
			</tr>
		  </tbody></table>
		</div>
      <?php } else { ?>
		<div class="content2" style="margin-top:10px;"><?php echo $text_no_related; ?></div>
      <?php } ?>
	</div>
    <div id="tab_review" class="tab_page">
      <div id="review"></div>
      <div class="heading" id="review_title"><?php echo $text_write; ?></div>
      <div class="content2"><b><?php echo $entry_name; ?></b><br />
        <input type="text" name="name" value="" />
        <br />
        <br />
        <b><?php echo $entry_review; ?></b>
        <textarea name="text" style="width: 99%;" rows="8"></textarea>
        <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
        <br />
        <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
        <input type="radio" name="rating" value="1" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="2" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="3" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="4" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="5" style="margin: 0;" />
        &nbsp; <span><?php echo $entry_good; ?></span><br />
        <br />
        <b><?php echo $entry_captcha; ?></b><br />
        <input type="text" name="captcha" value="" />
        <br />
        <img src="index.php?route=product/product/captcha" id="captcha" /></div>
      <div class="buttons">
        <table>
          <tr>
            <td align="right"><a onclick="review();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </div>
    <div id="tab_image" class="tab_page">
		<?php if ($images) { ?> 
			<?php foreach ($images as $image) { ?>
			<a onclick="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="fancybox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
			<?php } ?>
		<?php } else { ?>
			<div class="content2"><?php echo $text_no_images; ?></div>
		<?php } ?>
    </div>
	<?php if($tags) { ?>
		<?php for ($i=0; $i < sizeof($tags); $i++) { ?>
			<?php if($i==0 && $tags[$i]['keyword']) { echo "<b>Tags:</b>"; } elseif($i!=0) { echo ", "; } ?>
			<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['keyword']; ?></a>
		<?php } ?>
	<?php } ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: false,
		escKey: false,
		opacity: 0.5
	});
});
//--></script>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

function review() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#review_button').attr('disabled', 'disabled');
			$('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#review_button').attr('disabled', '');
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review_title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review_title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?> 