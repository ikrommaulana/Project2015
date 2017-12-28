<?php
//160910 5.0.0 - update gui.. data kementrian
//120305 - fix session issue
$vmod="v5.0.1";
$vdate="120305";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$adm = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
			
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];

	$dts=$_POST['rdate'];
	if($dts=="")
		$dts=date('Y-m-d');
	$dte=$_POST['edate'];
	$kelab=$_POST['kelab'];
	
	$jawatan=$_POST['jawatan'];
	if($jawatan!="")
		list($posval,$pos)=explode("|",$jawatan);
	else
		$posval=0;
	
	$penglibatan=$_POST['penglibatan'];
	if($penglibatan!="")
		list($parval,$par)=explode("|",$penglibatan);
	else
		$parval=0;
		
	$pencapaian=$_POST['pencapaian'];
	if($pencapaian!="")
		list($achval,$ach)=explode("|",$pencapaian);
	else
		$achval=0;
		
	$status=$_POST['status'];
	$year=$_POST['year'];
	if($year=="")
		    $year=date('Y');
	if($status=="")
		$status=0;
	$op=$_POST['op'];
	$id=$_POST['id'];
	$del=$_POST['del'];
	$uid=$_REQUEST['uid'];
	$attval=$_REQUEST['attval'];
	$attfull=$_REQUEST['attfull'];
	$attstu=$_REQUEST['attstu'];
	$par_des=$_REQUEST['par_des'];
	$ach_des=$_REQUEST['ach_des'];
	if($uid!=""){
		$sql="select * from stu where sch_id=$sid and uid='$uid'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $xname=ucwords(strtolower(stripslashes($row['name'])));
		$xic=$row['ic'];
		$file=$row['file'];
			
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$cname=$row2['cls_name'];
	}
		
		
	if($op=='save'){
		$sql="select * from koq where grp='koq' and prm='$kelab' and (sid=0 or sid=$sid)"; 	
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$kname=$row['prm'];
		$kcode=$row['code'];
		$ktype=$row['val'];
		$totalval=$posval+$parval+$achval+$attval;
		$sql="update koq_stu set dts='$dts',dte='$dte',koq_name='$kname',koq_type=$ktype,koq_code='$kcode',
			pos='$pos',pos_val='$posval',par='$par',par_val='$parval',ach='$ach',ach_val='$achval',ach_des='$ach_des',par_des='$par_des',total_val='$totalval',
			att_val='$attval',att_full='$attfull',att_stu='$attstu',adm='$adm',dt=now() where id=$id";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
		$kelab="";
		$jawatan="";
		$status="";
		$id="";
	}
	if($op=='delete'){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from koq_stu where id=$del[$i]";
		      	mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
		}
		$kelab="";
		$jawatan="";
		$status="";
		$id="";
	}
	if($id!=""){
		$sql="select * from koq_stu where id=$id"; 	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$kelab=$row['koq_name'];
		$koqyear=$row['year'];
		$koqtype=$row['koq_type'];
		$koqcode=$row['koq_code'];
		$pos=$row['pos'];
		$posval=$row['pos_val'];
		$ach=$row['ach'];
		$achval=$row['ach_val'];
		$par=$row['par'];
		$parval=$row['par_val'];
		$par_des=$row['par_des'];
		$ach_des=$row['ach_des'];
		
		$attval=$row['att_val'];
		$attstu=$row['att_stu'];
		$attfull=$row['att_full'];
		
		$status=$row['sta'];
		$dts=$row['dts'];
		$dte=$row['dte'];
		if($dte=="0000-00-00")
			$dte=""; 
		$sql="select * from koq where code='$koqcode'"; 	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$setjawatan=$row['etc'];
	}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='save'){
		if(document.myform.year.value==""){
			alert("Please insert year");
			document.myform.year.focus();
			return;
		}
		if(document.myform.jawatan.value==""){
			alert("Choose Position");
			document.myform.jawatan.focus();
			return;
		}
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='delete'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                	if(e.checked==true)
						cflag=true;
                	else
						allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}
function kira_kedatangan(){
	full=document.myform.attfull.value;
	hadir=document.myform.attstu.value;
	document.myform.attval.value=Math.round(hadir/full*50);
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../ekoq/hos_stu_reg">
	<input type="hidden" name="op">
	<input type="hidden" name="kelab" value="<?php echo $kelab;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
		<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="javascript: href='koq_stu_reg.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitlebg"><?php echo strtoupper($lg_cocurriculum);?> <?php echo $f;?></div>
<table width="100%">
  <tr>
<?php if($file!=""){?>
  	<td width="5%" align="center" valign="top">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%"><?php echo strtoupper($lg_name);?></td>
        <td width="1%">:</td>
        <td width="85%"><?php echo strtoupper("$xname");?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$uid");?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$xic");?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_school);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$sname");?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname / $year");?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%">
	  <tr>
        <td width="24%"></td>
        <td width="1%"></td>
        <td width="75%"></td>
      </tr>
	 
    </table>
 	</td>
  </tr>
</table>

<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">
<div id="mytitlebg"><?php echo strtoupper($lg_update);?> : <?php echo strtoupper($kelab);?></div>
<table width="100%" cellspacing="0" cellpadding="5">
  <tr>
    <td width="10%" id="myborder"><?php echo strtoupper("$lg_date $lg_register");?></td>
    <td width="90%" id="myborder"><input name="rdate" type="text" id="rdate" value="<?php echo "$dts";?>" readonly size="10" onClick="displayDatePicker('rdate');" onKeyDown="displayDatePicker('rdate');"></td>
   </tr>
  <tr>
    <td id="myborder"><?php echo strtoupper("$lg_attendance");?></td>
    <td id="myborder">
		<?php echo strtoupper("$lg_day");?>
				<select name="attstu" onChange="kira_kedatangan();">
 <?php	
 			if($attstu!="")
				echo "<option value=\"$attstu\">$attstu</option>";
			for($i=0;$i<=18;$i++)
				echo "<option value=\"$i\">$i</option>";
?>
		</select> /
		<select name="attfull" onChange="kira_kedatangan();">
 <?php	
 		if($attfull>0)
				echo "<option value=\"$attfull\">$attfull</option>";
			for($i=18;$i>0;$i--)
				echo "<option value=\"$i\">$i</option>";
?>
		</select>


		<?php echo strtoupper("$lg_mark");?>
		<input type="text" name="attval" size="3" readonly value="<?php echo "$attval";?>">
	</td>
  </tr>
		<tr>
		<td id="myborder"><?php echo strtoupper($lg_position);?></td>
		<td id="myborder">
		<select name="jawatan">
 <?php	
			if($pos=="")
				echo "<option value=\"\">- $lg_select -</option>";
			else{
				 $v=sprintf("%02d",$posval);
				echo "<option value=\"$posval|$pos\">$v : $pos</option>";
			}
			$sql="select * from type where grp='koq_jawatan' and code='$setjawatan' order by val desc, idx"; 	
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        $v=sprintf("%02d",$row['val']);
                        $val=$row['val'];
                        echo "<option value=\"$val|$s\">$v : $s</option>";
            }
?>
            </select>
		</td>
	<tr>
		<td id="myborder"><?php echo strtoupper($lg_participation);?></td>
		<td id="myborder">
			<select name="penglibatan">
 <?php	
			if($par=="")
				echo "<option value=\"\">- $lg_select -</option>";
			else{
				 $v=sprintf("%02d",$parval);
				echo "<option value=\"$parval|$par\">$v : $par</option>";
			}
			$sql="select * from type where grp='koq_penglibatan' order by id"; 	
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=sprintf("%02d",$row['val']);
						$val=$row['val'];
                        echo "<option value=\"$val|$s\">$v : $s</option>";
            }
?>
            </select> 
			<?php echo strtoupper("$lg_description");?> &raquo; <input type="text" name="par_des" size="48" value="<?php echo $par_des;?>"> 
			<?php
			if($LG=="BM") echo "Cth: Menyertai seminar penulisan peringkat negeri";
			else echo "Eg: Join writing seminar at state level";
			?>

		</td>
  	<tr>
	<tr>
		<td  id="myborder"><?php echo strtoupper($lg_achievement);?></td>
		<td  id="myborder">
		<select name="pencapaian">
 <?php	
			if($ach=="")
				echo "<option value=\"\">- $lg_select -</option>";
			else{
				 $v=sprintf("%02d",$achval);
				echo "<option value=\"$achval|$ach\">$v : $ach</option>";
			}
			$sql="select * from type where grp='koq_pencapaian' order by id"; 	
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=sprintf("%02d",$row['val']);
                        $val=$row['val'];
                        echo "<option value=\"$val|$s\">$v : $s</option>";
            }
?>
            </select>
			<?php echo strtoupper("$lg_description");?> &raquo; 
			<input type="text" name="ach_des" size="48" value="<?php echo $ach_des;?>">
			<?php 
			if($LG=="BM") echo "Cth: Naib Johan Bahas antara kelas";
			else echo "Eg: Runner up debat between class";
			?>
		</td>
  	<tr>
  </tr>

</table>
</div><!-- end block -->

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
		<td id="mytabletitle" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_session);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_group);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_activity);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_attendance);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_position);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_participation);?></td>
		<td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($lg_achievement);?></td>
		<td id="mytabletitle" width="15%" align="center"><?php echo strtoupper($lg_point);?></td>
  </tr>
<?php
	$sql="select * from koq_stu where uid='$uid' order by year desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$nam=$row['koq_name'];
		$cod=$row['koq_code'];
		$typ=$row['koq_type'];
		$pos=$row['pos'];
		$par=$row['par'];
		$ach=$row['ach'];
		$ses=$row['year'];
		$attv=$row['att_val'];
		$attf=$row['att_full'];
		$atts=$row['att_stu'];
		
		$pv=$row['pos_val'];
		$ptv=$row['par_val'];
		$av=$row['ach_val'];
		$atv=$row['att_val'];
		$total=$row['total_val'];
		
		$sql="select * from type where grp='koq_grp' and val='$typ'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$grp=$row2['prm'];
		//echo $sql;
		
	
		if(($q++%2)==0)
				$bg="#FAFAFA";
		else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder"><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)"></td>
    <td id="myborder" align="center"><?php echo "$ses";?></td>
	<td id="myborder"><?php echo "$grp";?></td>
    <td id="myborder"><a href="#" onClick="document.myform.id.value=<?php echo $xid;?>;document.myform.submit();"><?php echo "$nam";?></a></td>
    <td id="myborder"><?php echo "$atts / $attf";?></td>
	<td id="myborder"><?php echo "$pos";?></td>
	<td id="myborder"><?php echo "$par";?></td>
    <td id="myborder"><?php echo "$ach";?></td>
	<td id="myborder" align="center"><?php echo "$total";?></td>
  </tr>
<?php } ?>
</table>
<!-- 
<div id="mytitlebg">ULASAN GURU KOKURIKULUM</div>
<textarea name="ulasankoq" rows="5" cols="60" value<?php echo $ulasankoq;?> </textarea>
<input type="button" value="Save" onClick="process_form('saveulasan'">
 -->

</div><!-- story -->
</div><!-- content -->

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->