<?php 
include_once('../etc/db.php');
include_once('session.php');
verify();

	$sql="select * from type where grp='openexam' and prm='EHAFAZAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
	
	
	$exam=$_POST['exam'];

	$semester=$_POST['semester'];
		if($semester!="") 
			$sqlsem=" and code='$semester'";

	$sid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];
	
	$year=$_POST['year'];
	if($year==""){
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by id desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $year=$row['year'];
	}
	
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid'  and sch_id=$sid order by id desc";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}

	
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="haf_slip">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>


<div id="story">
	<div id="mytitlebg">STATUS HAFALAN QURAN</div>
	<div id="mytitlebg" align="right"><select name="semester" onChange="document.myform.submit();">
					<?php    
								echo "<option value=\"\">- Semester -</option>";
								if($semester=="1"){$selected="selected";}else{$selected="";}
								echo "<option value=1 $selected>Semester 1</option>";
								if($semester=="2"){$selected="selected";}else{$selected="";}
								echo "<option value=2 $selected>Semester 2</option>";
							
					?>
				  </select>&nbsp;<select name="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam==""){
						echo "<option value=\"\">- $lg_exam -</option>";
					}
					
					$sql="select * from type where grp='exam' order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
						if($exam==$b){$selected="selected";}else{$selected="";}
                        echo "<option value=\"$b\" $selected>$a</option>";
            		}
					if($exam!=""){
					echo "<option value=\"\">- $lg_exam -</option>";
					}

			?>
</select>
</div>
<table width="100%"  cellspacing="0" cellpadding="4" style="font-size:18px ">
	<tr>
<?php 
	
	$sql="select * from type where grp='surahhafazan' and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) $sqlsem order by idx";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$surahname=stripslashes($row['prm']);
		$surah=addslashes($row['prm']);
		$i++;
						$sql="select * from surah_stu_status where uid='$uid' and surahname='$surah'";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$status=$row3['status'];
						$reading=$row3['reading'];
						if($reading=="")
							$bgtd="";
						elseif($reading=="0")
							$bgtd="bgcolor=\"#00FF66\"";
						else
							$bgtd="bgcolor=\"#FFCC99\"";
						if($status=="0")
							$bgtd="bgcolor=\"#FFFF00\"";
?>
		<td <?php echo $bgtd;?> id="myborder" width="5%">
			<a href="#" ><?php echo "$i.$surahname";?></a>
		</td>
<?php if($i%3==0) echo "</tr><tr>"; } ?>
	</tr>
</table>
<br><br>
<table width="100%" cellspacing="0" cellpadding="2" style="font-size:11px">
	<tr>
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Deskripsi</td>            
			<td class="mytableheader" style="border-right:none;" width="20%" align="center">Nilai</td>
	</tr><?php
 		$sql="select * from type where grp='hafaz_cate' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row=mysql_fetch_assoc($res)){
				$cate=$row['prm'];
				$cate=stripslashes($cate);
				$code_disable=$row['code'];
				$q++;
				$jj=0;
				
?>				
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td id="mytabletitle" style="border-right:none;" align="center"><?php echo $q;?></td>
                    <td id="mytabletitle" style="border-right:none;"><?php echo $cate;?></td>
                    <td id="mytabletitle" style="border-right:none;">&nbsp;</td>
     </tr>
<?php
		if($code_disable){
		$kk=0;
		$sqlsurahstu="select distinct(surahname) from surah_stu_read where uid='$uid' and sid='$sid' and year='$year'";
		$ressurahstu=mysql_query($sqlsurahstu)or die("$sqlsurahstu query failed:".mysql_error());
		while($rowsurahstu=mysql_fetch_assoc($ressurahstu)){
			$surah=$rowsurahstu['surahname'];
			
			$sqlsurahno="select * from surah_stu_read where uid='$uid' and sid='$sid' and year='$year' and surahname='$surah'";
			$ressurahno=mysql_query($sqlsurahno)or die("$sqlsurahno query failed:".mysql_error());
			$rowsurahno=mysql_fetch_assoc($ressurahno);
			$surahno=$rowsurahno['surahno'];

			$jj++;
		
						$kk++;
						$totalitem=0;
						$bg="";

						$sqlmark="select * from hafaz_exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='$exam' and sub_code='AQ|$surahno'";
						$resmark=mysql_query($sqlmark)or die("$sqlmark query failed:".mysql_error());
						$rowmark=mysql_fetch_assoc($resmark);
						$mark=$rowmark['point'];
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;"><?php echo "$q-$jj-$kk";?>&nbsp;&nbsp;<?php echo "$surahno $surah";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
									<?php echo $mark;?>
									</td>
									</tr>
<?php
				
		}
		}else{
		$sql="select distinct(type) from hafaz_construct where cate='$cate' and isdel=0 order by cate";
		$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['type'];
				$type=stripslashes($type);
				$jj++;
				$bg="#FAFAFA";
				if($type!=""){
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "$q-$jj";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo $type;?></td>
                            <td id="myborder" style="border-top:none;border-right:none;">&nbsp;</td>
             </tr>
            <?php //show itemized item
		}
				$kk=0;
				$type1=addslashes($type);
				$sql="select * from hafaz_construct where type='$type1' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and isdel=0 order by cate";
				$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
				while($row3=mysql_fetch_assoc($res3)){
						$xid=$row3['id'];
						$cc=$row3['code'];
						$vv=$row3['val'];
						$item=$row3['item'];
						$grading=$row3['grading'];
						$kk++;
						$totalitem=0;
						$bg="";

						$sqlmark2="select * from hafaz_exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='$exam' and sub_code='BG|$cc'";
						$resmark2=mysql_query($sqlmark2)or die("$sqlmark2 query failed:".mysql_error());
						$rowmark2=mysql_fetch_assoc($resmark2);
						$mark2=$rowmark2['point'];
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;"><?php echo "$q-$jj-$kk";?>&nbsp;&nbsp;<?php echo "$cc $item";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;" align="center">
									<?php echo $mark2;?>
									</td>
                        </tr>
<?php }
			} //end if (code_disable?>
<?php } ?> 
<?php } ?>     
 </table>
 <br>
<table width="100%" cellspacing="0" cellpadding="0" border="0"  style="font-size:11px">
<tr>
<td style="border-right:none;" align="center">&nbsp;</td>
<td class="mytableheader" style="border-right:none;" align="center">&nbsp;Catatan Perkembangan Murid</td>
<td	style="border-right:none;" align="center">&nbsp;</td>
</tr>
<tr>
<?php
			$sqlmsg="select * from hafaz_stu_summary where uid='$uid' and sid='$sid' and year='$year' and exam='$exam'";
			$resmsg=mysql_query($sqlmsg)or die("$sqlmsg query failed:".mysql_error());
			$rowmsg=mysql_fetch_assoc($resmsg);
			$hafaz_msg=$rowmsg['hafaz_msg'];

?>
<td style="border-right:none;border-bottom:none" width="10%">&nbsp;</td>
<td id="myborder" width="80%" height="80" valign="top">&nbsp;<?php echo $hafaz_msg;?></td>
<td width="10%">&nbsp;</td>
</tr>
</table>
</div></div><!-- content/story -->
</form>
</body>
</html>
