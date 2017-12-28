<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN');
	$username = $_SESSION['username'];
	$id=$_REQUEST['fid'];
	$fee=$_REQUEST['fee'];
	$sid=$_REQUEST['sid'];
	$op=$_REQUEST['op'];
	$year=$_REQUEST['year'];
	if($year==""){
			$sql="select * from type where grp='session' and prm!='$sesyear' order by prm desc";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);       
	     	$year=$row['prm'];
	}
if($op==""){
	
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($id!=""){
		$sql="select * from feeset where id='$id'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$fee=$row['name'];
		$sid=$row['sch_id'];
	}
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sname=stripslashes($row['name']);
		$stype=$row['level'];
		mysql_free_result($res);					  
	}
	
		
	if($fee!=""){
		$sql="select * from feeset where name='$fee' and sch_id=$sid and year='$year'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$id=$row['id'];
		$name=$row['name'];
		$val=$row['val'];
		$yatim=$row['yatim'];
		$staff=$row['staff'];
		$kawasan=$row['kawasan'];
		$xpelajar=$row['xpelajar'];
		$asrama=$row['asrama'];
		$inter=$row['inter'];
		$a2=$row['a2'];
		$a3=$row['a3'];
		$ax=$row['ax'];
		$fnew=$row['new'];
		$etc=$row['etc'];
		$fsex=$row['issex'];
		$flvl=$row['islvl'];
		$fcls=$row['iscls'];
		$idx=$row['idx'];
	}
}
else{

	$del=$_POST['del'];
 	$fee=$_POST['fee'];
	$sid=$_POST['sid'];
	$val=$_POST['val']; if($val=="") $val=-1;
	$ytm=$_POST['yatim']; if($ytm=="") $ytm=-1;
	$stf=$_POST['staff']; if($stf=="") $stf=-1;
	$kaw=$_POST['kawasan']; if($kaw=="") $kaw=-1;
	$xp=$_POST['xpelajar']; if($xp=="") $xp=-1;
	$a2=$_POST['a2']; if($a2=="") $a2=-1;
	$a3=$_POST['a3']; if($a3=="") $a3=-1;
	$ax=$_POST['ax']; if($ax=="") $ax=-1;
	$fnew=$_POST['fnew']; if($fnew=="") $fnew=-1;
	$etc=$_POST['etc']; if($etc=="") $etc=-1;
	$asrama=$_POST['asrama']; if($asrama=="") $asrama=-1;
	$inter=$_POST['inter']; if($inter=="") $inter=-1;
	$fsex=$_POST['sex'];
	$flvl=$_POST['lvl'];
	$fcls=$_POST['cls'];
	$idx=$_POST['idx']; if($idx=="") $idx=0;
	$id=$_POST['id'];
	$op=$_POST['op'];
	if($op=='delete'){	
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from feeset where id=$del[$i]";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		echo "<script language=\"javascript\">location.href='prmfee.php?sid=$sid&year=$year'</script>;";
	}
	elseif($op=='create'){
		//$lastyear=$year-1;
		list($year1,$year2)=explode("/",$year);
		$year1=$year1-1;$year2=$year2-1;
		$lastyear="$year1/$year2";
		$sql="select * from feeset where sch_id='$sid' and year='$lastyear' order by idx, id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xid=$row['id'];
			$sql="insert into feeset(sch_id,name,type,val,yatim,staff,kawasan,xpelajar,a2,a3,ax,new,etc,iscls,islvl,issex,idx,special,inter,asrama,year,adm,ts) 
			  	select sch_id,name,type,val,yatim,staff,kawasan,xpelajar,a2,a3,ax,new,etc,iscls,islvl,issex,idx,special,inter,asrama,'$year','$username',now() from feeset where id=$xid";
			mysql_query($sql)or die("Failed: $sql >>".mysql_error());
		}
		echo "<script language=\"javascript\">location.href='prmfee.php?sid=$sid&year=$year'</script>;";
	}
	else{
		if($id!="")
			$sql="update feeset set val=$val,yatim=$ytm,staff=$stf,kawasan=$kaw,xpelajar=$xp,a2=$a2,a3=$a3,ax=$ax,new=$fnew,
			issex='$fsex',islvl='$flvl',iscls='$fcls',etc=$etc,asrama=$asrama,inter=$inter,
			year='$year',idx=$idx,adm='$username',ts=now() where id=$id";
		else
      		$sql="insert into feeset (sch_id,name,val,yatim,staff,kawasan,xpelajar,a2,a3,ax,new,issex,islvl,iscls,etc,asrama,inter,year,idx,adm,ts) values 
			($sid,'$fee',$val,$ytm,$stf,$kaw,$xp,$a2,$a3,$ax,$fnew,'$fsex','$flvl','$fcls',$etc,$asrama,$inter,'$year',$idx,'$username',now())";
		
		mysql_query($sql)or die("Failed: $sql >>".mysql_error());
		echo "<script language=\"javascript\">location.href='prmfee.php?sid=$sid&year=$year'</script>;";
	}
	
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.id.value="";
		document.myform.fee.options[0] = new Option('- Select Fee -', '');
		document.myform.fee.options[0].selected=true;
		document.myform.val.value="";
		document.myform.yatim.value="";
		document.myform.staff.value="";
		document.myform.xpelajar.value="";
		document.myform.kawasan.value="";
		document.myform.cls.value="";
		document.myform.lvl.value="";
		document.myform.sex.value="";
		document.myform.fnew.value="";
		document.myform.etc.value="";
		document.myform.a2.value="";
		document.myform.a3.value="";
		document.myform.ax.value="";
		document.myform.asrama.value="";
		document.myform.inter.value="";
		document.myform.idx.value="";
		return;
	}
	if(action=='update'){
		if(document.myform.sid.value=="0"){
			alert("Sila pilih sekolah");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.fee.value==""){
			alert("Sila pilih jenis yuran");
			document.myform.fee.focus();
			return;
		}
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	if(action=='create'){
		ret = confirm("Are you sure want to Create FEE??");
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

</script>

<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="process_form('new');show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
	<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
	<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
	<a href="#" onClick="process_form('create')" id="mymenuitem"><img src="../img/flash.png"><br>Create</a>
	<a href="#" onClick="hide('panelform');process_form('new');document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<form name=myform method=post  action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="typ" value="<?php echo $typ;?>">
	<input type="hidden" name="op">
<div align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
<br>
<br>
<select name="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }					  
?>
      </select>
	<select name="sid" id="sid" onchange="process_form('new');document.myform.submit()">
      <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['name']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}					  
			
?>
    </select>
</div>
</div> <!-- end mycontrolpalel-->

<div id="story">
<div id="panelform" style="display:<?php if($fee=="") echo "none";else echo "block";?> ">
<div id="mytitle">
	<div id="myclick" onClick="hide('panelform');"><img src="../img/icon_minimize.gif" id="mycontrolicon">KONFIGURASI YURAN</div>&nbsp;
</div>
	<table width="100%" border="0"  id="mytable" >
      <tr>
        <td width="16%">Parameter</td>
        <td width="1%">:</td>
        <td width="83%">
		<select name="fee" id="fee" onchange="document.myform.submit();" <?php if($sid<1) echo "disabled";?> >
          <?php	
      		if($fee=="")
            	echo "<option value=\"\">- Select Fee -</option>";
			else
                echo "<option value=\"$fee\">$fee</option>";
				//$sql="select * from type where grp='yuran' and prm!='$fee' order by idx asc,id asc";
				$sql="select * from type where grp='yuran' order by idx asc,id asc";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
							echo "<option value=\"$s\">$s</option>";
				}
				mysql_free_result($res);	  
			
		?>
        </select></td>
      </tr>
      <tr>
        <td>FEE CHARGE</td>
        <td>:</td>
        <td><input type="text" name="val" value="<?php echo "$val";?>"></td>
      </tr>
      <tr>
        <td><?php echo $lg_orphan;?> </td>
        <td>:</td>
        <td><input type="text" name="yatim" value="<?php echo "$yatim";?>"></td>
      </tr>
      <tr>
        <td><?php echo $lg_staff_child;?></td>
        <td>:</td>
        <td><input type="text" name="staff" value="<?php echo "$staff";?>"></td>
      </tr>
      <tr>
        <td><?php echo $lg_kariah;?></td>
        <td>:</td>
        <td><input type="text" name="kawasan" value="<?php echo "$kawasan";?>"></td>
      </tr>
      <tr>
        <td><?php echo $lg_xprimary;?></td>
        <td>:</td>
        <td><input type="text" name="xpelajar" value="<?php echo "$xpelajar";?>"></td>
      </tr>
      <tr>
        <td>Second Child</td>
        <td>:</td>
        <td><input type="text" name="a2" value="<?php echo "$a2";?>"></td>
      </tr>
      <tr>
        <td>Third Child</td>
        <td>:</td>
        <td><input type="text" name="a3" value="<?php echo "$a3";?>"></td>
      </tr>
      <tr>
        <td>Next Child</td>
        <td>:</td>
        <td><input type="text" name="ax" value="<?php echo "$ax";?>"></td>
      </tr>
	  <tr>
        <td>New Student</td>
        <td>:</td>
        <td><input type="text" name="fnew" value="<?php echo "$fnew";?>"></td>
      </tr>
	  <tr>
        <td>International</td>
        <td>:</td>
        <td><input type="text" name="inter" value="<?php echo "$inter";?>"></td>
      </tr>
	  <tr>
        <td>Hostel</td>
        <td>:</td>
        <td><input type="text" name="asrama" value="<?php echo "$asrama";?>"></td>
      </tr>
	  <tr>
        <td>Nilai Bersyarat</td>
        <td>:</td>
        <td><input type="text" name="etc" value="<?php echo "$etc";?>"></td>
      </tr>
	   <tr>
        <td>GENDER</td>
        <td>:</td>
        <td><input type="text" name="sex" value="<?php echo "$fsex";?>" size="64"></td>
      </tr>
	   <tr>
        <td>LEVEL</td>
        <td>:</td>
        <td><input type="text" name="lvl" value="<?php echo "$flvl";?>" size="64"></td>
      </tr>
	  <tr>
        <td>KELAS</td>
        <td>:</td>
        <td><input type="text" name="cls" value="<?php echo "$fcls";?>" size="64"></td>
      </tr>
	  <tr>
        <td>Index </td>
        <td>:</td>
        <td><input type="text" name="idx" value="<?php echo "$idx";?>"></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    	
</div>

<div id="mytitle">FEE LIST</div>
	<table width="100%" cellspacing="0">
      <tr>
      	<td id="mytabletitle" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td id="mytabletitle" width="20%">Parameter</td>
		<td id="mytabletitle" width="5%" align="center">Default<br>Value</td>
		<td id="mytabletitle" width="5%" align="center"><?php echo $lg_orphan;?></td>
		<td id="mytabletitle" width="5%" align="center"><?php echo $lg_staff_child;?></td>
		<td id="mytabletitle" width="5%" align="center"><?php echo $lg_kariah;?></td>
		<td id="mytabletitle" width="5%" align="center"><?php echo $lg_xprimary;?></td>
		<td id="mytabletitle" width="5%" align="center">2nd Child</td>
		<td id="mytabletitle" width="5%" align="center">3rd Child</td>
		<td id="mytabletitle" width="5%" align="center">Next Child</td>
		<td id="mytabletitle" width="5%" align="center">New Student</td>
		<td id="mytabletitle" width="5%" align="center">International</td>
		<td id="mytabletitle" width="5%" align="center">Hostel</td>
		<td id="mytabletitle" width="5%" align="center">Nilai<br>Syarat</td>
		<td id="mytabletitle" width="5%" align="center">GENDER</td>
		<td id="mytabletitle" width="5%" align="center">LEVEL</td>
		<td id="mytabletitle" width="5%" align="center">KELAS</td>
		<td id="mytabletitle" width="5%" align="center">Index</td>
      </tr>
<?php
	$sql="select * from feeset where sch_id='$sid' and year='$year' order by idx, id";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=0;
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$name=$row['name'];
		$val=$row['val'];
		$yatim=$row['yatim'];
		$staff=$row['staff'];
		$kawasan=$row['kawasan'];
		$xpelajar=$row['xpelajar'];
		$asrama=$row['asrama'];
		$inter=$row['inter'];
		$a2=$row['a2'];
		$a3=$row['a3'];
		$ax=$row['ax'];
		
		$fnew=$row['new'];
		$etc=$row['etc'];
		$fsex=$row['issex'];
		$flvl=$row['islvl'];
		$fcls=$row['iscls'];
		
		$idx=$row['idx'];
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>

<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder"><input type=checkbox name=del[] value="<?php echo $xid;?>" onClick="check(0)"></td>
		<td id="myborder"><a href="prmfee.php?<?php echo "fid=$xid&year=$year";?>" title="edit" style="text-decoration:underline"><?php echo $name;?></a></td>
		<td id="myborder" align="center"><?php echo $val;?></td>
		<td id="myborder" align="center"><?php echo $yatim;?></td>
		<td id="myborder" align="center"><?php echo $staff;?></td>
		<td id="myborder" align="center"><?php echo $kawasan;?></td>
		<td id="myborder" align="center"><?php echo $xpelajar;?></td>
		<td id="myborder" align="center"><?php echo $a2;?></td>
		<td id="myborder" align="center"><?php echo $a3;?></td>
		<td id="myborder" align="center"><?php echo $ax;?></td>
		<td id="myborder" align="center"><?php echo $fnew;?></td>
		<td id="myborder" align="center"><?php echo $inter;?></td>
		<td id="myborder" align="center"><?php echo $asrama;?></td>
		<td id="myborder" align="center"><?php echo $etc;?></td>
		<td id="myborder" align="center"><?php echo $fsex;?></td>
		<td id="myborder" align="center"><?php echo $flvl;?></td>
		<td id="myborder" align="center"><?php echo $fcls;?></td>
		<td id="myborder" align="center"><?php echo $idx;?></td>
</tr>
<?php	}?>
      </table>
</form>
</div></div>
</body>
</html>
