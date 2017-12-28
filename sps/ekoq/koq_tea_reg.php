<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
			
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];
    $year1=$_REQUEST['year'];
    $year=$_POST['year'];
   if($year=="")
		    $year=$_REQUEST['year'];
		    
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
		$sql="select * from usr where uid='$uid'";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $xname=$row['name'];
		$xic=$row['ic'];
		$file=$row['file'];
	}
		
		
	if($op=='save'){
		$sql="select * from koq where grp='koq' and prm='$kelab' and (sid=0 or sid=$sid)"; 	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$kname=$row['prm'];
		$kcode=$row['code'];
		$ktype=$row['val'];

		if($id>0)
			$sql="update koq_tea set dts='$dts',dte='$dte',koq_name='$kname',koq_type=$ktype,koq_code='$kcode',pos='$jawatan',sta=$status,adm='$username',dt=now() where id=$id";
		else
			$sql="insert into koq_tea(sid,uid,dts,dte,koq_name,koq_type,koq_code,pos,sta,adm,dt)value($sid,'$uid','$dts','$dte','$kname',$ktype,'$kcode','$jawatan',$status,'$username',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
		$kelab="";
		$jawatan="";
		$status="";
		$id="";
	}
	if($op=='update'){
		$sql="select * from koq_tea where id=$id"; 	
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
		      	$sql="delete from koq_tea where id=$del[$i]";
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
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include("$MYOBJ/calender/calender.htm")?>
<script language="javascript">

function process_form(action,id){
	var ret="";
	var cflag=false;
	if(action=='new'){
		document.myform.kelab[0].value="";
		document.myform.kelab[0].text="- Select -";
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
			alert("Select the activities..");
			document.myform.kelab.focus();
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
	<input type="hidden" name="op">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="year" value="<?php echo $year1;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<!-- 
<a href="#" onClick="process_form('new'),show('panelform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
 -->
<a href="#" onClick="process_form('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="javascript: href='koq_tea_reg.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>&year=<?php echo $year;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitlebg"><?php echo strtoupper("$lg_cocurriculum");?> - <?php echo strtoupper($lg_teacher);?> <?php echo $f;?></div>

	<table width="100%" >
      <tr>
        <td width="14%"><?php echo strtoupper("$lg_name");?></td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_staff_id");?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper("$lg_ic_number");?></td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_school");?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper("$lg_session");?></td>
        <td>:</td>
        <td><?php echo "$year1";?> </td>
      </tr>
    </table>


<div id="panelform" style="display:<?php if($id=="") echo "none";else echo "block";?>">
<div id="mytitle"><?php echo strtoupper("$lg_update");?></div>
<table width="100%" >
  <tr>
    <td width="15%"><?php echo strtoupper("$lg_activity");?></td>
    <td width="1%">:</td>
    <td width="74%">
	<select name="kelab">
 <?php	
      		if($kelab=="")
				echo "<option value=\"\">- $lg_select -</option>";
			else
				echo "<option value=\"$kelab\">$kelab</option>";
			$sql="select * from type where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by idx,prm"; 	
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$s=$row['prm'];
                echo "<option value=\"$s\">$s</option>";
            }
?>
            </select>
		</td>
  </tr>
 <!-- 
  <tr>
  <td width="15%"><?php echo strtoupper("$lg_position");?></td>
    <td width="1%">:</td>
    <td width="74%">
	<select name="jawatan">
 <?php	
      		if($jawatan!="")
				echo "<option value=\"$jawatan\">$jawatan</option>";
			$sql="select * from type where grp='koq_jaw_guru' and prm!='$jawatan' order by idx"; 	
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>
            </select>
		</td>
  </tr>
  -->
  <tr>
    <td><?php echo strtoupper("$lg_start");?></td>
    <td>:</td>
    <td><input name="rdate" type="text" id="rdate" value="<?php echo "$dts";?>" size="8">
        <input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('rdate')"></td>
  </tr>
 <!-- 
   <tr>
    <td><?php echo strtoupper("$lg_status");?></td>
    <td>:</td>
    <td>
		<select name="status">
 <?php	
      		if(($status=="")||($status=="0")){
				echo "<option value=\"0\">$lg_activate</option>";
				echo "<option value=\"1\">$lg_end</option>";
			}
			else{
				echo "<option value=\"1\">$lg_end</option>";
				echo "<option value=\"0\">$lg_activate</option>";
            }
?>
        </select>
	</td>
	</tr>
 -->
  <tr>
    <td><?php echo strtoupper("$lg_end");?></td>
    <td>:</td>
    <td><input name="edate" type="text" id="edate" value="<?php echo "$dte";?>" size="8">
                    <input name=" cal2" type="button" id=" cal2" value="-" onClick="c2.popup('edate')"></td>
  </tr>
</table>
</div><!-- end block -->

<table width="100%">
  <tr>
    <td id="mytabletitle" width="2%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper("$lg_code");?></td>
	<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper("$lg_year");?></td>
    <td id="mytabletitle" width="80%"><?php echo strtoupper("$lg_activity");?></td>
  </tr>
<?php
	$sql="select * from koq_tea where uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$nam=$row['koq_name'];
		$cod=$row['koq_code'];
		$typ=$row['koq_type'];
		$pos=$row['pos'];
		$sta=$row['sta'];
		$dts=$row['dts'];
		$dte=$row['dte'];
		$y=$row['year'];
		if($dte=="0000-00-00")
			$dte="";
		if(($q++)%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
?>
  <tr <?php echo "$bg";?>>
		<td><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)"></td>
		<td align="center"><?php echo "$cod";?></td>
		<td align="center"><?php echo "$year1";?></td>
		<td><a href="#" onClick="process_form('update',<?php echo $xid;?>)"><?php echo "$nam";?></a></td>
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