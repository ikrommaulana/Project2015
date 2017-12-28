<?php
	include_once('../../etc/db_js.php');
	$dat=rawurldecode($_REQUEST['dat']);
	list($xbuf,$xval,$lelfnote,$topnote,$bottomnote,$centernote,$rightnote,$gdec,$ispercent) = split('[|]', $dat);
?>

<chart>
		<license><?php echo $LICENSE_MAANI;?></license><!-- sps.net.my -->
	<chart_data>
		<row>
			<null/>
<?php
		$xxbuf=explode(",",$xbuf);
		for($i=0;$i<count($xxbuf);$i++){
?>
			<string><?php echo $xxbuf[$i];?></string>
<?php } ?>
		</row>
		
		<row>
			<string><?php echo "TITLE";?></string>
<?php
		$yybuf=explode(",",$xbuf);
		$xxbuf=explode(",",$xval);
		for($i=0;$i<count($xxbuf);$i++){
?>
<!-- 
			<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='1' line_alpha='65' label='<?php echo $yybuf[$i];?>:<?php echo $xxbuf[$i];?>'><?php echo $xxbuf[$i];?></number>
 -->
<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='1' line_alpha='50' ><?php echo $xxbuf[$i];?></number>
<?php } ?>
		</row>

	</chart_data>
	
   	<chart_grid_h alpha='0' color='FFFFFF' thickness='1' type='solid' />
	<!-- <chart_label shadow='low' color='ffffff' alpha='95' size='10' position='inside' as_percentage='true' /> -->
	<chart_label color='ffffff' alpha='95' size='12' position='inside' as_percentage='<?php echo $ispercent;?>' />
	<chart_pref select='true' />
	<!-- <chart_rect bevel='bg' positive_color='4c5577' positive_alpha='75' /> -->
	
	<chart_type>pie</chart_type>

	<draw>
		<rect shadow='bg' layer='background' x='0' y='0' width='480' height='300' fill_color='4c5577' fill_alpha='100' line_alpha='0' line_thickness='0' />
        <text color='FFFFFF' alpha='90' rotation='-90' size='16' x='0' y='270' width='300' height='200' h_align='center'><?php echo $leftnote;?></text>
		<text layer='background' color='FFFFFF' alpha='90' size='12' x='0' y='0' width='400' height='150'><?php echo $topnote;?></text>
		<text transition='zoom' delay='0.5' duration='1' color='ffffff' alpha='90' rotation='0'  x='180' y='130'size='20' width='300' height='200' h_align='center' v_align='middle'><?php echo $bottomnote;?></text>
		<text transition='zoom' delay='0.5' duration='1' color='ffffff' alpha='90' rotation='0'  x='80' y='30'size='20' width='300' height='200' h_align='center' v_align='middle'><?php echo $centernote;?></text>

		<image transition='blink' delay='0.5' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='370' y='4' width='25' height='25' alpha='80' />
   	</draw>
   	<link>
		<area x='370' y='4' width='25' height='25' target='toggle_fullscreen' />
	</link>
	<filter>
		<shadow id='high' distance='5' angle='45' color='0' alpha='50' blurX='10' blurY='10' />
		<shadow id='bg' inner='true' quality='2' distance='25' angle='-45' color='000000' alpha='35' blurX='100' blurY='100' />
	</filter>

	<legend fill_color='0' fill_alpha='5' x='2' y='20' line_alpha='0' line_thickness='0' bullet='circle' size='10' color='FFFFFF' alpha='75' margin='2' />

   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='scale' delay='0' duration='1' order='series' />
   
	<series_explode>
		<number>10</number>
		<number>0</number>
		<number>0</number>
		<number>0</number>
		<number>0</number>
		<number>0</number>
	</series_explode>

</chart>