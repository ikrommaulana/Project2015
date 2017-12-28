<!--
THIS IS IMTIAZ VERSION ONLY
 -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>
<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<div id="mytitlebg" style="background-color:#FFFF00 ">G. MAKLUMAT AKADEMIK</div>
<table width="100%" cellspacing="0" >
<tr>
<td width="50%" valign="top"  id="myborder">

<?php 
$sql="select * from stureg_akademik where xid='$id' and exam='$exam'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$s1=explode("|",$row['s1']);
$s2=explode("|",$row['s2']);
$s3=explode("|",$row['s3']);
$s4=explode("|",$row['s4']);
$s5=explode("|",$row['s5']);
$sch=stripslashes($row['sch']);
$schyear=$row['year'];
?>

<div id="mytitlebg">1. MAKLUMAT PEPERIKSAAN <?php echo $examname;?></div>
<font color="#FF0000" style="font-weight:bold ">
PERHATIAN. HANYA PELAJAR 5A SAHAJA LAYAK MEMOHON.
</font>
<?php 
echo "(Sila tandakan subjek yang berkenaan sahaja)";
?>
<table width="100%" cellspacing="0" style="color:#669900">
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			<td id="myborder" style="border-top:none;border-left:none;border-right:none;" width="50%" align="right">NAMA SEKOLAH* </td>
			<td><input type="text" name="upsrsch" size="30" value="<?php echo $sch;?>"></td>
		</tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			<td id="myborder" style="border-top:none;border-left:none;border-right:none;" align="right">TAHUN PEPERIKSAAN*</td>
			<td><input type="text" name="upsryear" size="6" value="<?php echo $schyear;?>"></td>
		</tr>
<?php
		$j=1;
		$sql="select * from type where grp='profileexam' and code='$exam' order by id";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$sub=$row2['prm'];
?>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			<td id="myborder" style="border-top:none;border-left:none;border-right:none;" align="right"><?php echo strtoupper($sub);?></td>
			<td>
				<select name="sub[]">
                <?php
					$s1=explode("|",$row["s$j"]);
					$j++;
					if($s1[1]!="")
						echo "<option value=\"$sub|$s1[1]\">$s1[1]</option>";
					else
						echo "<option value=\"\">- Gred -</option>";
					$sql="select * from type where grp='grade' and code='$exam' order by val";
					$res3=mysql_query($sql)or die("query failed:".mysql_error());
					while($row3=mysql_fetch_assoc($res3)){
								$g=$row3['prm'];
								echo "<option value=\"$sub|$g\">$g</option>";
					}				  
				?>
              </select>
			</td>
		</tr>
		
<?php } ?>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			<td id="myborder" style="border-top:none;border-left:none;border-right:none;" align="right">PEKA</td>
			<td >
				<select name="sub[]">
                <?php
					$s1=explode("|",$row["s$j"]);
					$j++;
					if($s1[1]!="")
						echo "<option value=\"PEKA|$s1[1]\">$s1[1]</option>";
					else
						echo "<option value=\"\">- Gred -</option>";
						echo "<option value=\"PEKA|1\">1</option>";
						echo "<option value=\"PEKA|2\">2</option>";
						echo "<option value=\"PEKA|3\">3</option>";
						echo "<option value=\"PEKA|4\">4</option>";
						echo "<option value=\"PEKA|5\">5</option>";
							  
				?>
              </select>
			</td>
		</tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			<td id="myborder" style="border-top:none;border-left:none;border-right:none;" align="right">FARDU AIN</td>
			<td>
				<select name="sub[]">
                <?php
					$s1=explode("|",$row["s$j"]);
					$j++;
					if($s1[1]!="")
						echo "<option value=\"FARDU AIN|$s1[1]\">$s1[1]</option>";
					else
						echo "<option value=\"\">- Gred -</option>";
						echo "<option value=\"FARDU AIN|Lulus\">Lulus</option>";
						echo "<option value=\"FARDU AIN|Gagal\">Gagal</option>";
							  
				?>
              </select>
			</td>
		</tr>
</table>


</td>
<td width="50%" id="myborder">

		<?php 
		$sql="select * from stureg_akademik where xid='$id' and exam='LAIN-LAIN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sch=stripslashes($row['sch']);
		$schyear=$row['year'];
		$xexamname=$row['etc'];
		?>
		
		<div id="mytitlebg">2. PEPERIKSAAN LAIN (Jika ada. Contoh: KAFA)</div>
		<table width="100%" cellspacing="0" style="color:#669900">
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
            	<td id="myborder" style="border-top:none;border-left:none;border-right:none;" width="30%" colspan="2">Nama Sekolah </td>
				<td width="70%"><input type="text" name="examlain_sch" size="30" value="<?php echo $sch;?>">
				</td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder"  style="border-top:none;border-left:none;border-right:none;" colspan="2">Tahun</td>
				<td ><input type="text" name="examlain_year" size="5" value="<?php echo $schyear;?>"></td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder"  style="border-top:none;border-left:none;border-right:none;" colspan="2">Nama Peperiksaan</td>
				<td><input type="text" name="examlain_name" size="30" value="<?php echo $xexamname;?>"></td>
			</tr>
          </table>
          <table width="100%" cellspacing="0" cellpadding="0" style="color:#669900">
          	<tr id="mytitle">
            	<td>&nbsp;</td>
				<td>Mata Pelajaran</td>
				<td>Gred/Keputusan</td>
			</tr>
		<?php 
		for($j=1;$j<=10;$j++){
				$s1=explode("|",$row["s$j"]);
		?>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
            	<td id="myborder" width="1%"><?php echo $j;?></td>
				<td width="30%"><input type="text" name="examlain_sub[]" size="40" value="<?php echo stripslashes($s1[0]);?>"></td>
				<td width="70%"><input type="text" name="examlain_gred[]" size="20" value="<?php echo $s1[1];?>"></td>
			</tr>
		<?php } ?>
		</table>



</td>
</tr>
</table> 



<table width="100%" cellspacing="0" >
<tr><td width="50%" valign="top" id="myborder">

<?php 
	$sql="select * from stureg_akademik where xid='$id' and exam='KOQ'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sch=$row['sch'];
	$schyear=$row['year'];
	$xexamname=$row['etc'];
?>
<div id="mytitlebg">3. MAKLUMAT KOKURIKULUM</div>
<table width="100%"  border="0" style="color:#669900">
	<tr>
		<td width="2%">&nbsp;</td>
		<td width="30%">Sukan/Kelab/Persatuan</td>
		<td width="10%">Jawatan</td>
		<td width="60%">Tahap Sekolah/Daerah/Negeri/Negara</td>
	</tr>
<?php for($j=1;$j<=5;$j++){ $s1=explode("|",$row["s$j"]); ?>
	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
		<td><?php echo $j;?></td>
		<td><input type="text" name="koq[]" size="25" value="<?php echo $s1[0];?>"></td>
		<td><input type="text" name="koqj[]" size="20" value="<?php echo $s1[1];?>"></td>
		<td><input type="text" name="koqt[]" size="20" value="<?php echo $s1[2];?>"></td>
	</tr>
<?php } ?>
</table>

  
  
</td>
<td width="50%" id="myborder" valign="top">


<?php 
	$sql="select s1,s2,s3,s4,s5 from stureg_akademik where xid='$id' and exam='AL-QURAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$bacaquran=explode("|",$row[0]);
	$bacajawi=explode("|",$row[1]);
	$tulisjawi=explode("|",$row[2]);
	$khatamquran=explode("|",$row[3]);
	$hafazquran=explode("|",$row[4]);
?>

<div id="mytitlebg">4. PENGUASAAN AL-QURAN</div>
  <table width="100%" cellspacing="0" style="color:#669900">
  	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;" width="30%">Kelancaran Membaca Quran </td>
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;" width="70%">
	  <input type="radio" name="bacaquran" value="Baik" <?php if($bacaquran[1]=="Baik") echo "checked";?> >Baik&nbsp;&nbsp;
	  <input type="radio" name="bacaquran" value="Sederhana" <?php if($bacaquran[1]=="Sederhana") echo "checked";?> >Sederhana&nbsp;&nbsp;
	  <input type="radio" name="bacaquran" value="Lemah" <?php if($bacaquran[1]=="Lemah") echo "checked";?>>Lemah</td>
    </tr>
	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
      <td id="myborder"  style="border-top:none;border-left:none;border-right:none;">Kemahiran Membaca Jawi </td>
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
	  <input type="radio" name="bacajawi" value="Baik"  <?php if($bacajawi[1]=="Baik") echo "checked";?>>Baik&nbsp;&nbsp;
	  <input type="radio" name="bacajawi" value="Sederhana"  <?php if($bacajawi[1]=="Sederhana") echo "checked";?>>Sederhana&nbsp;&nbsp;
	  <input type="radio" name="bacajawi" value="Lemah"  <?php if($bacajawi[1]=="Lemah") echo "checked";?>>Lemah</td>
    </tr>    
    <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
      <td id="myborder"  style="border-top:none;border-left:none;border-right:none;">Kemahiran Menulis Jawi </td>
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
	  <input type="radio" name="tulisjawi" value="Baik"  <?php if($tulisjawi[1]=="Baik") echo "checked";?>>Baik&nbsp;&nbsp;
	  <input type="radio" name="tulisjawi" value="Sederhana"  <?php if($tulisjawi[1]=="Sederhana") echo "checked";?>>Sederhana&nbsp;&nbsp;
	  <input type="radio" name="tulisjawi" value="Lemah"  <?php if($tulisjawi[1]=="Lemah") echo "checked";?>>Lemah</td>
    </tr>
	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
      <td id="myborder"  style="border-top:none;border-left:none;border-right:none;">Telah Khatam Quran </td>
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
	  	<input type="radio" name="khatam" value="YA" <?php if($khatamquran[1]=="YA") echo "checked";?>>Ya&nbsp;&nbsp;
		<input type="radio" name="khatam" value="TIDAK" <?php if($khatamquran[1]=="TIDAK") echo "checked";?>>Tidak.
	  	<font color="#669900"><br>
	  	Jika TIDAK, juzuk ke berapakah sekarang?</font>
		<input type="text" name="juzuk" size="5" value="<?php echo $khatamquran[2];?>">  
	  </td>
    </tr>
    <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Pernah Hafaz Quran </td>
      <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
	  	<input type="radio" name="hafaz" value="YA" <?php if($hafazquran[1]=="YA") echo "checked";?>>Ya&nbsp;&nbsp;
		<input type="radio" name="hafaz" value="TIDAK" <?php if($hafazquran[1]=="TIDAK") echo "checked";?>>Tidak.
	  	<font color="#669900"><br>
	  	Jika YA, Brapa juzukkah yang telah dihafal?</font>
		<input type="text" name="hafaz_no" size="5"  value="<?php echo $hafazquran[2];?>">  
	  </td>
    </tr>
  </table>
  


</td>
</tr>
</table> 




<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
</body>
</html>
