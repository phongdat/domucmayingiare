<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title id="title"><?php echo $title; ?></title>

<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>">
<?php } ?>
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="image/<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/sidebar.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/menu.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/cart.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script src="catalog/view/javascript/jquery.bxSlider.js" type="text/javascript"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tab.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/unitpngfix/unitpngfix.js"></script>
<![endif]-->
<?php foreach ($styles as $style) { ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/<?php echo $style; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="view/javascript/jquery/<?php echo $script; ?>"></script>
<?php } ?>
<?php echo $google_analytics; ?>

</head>
<body>
<div id="container">
<div id="header">
  <div class="header">
	<div class="div0">
		<div class="search">
			<?php if ($keyword) { ?>
			<input type="text" value="<?php echo $keyword; ?>" id="filter_keyword" />
			<?php } else { ?>
			<input type="text" value="<?php echo $text_keyword; ?>" id="filter_keyword" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
			<?php } ?>
			<a onclick="moduleSearch();" class="button_search"></a>
		</div>
		<div id="cart-online"></div>
		<div id="showuser"></div>
	</div>	
	<div class="div1">
		<div class="logo">
		<a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a>
		</div>
	</div>
	<div style="clear:both;"></div>
  </div>
</div>
<div id="menu">
	<ul class="ultop">
		<li class="litop home<?php if($home_select == 1) { echo ' select active';} ?>"><a class="atop" href="<?php echo $base; ?>"><span class="icon-home"></span></a></li>
		<?php foreach ($categories as $category) { ?>
		<li class="litop<?php if($category['children']) { echo ' drop'; } ?><?php foreach ($category['children'] as $children) {if($children['category_id'] == $category_id){ echo ' select active'; }} if($category['category_id'] == $category_id){ echo ' select active'; } ?>"><a class="atop" href="<?php echo $category['href']; ?>"><span class="spantop"><?php echo $category['name']; ?></span></a>
		<?php if($category['children']){ ?>
		  <div class="dropdown_1column">
			<div class="col_1">
			<ul class="simple">
			<?php $i = 0; ?>
			<?php foreach ($category['children'] as $children) { ?>
				<?php $i++; ?>
				<li class="<?php if(sizeof($category['children']) == $i) {echo 'slbottom';} ?> <?php if($children['category_id'] == $category_id){ echo 'select active'; } ?>"><a href="<?php echo $children['href']; ?>"><?php echo $children['name']; ?></a></li>
			<?php } ?>
			</ul>
			</div>
		  </div>
		<?php } ?>
		</li>
		<?php } ?>
		<li class="litop right<?php if($contact_select == 1) { echo ' select active';} ?>"><a class="atop" href="<?php echo $contact; ?>"><span class="spantop"><?php echo $text_contact; ?></span></a></li>
		<li class="litop right drop<?php if($news_select == 1) { echo ' select active';} ?>"><a class="atop" href="<?php echo $news_href; ?>"><span class="spantop"><?php echo $text_news; ?></span><span class="rightspan"></span></a>
			<div class="dropdown_1column mnews">
				<div class="col_1">
					<ul class="simple">
					<?php $i = 0; ?>
						<?php foreach ($cnews as $cnew) { ?>
							<?php $i++; ?>
							<li <?php if(sizeof($cnews) == $i) { echo ' class="slbottom"'; } ?>><a href="<?php echo $cnew['href']; ?>"><?php echo $cnew['name']; ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</li>
	</ul>
<script language="javascript"> 
$('#menu li.litop').mouseenter(function() {
	$('#menu li.litop').removeClass('select');
});
$('#menu li.litop').mouseleave(function() {
	$('#menu li.litop.active').addClass('select');
});
$('#menu .simple li').mouseenter(function() {
	$('#menu .simple li').removeClass('select');
});
$('#menu .simple li').mouseleave(function() {
	$('#menu .simple li.active').addClass('select');
});
</script>
</div>
<div style="clear:both;"></div>
<div id="wrapper">
	<div id="breadcrumb">
		<ul>
		  <?php if($home_select == 1) { ?>
			<li class="bc_home">
				<a class="liabrd" href="" title="">Trang chủ</a>
			</li>
			<li class="bc_next">
				<h1><?php echo $title; ?></h1>
			</li>
		  <?php } else { ?>
			<?php $i = 0; ?>
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php $i++; ?>
			<li <?php if($breadcrumb['separator']) { echo 'class="bc_next"'; } else { echo 'class="bc_home"';} ?>>
				<?php if(sizeof($breadcrumbs) == $i) { ?>
					<span><?php if($breadcrumb['separator']) { echo $breadcrumb['text']; } ?></span>
				<?php } else { ?>
					<a class="liabrd" href="<?php echo $breadcrumb['href']; ?>"><?php if($breadcrumb['separator']) { echo $breadcrumb['text']; } else { echo 'Trang chủ';} ?></a>
				<?php } ?>
			</li>
			<?php } ?>
		  <?php } ?>
		</ul>
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style " id="addthis">
		<a class="addthis_counter addthis_pill_style"></a>
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e2bcabf052e93c3"></script>
		<!-- AddThis Button END -->
	</div>

<script language="javascript">
$('#cart-online').load('index.php?route=common/header/cart');
$('#showuser').load('index.php?route=common/header/account');
</script>
<script type="text/javascript"><!--
$('.search input').keydown(function(e) {
	if (e.keyCode == 13) {
		moduleSearch();
	}
});
function moduleSearch() {
	pathArray = location.pathname.split( '/' );

	url = 'search/';
		
	var filter_keyword = $('#filter_keyword').attr('value')

	if (filter_keyword) {
		url += 'keyword/' + encodeURIComponent(filter_keyword).replace(/%20/gi, "-") + '/';
	}
	
	location = url;
}
//--></script>