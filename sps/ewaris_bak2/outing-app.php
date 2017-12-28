<?php
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();


		$uid=$_SESSION['uid'];
		$sql="select * from stu where uid='$uid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
        $xname=$row['name'];
		$xic=$row['ic'];
		$sid=$row['sch_id'];
		$img=$row['file'];
		$p1name=$row['p1name'];
		$p2name=$row['p2name'];
		$mel=$row['mel'];
		$hp=$row['hp'];
			
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sname=$row['name'];
			
		$year=date('Y');
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$cname=$row2['cls_name'];
			
			
		$blk=$rom=$lvl=$bed="";
		$sql="select * from hos_room where uid='$uid'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$blk=$row2['block'];
			$lvl=$row2['level'];
			$rom=$row2['roomno'];
			$bed=$row2['stuno'];
		}

		
   
   	$id=$_REQUEST['id'];
	$operation=$_REQUEST['operation'];
	if($operation!=""){
		$sdate=$_POST['sdate'];
		$edate=$_POST['edate'];
		$subtype=$_POST['subtype'];
		$relation=$_POST['relation'];
		$takenby=$_POST['takenby'];
		$rson=addslashes($_POST['rson']);
		$addr=addslashes($_POST['addr']);
		$status=$_POST['status'];
		$operation=$_POST['operation'];
		$confirm=$_POST['confirm'];
		
		if($id==""){
			$confirm=1;
			$sql="insert into outing (uid,date,sdate,edate,rson,addr,subtype,takenby,relation,confirm,sid) 
				  values ('$uid',now(),'$sdate','$edate','$rson','$addr','$subtype','$takenby','$relation',$confirm,$sid)";
			mysql_query($sql)or die("$sql query failed:".mysql_error());
			$id=mysql_insert_id();
				  
		}else{
	       $sql="update outing set confirm='$confirm',sdate='$sdate',edate='$edate',rson='$rson',addr='$addr',
		   	subtype='$subtype', takenby='$takenby', relation='$relation' where id=$id";
		   mysql_query($sql)or die("$sql query failed:".mysql_error());	
		}
		if($confirm)
			$f="<font color=blue>&lt; YOUR APPLICATION HAVE BEEN SAVE &gt;</font>";
		else
			$f="<font color=red>&lt; THIS APPLICATION HAVE CANCEL &gt;</font>";
	}
		
	if($id!=""){
		$sql="select * from outing where id=$id";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$date=$row['date'];
			$sdate=$row['sdate']; 
			$edate=$row['edate'];
			$jumcuti=$row['days'];
			$typecuti=$row['type'];
			$rson=$row['rson'];
			$addr=$row['addr'];
			$ganti=$row['ganti'];
			$subtype=$row['subtype'];
			$takenby=$row['takenby'];
			$relation=$row['relation'];
			$confirm=$row['confirm'];
			$f1=$row['file1'];
		}
	}
			
	/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";	


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<script language="JavaScript">
function process_form(op){
	var ret="";
	var cflag=false;

		if(document.myform.sdate.value==""){
			alert("Please select start date");
			document.myform.sdate.focus();
			return;
		}
		if(document.myform.edate.value==""){
			alert("Please enter end date");
			document.myform.edate.focus();
			return;
		}
		if(document.myform.addr.value==""){
			alert("Please enter address during the leave");
			document.myform.addr.focus();
			return;
		}
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.operation.value=op;
			document.myform.submit();
		}			
		return;
	 
}

function check_date(){
	if(document.myform.edate.value < document.myform.sdate.value){
		alert("Maaf. Tarikh tidak sah");
		document.myform.edate.value="";
		document.myform.cal.focus();
		return;
	}
	else{
		document.myform.submit();
	}
}


function pop_cal(url,type) 
{ 
	cal = window.open(url,"newwindow","HEIGHT=300,WIDTH=400,scrollbars=no,status=no,resizable=no,top=0,toolbar=no");
	cal.focus();
}

function process_batal(op){
		ret = confirm("Are you sure want to cancel this application??");
		if (ret == true){
			document.myform.operation.value=op;
			document.myform.confirm.value=0;
			document.myform.submit();
		}			
		return;
}
</script>

</head>
<body>
<form name="myform" method="post" enctype="multipart/form-data">
    <input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="confirm"  value="<?php echo $confirm;?>">
    <input type="hidden" name="operation" >
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
</div>
</div>
<div id="story">

<div id="mytitle2"><?php echo $lg_student_information;?>  <?php echo $f;?></div>
<table width="100%">
  <tr>
<td width="10%" valign="top" id="mybotder" align="center" style="background-color:#CCC">
		<?php if(($img!="")&&(file_exists("$dir_image_student$img"))){?>
				<img src="<?php echo "$dir_image_student$img";?>" width="75" height="75">
		<?php } else echo "Picture";?>
	 </td>
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
			<td><?php echo "$uid";?> </td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_school);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$sname");?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$cname/$year");?> </td>
		  </tr>
           <tr>
			<td><?php echo strtoupper($lg_hostel);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$blk-$lvl-$rom-$bed");?> </td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%">
		  <tr>
			<td width="24%" align="right"><?php echo strtoupper($lg_father);?></td>
			<td width="1%">:</td>
			<td width="75%"><?php echo $p1name;?></td>
		  </tr>
		   <tr>
			<td align="right"><?php echo strtoupper($lg_mother);?></td>
			<td>:</td>
			<td><?php echo $p2name;?></td>
		  </tr>
		  <tr>
			<td align="right"><?php echo strtoupper($lg_email);?></td>
			<td>:</td>
			<td><?php echo $mel;?></td>
		  </tr>
		  <tr>
			<td align="right"><?php echo strtoupper($lg_hp);?></td>
			<td>:</td>
			<td><?php echo $hp;?></td>
		  </tr>
		</table>
 	</td>
  </tr>
</table>

<div id="mytitle2">Outting Information</div>
<table width="100%" style="font-size:12px" cellspacing="0">
            <tr>
              <td width="15%" id="myborder"> Tarikh Keluar</td>
              <td>
			  <input name="sdate" type="text" id="sdate" size="12" readonly value="<?php echo $sdate;?>">
              <input name="cal1" type="button" id=" cal" value="-" onClick="pop_cal('../inc/mycal.php?tar=sdate',0)">
              </td>
            </tr>
			<tr>
              <td width="10%" id="myborder"> Tarikh Balik</td>
              <td>
			  <input name="edate" type="text" id="edate" size="12" readonly onChange="check_date()" value="<?php echo $edate;?>">
              <input name="cal2" type="button" id="cal" value="-" onClick="pop_cal('../inc/mycal.php?tar=edate',0)" onChange="check_date()">
			  </td>
            </tr>

            <tr>
              <td width="10%" valign="top" id="myborder"><?php echo $lg_reason;?></td>
              <td><textarea name="rson" cols="40" rows="3" id="rson"><?php echo $rson;?></textarea></td>
            </tr>
            <tr>
              <td width="10%" valign="top" id="myborder"><?php echo $lg_address;?></td>
              <td><textarea name="addr" cols="40" rows="3" id="addr"><?php echo $addr;?></textarea></td>
            </tr>
             <tr>
              <td width="10%" valign="top" id="myborder">Di Ambil Oleh</td>
              <td>
              <label style="cursor:pointer"><input type="radio" name="subtype" value="0" <?php if($subtype=="0")echo "checked";?>>
              Saya sendiri akan datang mengambil.</label><br>
              <label style="cursor:pointer"><input type="radio" name="subtype" value="1" <?php if($subtype=="1")echo "checked";?>>
              Saya membenarkan anak saya pulang sendiri.</label><br>
              <label style="cursor:pointer"><input type="radio" name="subtype" value="2" <?php if($subtype=="2")echo "checked";?>> 
              Wakil saya datang mengambil.</label><br>
			Nama Wakil<input type="text" name="takenby" value="<?php echo $takenby;?>"> 
            Hubungan <input type="text" name="relation" value="<?php echo $relation;?>">
              
              </td>
            </tr>
			
          </table>
<br>
<?php if($id>0){?>
<div align="center">
<a href="#" onClick="process_batal('save')"><font size="2" color="#FF0000">
<strong>&lt;&lt;<?php echo $lg_cancel_this_request;?>&gt;&gt;</strong></font></a>	
</div>
<?php } ?>
</body>
</div></div>
</form>
</html>
