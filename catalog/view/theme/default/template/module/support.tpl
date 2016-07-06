<div class="box">
  <div class="top"><h3><?php echo $heading_title; ?></h3></div>
  <div class="middle hotroonline" style="overflow:hidden;">
  <?php if($code) { ?>
  <div style="padding:5px;"><?php echo $code; ?></div>
  <?php } ?>
<?php $j = 0; ?>
  <?php foreach ($csupports as $csupport) { ?>
  <?php if($csupport['supports']) { ?>
  <div class="support"><?php echo $csupport['name']; ?></div>
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="hotro">
		<tbody>
		<?php $i = 0; ?>
		<?php $j++; ?>
		<?php foreach ($csupport['supports'] as $support) { ?>
			<?php $i++; ?>
			<tr>
				<td align="left" width="12" class="tdtop">
				<a href="ymsgr:sendIM?<?php echo $support['yahoo_id']; ?>"><img align="left"  src="http://opi.yahoo.com/online?u=<?php echo $support['yahoo_id']; ?>&m=g&t=0" /></a>
				</td>
				<td align="center" class="tdtop"><?php echo $support['name']; ?></td>
			</tr>
			<tr>
				<td align="left" width="12" class="tdbottom" <?php if(sizeof($csupport['supports']) != 1 && sizeof($csupport['supports']) != $i) { echo 'style="border-bottom:1px solid #D8D8DA;"'; } ?>>
				<?php if($support['skype_id']) { ?>
				<a href="skype:<?php echo $support['skype_id']; ?>?chat"><img align="right" src="catalog/view/theme/default/image/skype.png" alt="Chat Skype" border="0"></a>
				<?php } ?>
				</td>
				<td align="right" class="tdbottom" <?php if(sizeof($csupport['supports']) != 1 && sizeof($csupport['supports']) != $i) { echo 'style="border-bottom:1px solid #D8D8DA;"'; } ?>>
				<?php if($support['telephone']) { ?>
				Hotline: <span><?php echo $support['telephone']; ?></span>
				<?php } ?>
				</td> 
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<?php } ?>
  </div>
</div>
