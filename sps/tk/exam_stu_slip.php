<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
verify("");
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];
$uid=$_REQUEST['uid'];
$stu=$_REQUEST['stu'];
$smt=$_REQUEST['smt'];
$lvl=$_REQUEST['lvl'];
$cls=$_REQUEST['cls'];
$year=$_REQUEST['year'];
$arrmarkah=$_POST['markah'];
$op=$_REQUEST['op'];
$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];	

$sql="select * from sch where id='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sname=stripslashes($row['sname']);
$addr=$row['addr'];
$state=$row['state'];
$sqlsid="and sid='$sid'";
		

$exam=$_REQUEST['exam'];
$sqlexam="and exam='$exam'";
$sql="select * from type where grp='ujian' and code='$exam'";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$examname=$row['prm'];

if(count($stu)==0)
			$stu[0]=$uid;
if(count($stu)==1)
			$uid=$stu[0];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="javascript">
function process_new(){
	document.myform.code.value="";
	document.myform.item.value="";
	document.myform.val.value="";
	document.myform.edit.value="";
}
function process_form(action,xid){
	var ret="";
	var cflag=false;
	
	if(action=='save'){
		document.myform.op.value=action;
		document.myform.submit();		
		return;
	}
}

</script>

</script>

</head>

<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="<?php echo $p;?>">
 	<input type="hidden" name="op">
	<input type="hidden" name="xid">
    <input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="cls" value="<?php echo $cls;?>">
	<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="exam" value="<?php echo $exam;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">



<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
                <a href="#" onClick="window.print()"  id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.close();"  id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>  
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->

<?php
$totalpage=count($stu);
for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
		$pageno++;
		$uid=$stu[$numberofstudent];
		echo "<input type=\"hidden\" name=\"stu[]\" value=\"$uid\">";
?>		
<div id="story" style="min-height:none;">
		
<?php include ('../inc/header_school.php');?>

<?php
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$ccode=$row['cls_code'];
		$cname=stripslashes($row['cls_name']);
		
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=stripslashes($row['name']);
		$ic=$row['ic'];
		$sex=$row['sex'];
		$mentor=$row['mentor'];
		$rdate=$row['rdate'];
		$file=$row['file'];
		$totaljk=$row['hafazan_totaljk'];

		$sql="select * from usr where uid='$mentor'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$mentorname=$row['name'];
?>
<table width="100%" id="mytitlebg" style="font-size: 11px">
  <tr>
  <?php if(($file!="")&&($CONFIG['SHOW_PICTURE']['val'])){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="30%" valign="top"><?php echo strtoupper("Nama Murid");?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("Nomor Induk");?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td width="20%" valign="top"><?php echo strtoupper($lg_class);?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php echo strtoupper("$cname");?></td>
      </tr>
	   <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%" >
	<tr>
		<td><?php echo strtoupper($lg_exam);?></td>
		<td>:</td>
		<td><?php echo strtoupper("$examname");?> </td>
	</tr>
	<tr>
		<td><?php echo "SEMESTER";?></td>
		<td>:</td>
		<td><?php echo $smt; ?></td>
	</tr>
	  <tr>
        <td><?php echo strtoupper("tahun ajaran");?></td>
        <td>:</td>
        <td><?php echo $year;?> </td>
      </tr>
    </table>
 	</td>
  </tr>
</table>
<?php
/*
		$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and exam='$exam' and year='$year' and type='comment1'";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$msg=$row3['msg'];
		if($msg!=""){
			echo "<div id=\"mytitlebg\">URAIAN PELAJARAN :-</div>";
		}
		echo $msg;
?>

<?php

		$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and exam='$exam' and year='$year' and type='comment2'";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$msg=$row3['msg'];
		if($msg!=""){
		echo  "<div id=\"mytitlebg\">TUJUAN PELAJARAN :-</div>";
		}
		echo $msg;
*/
?>



<table width="100%" cellspacing="0" cellpadding="2" style="font-size:11px">
	<tr>
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Kompetensi Dasar</td>            
			<?php if($ON_TK_KOMENTAR){?>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center">Pencapaian</td>
			<td class="mytableheader" style="border-right:none;" width="35%" align="center">Deskripsi</td>
			<?php }else{?>
			<td class="mytableheader" style="border-right:none;" width="20%" align="center">Kompetensi Dasar</td>
			<?php }?>
	</tr>
<?php
 		$sql="select * from type where grp='sub_cate' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row=mysql_fetch_assoc($res)){
				$cate=$row['prm'];
				$q++;
				$jj=0;
				
?>				
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                    <td id="mytabletitle" style="border-right:none;" align="center" colspan="4"><?php echo $cate;?></td>
     </tr>
<?php
		$sql="select distinct(prm) from sub_construct a left join type b on (a.type=b.prm) where cate='$cate' and b.grp='sub_type' and isdel=0 order by b.idx";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['prm'];
				$jj++;
				$bg="#FAFAFA";
				if($type!=""){
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "<strong>$jj</strong>";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo "<strong>$type</strong>";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;">&nbsp;</td>
			<?php
				$sqltkkomentar="select * from sub_stu_summary where sid='$sid' and exam='$exam' and uid='$uid' and year='$year' and cls='$cls' and sub_type='$type'";
				$restkkomentar=mysql_query($sqltkkomentar)or die("$sqltkkomentar query failed:".mysql_error()); 
				$rowtkkomentar=mysql_fetch_assoc($restkkomentar);
				$tkkomentar=$rowtkkomentar['sub_msg'];
			?>			
							<td id="myborder" style="border-top:none;border-right:none;"></td>
							

             </tr>
            <?php //show itemized item
		}
				//$sql = mysql_query("SELECT A.*, (SELECT COUNT(fakultas) FROM tbfakultas WHERE fakultas=A.fakultas) AS
				//jumlah FROM tbfakultas A ORDER BY A.fakultas");

				$kk=0;
				$jum=1;
				
				
				//$sql="select * from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and isdel=0 order by cate";
				$sql="select sub_construct.*, (select count(item) from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and smt='$smt' and isdel=0)
				as jumlah from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$lvl' and exam='$exam' and smt='$smt' and isdel=0 order by cate";
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
				?>
				<tr >                          
				<?php
				while($row3=mysql_fetch_assoc($res3))
				{
					$xid=$row3['id'];
					$cc=$row3['code'];
					$vv=$row3['val'];
					$item=$row3['item'];
					$grading=$row3['grading'];
					$kk++;	
					?>
						
						<td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
						<td id="myborder" style="border-top:none;border-right:none;"><!--&nbsp;&nbsp;--><?php //echo "$cc"; 
											echo "$item";?></td>
						<td id="myborder" style="border-top:none;border-right:none;" align="center" >					
			
						<?php
							$sql="select * from exam where stu_uid='$uid' and year='$year' and sub_code='$cc' and sch_id='$sid'";
							$res6=mysql_query($sql)or die("$sql failed:".mysql_error());
							$row6=mysql_fetch_assoc($res6);
							$grade=$row6['grade'];
							$point=$row6['point'];
							echo "$grade";
						?></td>	
					<?php	
					if($jum <= 1)
					{ ?>
						<td id="myborder" rowspan="<?php echo $row3['jumlah']; ?>">
							<?php echo $tkkomentar; ?>
						</td>
					<?php
						$jum = $row3['jumlah'];      
							
					}
					?>		
				</tr>				
                        
<?php } ?>

<?php } ?> 
<?php } ?>     
 </table>
 <br>
 <table width="100%" cellspacing="0" cellpadding="2" border="0" style="font-size:11px">
 <tr><td align="center">
 <table width="60%" cellspacing="0" cellpadding="2" border="0" style="font-size:11px">
 <tr style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
 <td id="mytabletitle" style="border-right:none;" align="center">Deskripsi Umum</td>
 </tr>
<tr style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
 <td id="myborder">
 <?php
		$sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year' and grp='internal' and type is null";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$mm=$row3['msg'];
		$kg=$row3['wg'];
		$cm=$row3['hg'];
		$at=$row3['totalatt'];
		$dy=$row3['totalday'];
		$sakit=$row3['sakit'];
		$izin=$row3['izin'];
		$alpa=$row3['noreason'];
		echo $mm;
?>
</td>
 </tr>
 </table>
 </td></tr>
 </table>

 <div style="font-size:11px; color:#666; font-weight:bold;">
        <?php
        $sql="select * from letter where type='examslip_footer' and sid='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $content=stripslashes($row['content']);
		
		$d=date('d');
		$m=date('m');
		$y=date('Y');
		$sqlmonth="select * from month where no='$m'";
		$resmonth=mysql_query($sqlmonth)or die("$sqlmonth query failed:".mysql_error());
		$rowmonth=mysql_fetch_assoc($resmonth);
		$monthname=$rowmonth['name'];
		$system_date="$d $monthname $y";
		$content=str_replace("{system_date}",$system_date,$content);

        echo $content;
        ?> 
 </div>

</div><!-- story -->

<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php }//end for loop ?>


	<div align="right" class="printhidden">
        	<a href="../adm/letter_config2.php?sid=<?php echo $sid;?>&type=examslip_footer&name=examslip_footer" target="_blank">Footer Setting</a> 
	</div>

</div><!-- content -->
</form>	
</body>
</html>
