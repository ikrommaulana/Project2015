<?php
	include_once('../../etc/db.php');
	include_once('../../inc/language.php');
	$dat=$_REQUEST['dat'];
	list($sid, $lvl, $year,$maxa,$exam,$topgrade) = split('[|]', $dat);
	if($lvl=="")
		$lvl=0;
	$sql="select * from type where grp='exam' and code='$exam'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $examname=$row['prm'];
	
	$sql="select * from sch where id='$sid'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sname=$row['name'];
		$ssname=$row['sname'];
		$simg=$row['img'];
		$namatahap=$row['clevel'];	
			
		if((strtoupper($namatahap)=="TINGKATAN")&&($lvl>3))
			$show_a1a2=1;
		else
			$show_a1a2=0;
		if($show_a1a2){
			$sql="select g1+g2 as jumsub from examrank where sch_id='$sid' and cls_level='$clslevel' and year=$year order by jumsub desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumlah_sub=$row2[0];
			if($jumlah_sub=="")
				$jumlah_sub=0;
			$topgrade="A";
		}
?>
<chart>
	<license>GT46RNFPXSY93T5D8RJ4.B-4ZRMDVL</license><!-- sps.net.my -->
	<chart_data>
		<row>
			<null/>
<?php for($i=$maxa;$i>=0;$i--){
		if($show_a1a2)
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$lvl' and year=$year and exam='$exam' and (g1+g2)=$i";
		else
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$lvl' and year=$year and exam='$exam' and g1=$i";
    	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$xx=$row2[0];
		if($xx=="0") continue;
?>
			<string><?php echo "$i $topgrade";?></string>
<?php } ?>
		</row>
		
		<row>
			<string><?php echo $b;?></string>
<?php for($i=$maxa;$i>=0;$i--){

		$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$lvl' and year=$year and exam='$exam' and g1=$i";
    	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$xx=$row2[0];
		if($xx=="0") continue;

?>
			<number bevel='data' <?php if($i==$maxa) echo "glow='glow1'";?>><?php echo $xx;?></number>
<?php } ?>

		</row>

	</chart_data>
	
   <chart_grid_h alpha='20' color='FFFFFF' thickness='1' type='solid' />
	<chart_label shadow='low' color='ffffff' alpha='95' size='10' position='inside' as_percentage='true' />
	<chart_pref select='true' />
	<chart_rect bevel='bg' positive_color='4c5577' positive_alpha='75' />
	<chart_type>pie</chart_type>

	<draw>
		<text layer='background' shadow='low' color='ffffff' alpha='5' size='30' x='0' y='0' width='400' height='150' >|||||||||||||||||||||||||||||||||||||||||||||||</text>
		<text layer='background'  shadow='low' color='ffffff' alpha='5' size='30' x='0' y='140' width='400' height='150' v_align='bottom'>|||||||||||||||||||||||||||||||||||||||||||||||</text>
		<text shadow='high' color='333333' alpha='50' rotation='0' size='20' x='150' y='5' width='400' height='400' h_align='left' v_align='top'><?php echo strtoupper($lg_percent);?> <?php echo $topgrade;?></text>
		<text shadow='shadow1' color='DDDDDD' alpha='40' rotation='0' size='70' x='150' y='70' width='400' height='400' h_align='left'><?php echo $year;?></text>
		<image transition='dissolve' delay='3' url='<?php echo $MYOBJ;?>/charts/resources/full_screen/full_screen.swf' x='350' y='20' width='15' height='15' alpha='80' />
   </draw>
   <link>
		<area x='350' y='15' width='20' height='15' target='toggle_fullscreen' />
	</link>
	
	<filter>
		<shadow id='low' distance='2' angle='45' color='0' alpha='50' blurX='5' blurY='5' />
		<bevel id='data' angle='45' blurX='10' blurY='10' distance='3' highlightAlpha='5' highlightColor='ffffff' shadowColor='000000' shadowAlpha='50' type='full' />
		<bevel id='bg' angle='10' blurX='20' blurY='20' distance='10' highlightAlpha='25' highlightColor='ff8888' shadowColor='8888ff' shadowAlpha='25' type='full' />
		<glow id='glow1' color='ff88ff' alpha='75' blurX='30' blurY='30' inner='false' />
	</filter>
	

	<legend shadow='low' fill_color='0' fill_alpha='5' line_alpha='0' line_thickness='0' bullet='circle' size='12' color='666666' alpha='75' margin='10' />

   
   <!-- drop the chart parts by series * dissolve, drop, spin, scale, zoom, blink, slide_right, slide_left, slide_up, slide_down, and none-->	
   <chart_transition type='zoom' delay='2' duration='1' order='series' />
   

	<series_explode>
		<number>20</number>
		<number>0</number>
		<number>5</number>
		<number>0</number>
		<number>10</number>
	</series_explode>



</chart>