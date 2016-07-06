$(document).ready(function () {
	$('#add_to_cart').replaceWith('<a onclick="" id="add_to_cart" class="button">' + $('#add_to_cart').html() + '</a>');

	$('#add_to_cart').click(function () {
		$.ajax({
			type: 'post',
			url: 'index.php?route=common/header/callback',
			dataType: 'html',
			data: $('#product :input'),
			success: function (html) {
				$('#cart-online .middle').html(html);
			},	
			complete: function () {
				var image = $('#image').offset();
				var cart  = $('#cart-online').offset();
	
				$('#image').before('<img src="' + $('#image').attr('src') + '" id="temp" style="position: absolute; top: ' + image.top + 'px; left: ' + image.left + 'px;" />');
	
				params = {
					top : cart.top + 'px',
					left : cart.left + 'px',
					opacity : 0.0,
					width : $('#cart-online').width(),  
					heigth : $('#cart-online').height()
				};		
	
				$('#temp').animate(params, 'slow', false, function () {
					$('#temp').remove();
				});		
			}			
		});			
	});		
});