<?php
//09/07/10 4.1.0 - clearwindow
$vmod="v5.0.0";
$vdate="09/07/10";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("eregister",1);
$adm = $_SESSION['username'];

		$id=$_REQUEST['id'];
		if($id!=""){
			$sql="select * from stureg where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$name=strtoupper(stripslashes($row['name']));
			$ic=$row['ic'];
			$anaknegeri=$row['anaknegeri'];
			$upsrresult=$row['upsr_result'];
			$test1=$row['test1'];
			$test2=$row['test2'];
			$ekomark=$row['ekomark'];
		}
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date('Y');
		
		$exam=$_REQUEST['exam'];
		$op=$_POST['op'];
		if($op=="save"){
			$sub=$_POST['sub'];
			$val=$_POST['val'];
			$jum=$_POST['jum'];
			$des=$_POST['des'];
			$sql="delete from stureg_exam where ic='$ic' and exam='$exam'";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error()); 
			for($i=0;$i<count($sub);$i++){
				$val=$_POST["val$i"];
				$sql="insert into stureg_exam (ic,sid,exam,sub,val,adm,ts,des) values ('$ic','$sid','$exam','$sub[$i]','$val[$i]','$adm',now(),'$des[$i]')";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			}
			if($exam=='TEMUDUGA')
				$sql="update stureg set test1='$jum' where id=$id";
			else
				$sql="update stureg set test2='$jum' where id=$id";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error()); 
			$f="<font color=blue>(Successfully Update)</font>";
		}
		
		$sql="select * from stureg where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$name=strtoupper(stripslashes($row['name']));
			$ic=$row['ic'];
			$anaknegeri=$row['anaknegeri'];
			$upsrresult=$row['upsr_result'];
			$test1=$row['test1'];
			$test2=$row['test2'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<SCRIPT LANGUAGE="JavaScript">
function process_form(op){
	ret = confirm("Are you want to save?");
    if (ret == true){
		document.myform.op.value=op;
		document.myform.submit();
    }
}
function kira(ele,no){
	var x=0;
	var y=0;
	var sum=0;
	/**
	y=ele.value;
	x=document.myform.jum.value;
	sum=parseFloat(x)+parseFloat(y);
	document.myform.jum.value=sum.toFixed(2);
	*/
	for(i=0;i<no;i++){
		var name='val'+i;
		x=document.myform.elements[name].value;
		sum=sum+parseFloat(x);
	}
	document.myform.jum.value=sum.toFixed(2);
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<div id="content">
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="stu_test">
	<input type="hidden" name="op">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="hidden" name="exam" value="<?php echo $exam;?>">
<div id="mypanel" class="printhidden">
<div id="mymenu" align="center">
<?php if(is_verify('ADMIN|AKADEMIK|HEP')){?>
	<a href="#" onClick="process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<?php } ?>
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>

</div>
<div id="story">
<div id="mytitle"> <?php echo strtoupper($lg_interview);?> <?php echo "$f";?></div>
<table width="100%" border="0" id="mytitle" style="background:none">
  <tr>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="30%"><?php echo strtoupper($lg_name);?></td>
        <td width="2%">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("NO AKTA LAHIR / PASSPORT ");?></td>
        <td>:</td>
        <td><?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("ASAL SEKOLAH");?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>

    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%">
  <!-- <tr>
        <td width="24%">UPSR</td>
        <td width="1%">:</td>
        <td width="75%"><?php echo $upsrresult;?></td>
      </tr> 
	  <tr>
        <td width="24%"><?php echo strtoupper($ANAK_NEGERI);?></td>
        <td width="1%">:</td>
        <td width="75%"><?php echo $anaknegeri;?></td>
      </tr> -->
	  <tr>
        <td width="24%"><?php echo strtoupper($lg_interview);?></td>
        <td width="1%">:</td>
        <td width="75%"><?php echo $test1;?></td>
      </tr>
	  <tr>
        <td width="24%"><?php echo strtoupper($lg_seminar);?></td>
        <td width="1%">:</td>
        <td width="75%"><?php echo $test2;?></td>
      </tr>
	 
    </table>
 	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" style="font-size:18px " cellpadding="5">
  <tr>
    <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="20%" ><?php echo "UJIAN TES MASUK SEKOLAH" ;?></td>
    <!--<td id="mytabletitle" width="10%" ><?php echo strtoupper($lg_mark);?></td>-->
	<td id="mytabletitle" width="65%" ><?php echo strtoupper($lg_remark);?></td>
  </tr>
  <?php	
  	$totalval=0;
	$sql="select * from type where grp='examtemuduga' and code='$exam'";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$numsub=mysql_num_rows($res);
	while($row=mysql_fetch_assoc($res)){
		$subname=$row['prm'];
		$subcode=$row['code'];
		$subval=$row['val'];
		$etc=$row['etc'];
		$des=$row['des'];
		
		$val=0;
		$sql="select * from stureg_exam where ic='$ic' and exam='$exam' and sub='$subname'";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$val=$row2['val'];
			$des=$row2['des'];
		}
		if($subname=="SOSIO EKONOMI")
				$val=$ekomark;
		
		$totalval=$totalval+$val;
		if($q++%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
?>
  <tr <?php echo "$bg";?>>
  	<td align="center"><?php echo "$q";?></td>
    <td ><?php echo "$subname";?></td>
    <td >
	<input type="hidden" name="sub[]" value="<?php echo $subname;?>">
	<input type="text" name="des[]" style="width:70%" value="<?php echo $des;?>">
	</td>
	<!--<td><font size="3"><?php echo $etc;?></font></td>-->
  </tr>
<?php } ?>
  <tr><!--
  	 <td></td>
     <td><strong><?php echo strtoupper($lg_total_mark);?></strong></td>
     <td><input type="text" name="jum" id="jum" size="5" value="<?php printf("%.02f",$totalval);?>" readonly style="font-size:20px; font-weight:bold; color:#330099"></td>
	 <td></td>-->
  </tr>
</table>


</div></div>
</form>
</body>
</html>
