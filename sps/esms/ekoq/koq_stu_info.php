<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
			
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];
    $year=date('Y');
	$dts=$_POST['rdate'];
	if($dts=="")
		$dts=date('Y-m-d');
	$dte=$_POST['edate'];
	$kelab=$_POST['kelab'];
	$jawatan=$_POST['jawatan'];
	$status=$_POST['status'];
	if($status=="")
		$status=0;
	$op=$_POST['op'];
	$id=$_POST['id'];
	$del=$_POST['del'];
	$uid=$_REQUEST['uid'];
	if($uid!=""){
		$sql="select * from stu where sch_id=$sid and uid='$uid'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $xname=$row['name'];
		$xic=$row['ic'];
		$file=$row['file'];
			
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$cname=$row2['cls_name'];
	}
		
		
	if($op=='save'){
		$sql="select * from type where grp='koq' and prm='$kelab' and (sid=0 or sid=$sid)"; 	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$kname=$row['prm'];
		$kcode=$row['code'];
		$ktype=$row['val'];

		if($id>0)
			$sql="update koq_stu set dts='$dts',dte='$dte',koq_name='$kname',koq_type=$ktype,koq_code='$kcode',pos='$jawatan',sta=$status,adm='$username',dt=now() where id=$id";
		else
			$sql="insert into koq_stu(sid,uid,dts,dte,koq_name,koq_type,koq_code,pos,sta,adm,dt)value($sid,'$uid','$dts','$dte','$kname',$ktype,'$kcode','$jawatan',$status,'$username',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
		$kelab="";
		$jawatan="";
		$status="";
		$id="";
	}
	if($op=='update'){
		$sql="select * from koq_stu where id=$id"; 	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$kelab=$row['koq_name'];
		$jawatan=$row['pos'];
		$status=$row['sta'];
		$dts=$row['dts'];
		$dte=$row['dte'];
		if($dte=="0000-00-00")
			$dte=""; 
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

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==0)
		$MAXLINE=30;
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
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action,id){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.kelab[0].value="";
		document.myform.kelab[0].text="- Pilih -";
		document.myform.id.value="";
		return;
	}
	if(action=='update'){
		document.myform.id.value=id;
		document.myform.op.value=action;
		document.myform.submit();
	}
	if(action=='save'){
		if(document.myform.kelab.value==""){
			alert("Sila pilih aktiviti");
			document.myform.kelab.focus();
			return;
		}
		if(document.myform.jawatan.value==""){
			alert("Sila pilih jawatan");
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

</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../ekoq/hos_stu_reg">
	<input type="hidden" name="isprint">
	<input type="hidden" name="op">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="javascript: href='stu_info.php?p=../ekoq/koq_stu_info&sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>

</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitle">
  <tr>
    <td width="100%" align="left">KOKURIKULUM - MAKLUMAT PELAJAR <?php echo $f;?></td>
  </tr>
</table>
<table width="100%" border="0" id="mytitle" style="background:none">
  <tr>
<?php if($file!=""){?>
  	<td width="5%" align="center" valign="top">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="72" id="myborderfull" >
	</td>
<?php } ?>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%">Nama</td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td>ID</td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td>KP</td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo "$lg_sekolah";?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td>Kelas</td>
        <td>:</td>
        <td><?php echo "$cname/$year";?> </td>
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


<table width="100%">
  <tr>
    <td id="mytabletitle" width="3%">Kod</td>
    <td id="mytabletitle" width="30%">Aktiviti</td>
    <td id="mytabletitle" width="10%">T.Daftar</td>
    <td id="mytabletitle" width="10%">T.Tamat</td>
    <td id="mytabletitle" width="30%">Jawatan</td>
	<td id="mytabletitle" width="10%">Status</td>
  </tr>
<?php
	$sql="select * from koq_stu where uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$nam=$row['koq_name'];
		$cod=$row['koq_code'];
		$typ=$row['koq_type'];
		$pos=$row['pos'];
		$sta=$row['sta'];
		if($sta=="1")
			$sta="TAMAT";
		else
			$sta="AKTIF";
		$dts=$row['dts'];
		$dte=$row['dte'];
		if($dte=="0000-00-00")
			$dte="";
		if(($q++)%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
?>
  <tr <?php echo "$bg";?>>
    <td><?php echo "$cod";?></td>
    <td><a href="#" onClick="process_form('update',<?php echo $xid;?>)"><?php echo "$nam";?></a></td>
    <td><?php echo "$dts";?></td>
    <td><?php echo "$dte";?></td>
    <td><?php echo "$pos";?></td>
    <td><?php echo "$sta";?></td>
  </tr>
<?php } ?>
</table>



</div><!-- story -->
</div><!-- content -->

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->