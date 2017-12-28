<?php
//500 - 27/03/2010 - repair paydate kasi betul kat table feestu
$vmod="v5.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
				
		$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}
		}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="surah" value="<?php echo $surahno;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<div id="right" align="right"><?php echo $vmod;?></div>
</div><!-- end mypanel-->
<div id="story">
<?php
include ('../inc/header_school.php');
?>
<div id="mytitlebg"><?php echo $lg_student;?></div>

<table width="100%" border="0"  style="background:none">
  <tr>
	 <td valign="top" id="myborderfull" align="center" width="8%">
		<?php if(($file!="")&&(file_exists("$dir_image_user$file"))){?>
				<img src="<?php echo "$dir_image_user$file";?>" width="90" height="100">
		<?php } else echo "&nbsp;";?>
	 </td>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%"><?php echo $lg_name;?></td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo $lg_ic;?></td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo $lg_school;?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_class;?></td>
        <td>:</td>
        <td><?php echo "$clsname / $year";?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	
 	</td>
  </tr>
</table>

<div id="mytitlebg" align="center">LAPORAN PRESTASI KELAS AL-QURAN HIRA'</div>
<table width="100%" cellspacing="0">
<tr>
<td id="myborder" width="33%" valign="top">
<?php
		$sql="select * from syaathir_rec where uid='$uid' and jk='1' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$ms1=$row2['ms'];
		$sql="select * from syaathir_rec where uid='$uid' and jk='2' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$ms2=$row2['ms'];
		$sql="select * from syaathir_rec where uid='$uid' and jk='3' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$ms3=$row2['ms'];
		

?>
<div id="mytitlebg">A. TAHAP MENGENAL KAEDAH ASYATHIR</div>
        <table width="100%" cellpadding="3" cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">PERINGKAT </td>
                    <td width="20%" id="mytabletitle" align="center">MS</td>
            </tr>
            <tr>
                    <td id="myborder">PERINGKAT 1</td>
                    <td id="myborder" align="center" style="font-size:12px"><?php echo $ms1;?></td>
            </tr> 
            <tr>
                    <td id="myborder">PERINGKAT 2</td>
                    <td id="myborder" align="center" style="font-size:12px"><?php echo $ms2;?></td>
            </tr> 
            <tr>
                    <td id="myborder">PERINGKAT 3</td>
                    <td id="myborder" align="center" style="font-size:12px"><?php echo $ms3;?></td>
            </tr> 
            <tr>
                    <td id="myborder">TANDATANGAN IBUBAPA</td>
                    <td id="myborder">&nbsp;<br><br><br></td>
            </tr> 
        </table>
        
        
<div id="mytitlebg">B. TAHAP BACAAN AL-BAQARAH DAN JUZUK AMMA</div>
        <table width="100%" cellpadding="3" cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">PERINGKAT </td>
                    <td width="20%" id="mytabletitle" align="center">SURAH/AYAT</td>
            </tr>
            <tr>
                    <td id="myborder">AL-BAQARAH</td>
                    <td id="myborder">&nbsp;</td>
            </tr> 
            <tr>
                    <td id="myborder">JUZUK AMMA</td>
                    <td id="myborder">&nbsp;</td>
            </tr> 
            <tr>
                    <td id="myborder">HAFAZAN</td>
                    <td id="myborder">&nbsp;</td>
            </tr> 
            <tr>
                    <td id="myborder">TANDATANGAN IBUBAPA</td>
                    <td id="myborder">&nbsp;<br><br><br></td>
            </tr> 
        </table>


		<?php
		$sql="select * from surah_stu_read where uid='$uid' and reading='0' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$last_surahayat=$row2['surahayat'];
		if($last_surahayat=="")
			$last_surahayat=0;
		$last_surahno=$row2['surahno'];
		$last_surahname=stripslashes($row2['surahname']);
		
		
		$sql="select * from surah_stu_read where uid='$uid' and reading='1' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$last_tilawahayat=$row2['surahayat'];
		if($last_tilawahayat=="")
			$last_tilawahayat=0;
		$last_tilawahno=$row2['surahno'];
		$last_tilawahname=stripslashes($row2['surahname']);
?>
<div id="mytitlebg">C. TAHAP HAFAZAN</div>
        <table width="100%" cellpadding="3"  cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">PERINGKAT </td>
                    <td width="20%" id="mytabletitle" align="center">SURAH/AYAT</td>
            </tr>
            <tr>
                    <td id="myborder">HAFAZAN TERKINI</td>
                    <td id="myborder" align="center" style="font-size:12px"><?php echo "$last_surahname<br>Ayat $last_surahayat";?></td>
            </tr> 
            <tr>
                    <td id="myborder">TILAWAH</td>
                    <td id="myborder" align="center" style="font-size:12px"><?php echo "$last_tilawahname<br>Ayat $last_tilawahayat";?></td>
            </tr> 
            <tr>
                    <td id="myborder">TANDATANGAN IBUBAPA</td>
                    <td id="myborder">&nbsp;<br><br><br></td>
            </tr> 
        </table>
 </td>     
<td  id="myborder" width="33%" valign="top">

<div id="mytitlebg">HAFAZAN TAHAP 1</div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">Surah </td>
                    <td width="20%" id="mytabletitle" align="center">Capai / Ayat</td>
            </tr>
            <?php
						$surahname="Al-Fatihah";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=1 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
                    </tr>
  				<?php 
				$stano=90;
				$i=$stano-1;
				$sql="select * from alquran where surahno>=90 and surahno<=114";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
						$surahname=$row2['surahname'];
						$i++;
						$j++;
						
						$sql="select * from surah_stu_read where uid='$uid' and surahno=$i and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];

				?>
					<tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
                    </tr>

				<?php } ?>
        </table>
     
        
<td>
<td id="myborder" width="33%" valign="top">

<div id="mytitlebg">HAFAZAN TAHAP 2</div>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">Surah </td>
                    <td width="20%" id="mytabletitle" align="center">Capai / Ayat</td>
            </tr>

  				<?php 
				$stano=78;
				$i=$stano-1;
				$sql="select * from alquran where surahno>=78 and surahno<=89";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
						$surahname=$row2['surahname'];
						$i++;
						$j++;
						
						$sql="select * from surah_stu_read where uid='$uid' and surahno=$i and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];

				?>
					<tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
                    </tr>

				<?php } ?>
        </table>
        
<div id="mytitlebg">HAFAZAN TAHAP 3</div>

<table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                    <td width="30%" id="mytabletitle" align="center">Surah </td>
                    <td width="20%" id="mytabletitle" align="center">Capai / Ayat</td>
            </tr>
            <?php
						$surahname="As Sajdah";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=32 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Yaassiin";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=36 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Ad Dukhaan";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=44 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Al Waaqi'ah";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=56 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Al Jumu'ah";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=62 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Al Mulk";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=67 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
             <?php
						$surahname="Al Insaan";
						$sql="select * from surah_stu_read where uid='$uid' and surahno=76 and reading=0";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$ayat=$row3['surahayat'];
			?>
            <tr>
                    <td id="myborder" align="center"><?php echo "$surahname";?></td>
                    <td id="myborder" align="center"><?php echo "$ayat";?></td>
            </tr>
  				
        </table>


</td>
</table>
</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->