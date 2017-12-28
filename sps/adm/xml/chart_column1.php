<?php
	include_once('../../etc/db.php');
	//include_once('../etc/session.php');
	$dat=$_REQUEST['dat'];
	list($xbuf,$xval,$gynote,$gtnote,$gxnote,$gcnote,$grnote,$gdec) = split('[|]', $dat);
	
?>
<chart>
	<license>GT46RNFPXSY93T5D8RJ4.B-4ZRMDVL</license><!-- sps.net.my -->
	<chart_type>column</chart_type>
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
		


<?php

$xxval=explode(";",$xval);
for($j=0;$j<count($xxval);$j++){
	$xxxval=explode(",",$xxval[$j]);
		
		echo "<row>\n";
		echo "<string>$xxxval[0]</string>\n";
		for($i=1;$i<count($xxxval);$i++){
			echo "<number bevel='gray' shadow='high'>$xxxval[$i]</number>\n";
		} 
		echo "</row>";
} 
?>

	</chart_data>
	<chart_rect  x='75' y='50' width='300' height='120' />
<!-- 
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
 -->

	<draw>
      	<text shadow='high' color='666666' alpha='50' rotation='-90' size='20' x='5' y='270' width='300' height='200' h_align='center'><?php echo $gynote;?></text>
		<text shadow='high' color='000000' alpha='70' rotation='0' size='8' x='50' y='240' width='300' height='200' h_align='center'><?php echo $gxnote;?></text>
		<!-- 
		<text shadow='low' color='666666' alpha='50' rotation='-90' size='16' x='7' y='230' width='300' height='50' h_align='center' v_align='middle'>(Pelajar)</text>
		 -->
		 <text shadow='shadow1' color='666666' alpha='40' rotation='0' size='70' x='120' y='60' width='400' height='400' h_align='left'><?php echo $gcnote;?></text>
		 <image transition='dissolve' delay='2' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='380' y='5' width='15' height='15' alpha='80' />
   </draw>
  
   <link>
		<area x='380' y='5' width='15' height='15' target='toggle_fullscreen' />
	</link>
   <!-- make category labels red, diagonal_down, size 10, and skip every other label -->   
   <axis_category skip='0' size='4' color='FF0000' alpha='90' orientation='diagonal_down' />
   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='slide_up' delay='1' duration='1' order='series' />
   

   <chart_label prefix='' 
                suffix='' 
                decimals='<?php echo $gdec;?>' 
                decimal_char='.'
                separator=''
                position='outside'
                hide_zero='false' 
                font='arial' 
                bold='true' 
                size='6' 
                color='FF0000' 
                alpha='90'
                />
</chart>