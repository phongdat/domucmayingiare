<div class="arrow">
  <span class="t-cart"><font><?php echo $text_cart; ?>(<?php echo $sanpham; ?>)</font></span>
  <div class="border-cart"><span></span></div>
</div>
<div class="cart-content" style="display:none;">
  <div class="content_middle">
	<?php if ($products) { ?>
	<table cellpadding="2" cellspacing="0" class="table_cart">
	  <?php foreach ($products as $product) { ?>
	  <tr>
		<td align="left" class="td_cart_image">
		<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" /></a>
		</td>
		<td align="left" class="td_cart_name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
		  <div>
			<?php foreach ($product['option'] as $option) { ?>
			- <small style="color: #999;"><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
			<?php } ?>
		  </div>
		</td>
		<td align="right" class="td_cart_quantity">
			<span>x</span><span class="span_quantity"><b><?php echo $product['quantity']; ?></b></span>
		</td>
		<?php if ($display_price) { ?>
		<td align="right">
			<?php echo $product['price']; ?>
		</td>
		<?php } ?>
		<td align="right" style="width:7px;"><span title="<?php echo $text_thongbao; ?>" class="cart_remove" id="remove_<?php echo $product['key']; ?>">&nbsp;</span></td>
	  </tr>
	  <?php } ?>
	</table>
	<br />
	<?php if ($display_price) { ?>
	<p class="cart_subtotal">
		<?php echo $text_subtotal; ?>&nbsp;<span style="color:#F00;"><?php echo $subtotal; ?></span>
	</p>
	<?php } ?>
	<?php } else { ?>
		<p class="cart_empty"><?php echo $text_empty; ?></p>
	<?php } ?>
  </div>
<?php if ($products) { ?>
	<div class="cart_button">
		<a onclick="location='<?php echo $cart; ?>'"  class="button"><span>Xem giỏ hàng</span></a><a onclick="<?php echo $cart; ?>order/" class="cb_cart button"><span><?php echo $text_cart_chitiet; ?> nhanh</span></a>
	</div>
<?php } ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.cb_cart').colorbox({
		overlayClose: false,
		escKey: false,
		opacity: 0.5
	});
});
//--></script>
<script language="javascript"> 
$('#cart-online .arrow').click(function(event) {
	if ($('#cart-online').hasClass('active')) {
		$('#cart-online .cart-content').slideUp(50);
		$('#cart-online').removeClass('active');
	} else {
		$('#cart-online .cart-content').slideDown(50);
		$('#cart-online').addClass('active');
	}
	return false;
});
$('#cart-online .content_middle').click(function(event) {
	event.stopPropagation();
});
$(document).click(function() {
	$('#cart-online .cart-content').slideUp(50);
	$('#cart-online').removeClass('active');
});
</script>
<script type="text/javascript"><!--
function getUrlParam(name) {
  var name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.href);
  if (results == null)
	return "";
  else
	return results[1];
}
$(document).ready(function () {
	$('#add_to_cart').replaceWith('<a onclick="" id="add_to_cart" class="button">' + $('#add_to_cart').html() + '</a>');

	$('#add_to_cart').click(function () {
		$.ajax({
			type: 'post',
			url: 'index.php?route=common/header/cart',
			dataType: 'html',
			data: $('#product :input'),
			success: function (html) {
				$('#cart-online').html(html);
			},	
			complete: function () {
				var image = $('#image').offset();
				var cart  = $('#cart-online').offset();
	
				$('#image').before('<img src="' + $('#image').attr('src') + '" id="temp" style="position: absolute; top: ' + image.top + 'px; left: ' + image.left + 'px;" />');
	
				params = {
					top : cart.top + 'px',
					left : cart.left + 'px',
					opacity : 0.5,
					width : $('#cart-online').width(),  
					heigth : $('#cart-online').height()
				};		
				$('html, body').animate({ scrollTop: $('#cart-online').offset().top -0}, 'slow');
				$('#temp').animate(params, 'slow', false, function () {
					$('#temp').remove();
				});	
				$('#cart-online .cart-content').slideDown(50);
				$('#cart-online').addClass('active');				
			}			
		});			
	});
	$('.cart_remove').live('dblclick', function () {
		$('#cart-online').addClass('active');
		$(this).removeClass('cart_remove').addClass('cart_remove_loading');
		$.ajax({
			type: 'post',
			url: 'index.php?route=common/header/cart',
			dataType: 'html',
			data: 'remove=' + this.id,
			success: function (html) {
				$('#cart-online').html(html);
				if (getUrlParam('route').indexOf('checkout') != -1) {
					window.location.reload();
				}
			}
		});
	});	
});
//--></script>
