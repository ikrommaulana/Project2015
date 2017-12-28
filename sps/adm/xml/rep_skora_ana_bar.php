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
<?php for($i=$maxa;$i>=0;$i--){?>
			<string><?php echo "$i $topgrade";?></string>
<?php } ?>
		</row>
		
		<row>
			<string><?php echo $b;?></string>
<?php for($i=$maxa;$i>=0;$i--){

		if($show_a1a2)
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$lvl' and year=$year and exam='$exam' and (g1+g2)=$i";
		else
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$lvl' and year=$year and exam='$exam' and g1=$i";
    	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$xx=$row2[0];

?>
			<number bevel='blue' shadow='low'><?php echo $xx;?></number>
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
      	<text shadow='high' color='333333' alpha='50' rotation='-90' size='20' x='5' y='270' width='300' height='200' h_align='center'><?php echo strtoupper($lg_student);?></text>
		<text shadow='high' color='333333' alpha='50' rotation='0' size='20' x='120' y='10' width='400' height='400' h_align='left' v_align='top'><?php echo strtoupper($lg_total);?> <?php echo $topgrade;?></text>
		<text shadow='shadow1' color='999999' alpha='40' rotation='0' size='70' x='140' y='70' width='400' height='400' h_align='left'><?php echo $year;?></text>
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