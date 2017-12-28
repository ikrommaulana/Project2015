<?php
	include_once('../../etc/db.php');
	//include_once('../etc/session.php');
	$dat=$_REQUEST['dat'];
	list($groups,$xval,$leftnote,$topnote,$bottomnote,$centernote,$rightnote,$vdec) = split('[|]', $dat);
?>
<chart>
	<license>GT46RNFPXSY93T5D8RJ4.B-4ZRMDVL</license><!-- sps.net.my -->
	<chart_type>line</chart_type>
	<chart_data>
		<row>
			<null/>
			
<?php
		$grpname=explode(",",$groups);
		for($i=0;$i<count($grpname);$i++){
?>
			<string><?php echo $grpname[$i];?></string>
<?php } ?>

		</row>
 
		<row>
			<string><?php echo $b;?></string>
<?php 
	$xxval=explode(";",$xval);
	for($j=0;$j<count($xxval);$j++){
?>
			<number bevel='blue' shadow='low'><?php echo $xxval[$j];?></number>
<?php } ?>
		</row>

	</chart_data>
	
	<chart_grid_h alpha='10' thickness='1' type='dashed' />
	<chart_label color='ddffff' alpha='90' size='10' position='middle' />
	<chart_note type='arrow' size='13' color='000000' alpha='75' x='-10' y='-30' background_color='FF4400' background_alpha='75' shadow='low' />
	<chart_rect bevel='bg' shadow='high' x='75' y='50' width='300' height='150' positive_color='eeeeff' negative_color='dddddd' positive_alpha='100' negative_alpha='100'  corner_tl='0' corner_tr='0' corner_br='40' corner_bl='40' />

	<filter>
		<shadow id='high' distance='5' angle='45' alpha='35' blurX='10' blurY='10' />
		<shadow id='low' distance='2' angle='45' alpha='35' blurX='5' blurY='5' />
		<bevel id='bg' angle='45' blurX='50' blurY='50' distance='10' highlightAlpha='50' highlightColor='ffffff' shadowAlpha='10' inner='true' />
		<bevel id='blue' angle='-80' blurX='0' blurY='30' distance='20' highlightColor='ffffff' highlightAlpha='50' shadowColor='000088' shadowAlpha='25' inner='true' />
		<bevel id='gray' angle='-80' blurX='0' blurY='30' distance='20' highlightColor='ffffff' highlightAlpha='25' shadowColor='000000' shadowAlpha='20' inner='true' />
	</filter>

	
	<draw>
      	      	<text shadow='high' color='333333' alpha='50' rotation='-90' size='20' x='5' y='270' width='300' height='200' h_align='center'><?php echo $leftnote;?></text>
		<text shadow='high' color='333333' alpha='50' rotation='0' size='20' x='120' y='10' width='400' height='400' h_align='left' v_align='top'><?php echo $topnote;?></text>
		<text shadow='shadow1' color='999999' alpha='40' rotation='0' size='70' x='140' y='70' width='400' height='400' h_align='left'><?php echo $centernote;?></text>
		<image transition='dissolve' delay='3' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='350' y='20' width='15' height='15' alpha='80' />
   </draw>
   <link>
		<area x='350' y='20' width='15' height='15' target='toggle_fullscreen' />
	</link>
	
   <!-- make category labels red, diagonal_down, size 10, and skip every other label -->   
   <axis_category skip='0' size='8' color='FF0000' alpha='75' orientation='diagonal_down' />
   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='scale' delay='1' duration='1' order='series' />
   
   



</chart>