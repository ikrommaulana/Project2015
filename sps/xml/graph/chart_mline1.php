<?php
	include_once('../../etc/db_js.php');
	//include_once('../etc/session.php');
	$dat=$_REQUEST['dat'];
	list($xbuf,$xval,$gynote,$gtnote,$gxnote,$gcnote,$grnote,$gdec) = split('[|]', $dat);
	
?>
<chart>
	<license><?php echo $LICENSE_MAANI;?></license><!-- sps.net.my -->
	<chart_type>line</chart_type>
	
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
	
	<chart_grid_h alpha='30' thickness='1' type='dotted' />
	<chart_grid_v alpha='30' thickness='1' type='dotted' />
	<chart_guide horizontal='true' vertical='true' thickness='1' color='000000' alpha='50' type='dashed' radius='3' line_color='000000' line_alpha='75' line_thickness='1' text_color='000000' text_h_alpha='90' />	
	<chart_note type='bullet' size='11' color='333333' alpha='90' x='0' y='-125' offset_y='5' background_color_1='000000' background_alpha='65' shadow='low' />
	<chart_pref line_thickness='2' point_shape='none' fill_shape='false' />
	<chart_rect x='60' y='40' width='300' height='150' positive_color='FFFFFF' positive_alpha='30' bevel='bg' />


	<draw>
		<rect shadow='bg' layer='background' x='0' y='0' width='480' height='300' fill_color='4c5577' fill_alpha='100' line_alpha='0' line_thickness='0' />
      	<text shadow='high' color='FFFFFF' alpha='90' rotation='-90' size='12' x='0' y='270' width='300' height='200' h_align='center'><?php echo $gynote;?></text>
		<text shadow='high' color='FFFFFF' alpha='90' rotation='0' size='8' x='50' y='240'   width='300' height='200' h_align='center'><?php echo $gxnote;?></text>
		<text layer='background' shadow='high' color='FFFFFF' alpha='90' rotation='0' size='30'  y='80'  h_align='center'><?php echo $gcnote;?></text>
		<image transition='blink' delay='2' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='380' y='5' width='15' height='15' alpha='80' />
   </draw>
  <filter>
		<shadow id='high' distance='5' angle='45' color='0' alpha='50' blurX='10' blurY='10' />
		<shadow id='bg' inner='true' quality='2' distance='25' angle='-45' color='000000' alpha='35' blurX='100' blurY='100' />
	</filter>
   <link>
		<area x='380' y='10' width='15' height='15' target='toggle_fullscreen' />
	</link>
   <!-- make category labels red, diagonal_down, size 10, and skip every other label -->   
   <axis_category skip='0' size='10' color='FFFFFF' alpha='75' orientation='diagonal_down' />
   
   <axis_value shadow='low' size='8' color='FFFFFF' alpha='75' /><!--y value -->
   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='drop' delay='1' duration='1' order='series' />
   
	
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
	<context_menu save_as_bmp='true' save_as_jpeg='true' save_as_png='true' /> 
    
	<legend size='8' />

</chart>