<?php
	include_once('../../etc/db_js.php');
	//include_once('../etc/session.php');
	$dat=rawurldecode($_REQUEST['dat']);
	list($xbuf,$xval,$gynote,$gtnote,$gxnote,$gcnote,$grnote,$show_decimal,$show_value) = split('[|]', $dat);
	
?>
<chart>
	<license><?php echo $LICENSE_MAANI;?></license><!-- sps.net.my -->
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
			echo "<number line_color='FFFFFF' line_thickness='1' line_alpha='65' tooltip='$xxxval[$i]' >$xxxval[$i]</number>\n";
		} 
		echo "</row>";
} 
?>

	</chart_data>
    
<!--
	<chart_guide horizontal='true' vertical='true' thickness='1' color='000000' alpha='35' type='dashed' radius='3' fill_alpha='75' line_alpha='0' background_color='FF4400' text_h_alpha='90' text_v_alpha='90' prefix_h='$' suffix_h=' Mil' suffix_v=' May' />	
--> 
	<chart_guide horizontal='true' vertical='true' thickness='1' color='000000' alpha='35' type='dashed' radius='3' fill_alpha='75' line_alpha='0' background_color='FF4400' text_h_alpha='90' text_v_alpha='90'/>	
    <chart_rect  x='60' y='50' width='300' height='120'  positive_color='FFFFFF' positive_alpha='0' />
<!--
	<chart_rect  x='60' y='50' width='300' height='120' />
-->
	<filter>
		<shadow id='high' distance='5' angle='45' color='0' alpha='50' blurX='10' blurY='10' />
		<shadow id='bg' inner='true' quality='2' distance='25' angle='-45' color='000000' alpha='35' blurX='100' blurY='100' />
	</filter>
	<draw>
		<rect shadow='bg' layer='background' x='0' y='0' width='480' height='300' fill_color='4c5577' fill_alpha='100' line_alpha='0' line_thickness='0' />
		<text layer='background' shadow='high' color='FFFFFF' alpha='90' size='15' x='0' y='0' width='400' height='150'><?php echo $gtnote;?></text>
      	<text color='FFFFFF' alpha='50' rotation='-90' size='15' x='1' y='270' width='300' height='200' h_align='center'><?php echo $gynote;?></text>
		<text color='000000' alpha='50' rotation='0' size='8' x='50' y='240' width='300' height='200' h_align='center'><?php echo $gxnote;?></text>
		<text color='666666' alpha='50' rotation='0' size='40' y='80' width='400' height='400' h_align='center' ><?php echo $gcnote;?></text>
		<image transition='dissolve' delay='2' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='380' y='5' width='15' height='15' alpha='80' />
   </draw>
  
   <link>
		<area x='380' y='5' width='15' height='15' target='toggle_fullscreen' />
	</link>
   <!-- make category labels red, diagonal_down, size 10, and skip every other label -->   
   <axis_category skip='0' size='8' color='FFFFFF' alpha='90' orientation='diagonal_down' /><!--x value -->
   <!-- <axis_value shadow='low' size='7' color='FFFFFF' alpha='75' max='120' steps='4' show_min='false' />-->
   <axis_value shadow='low' size='8' color='FFFFFF' alpha='75' /><!--y value -->
   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='slide_up' delay='1' duration='1' order='series' />
   
   <legend  bullet='circle' size='8' alpha='75' />

   <chart_label prefix='' 
                suffix='' 
                decimals='<?php echo $show_decimal;?>' 
                decimal_char='.'
                separator=''
                position='outside'
                hide_zero='false' 
                font='arial' 
                bold='false' 
                size='7' 
                color='FFFFFF' 
                alpha='90'
                />

</chart>