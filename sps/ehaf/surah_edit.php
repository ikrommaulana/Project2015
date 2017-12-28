<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$del=$_POST['del'];
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=addslashes($row['name']);
            mysql_free_result($res);					  
		}
		
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id=$sid and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2))
			$xcls=$row2['cls_name'];
		}
			
		$op=$_POST['op'];
		if($op=='delete'){
					for ($i=0; $i<count($del); $i++) {
						$sql1="select * from surah_stu_read where id=$del[$i]";
						$res1=mysql_query($sql1)or die("$sql1 query failed:".mysql_error());
						$row1=mysql_fetch_assoc($res1);
						$surah=$row1['surahname'];
						$uid=$row1['uid'];
						$sql="delete from surah_stu_read where id=$del[$i]";
						$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						
						$sql2="select max(id) from surah_stu_read where uid='$uid' and surahname='$surah' and reading='$reading'";
						$res2=mysql_query($sql2)or die("query failed: $sql".mysql_error());
						$row2=mysql_fetch_row($res2);
						$xid2=$row2[0];

						$sql3="select * from surah_stu_read where id='$xid2'";
						$res3=mysql_query($sql3)or die("$sql3 query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$surahayat=$row3['surahayat'];
						$sql="update surah_stu_status set status=0,surahayat='$surahayat' where uid='$uid' and surahname='$surah' and reading='$reading'";
						$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					}
		}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript">
function process_form(action)
{
	var ret="";
	var cflag=false;
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
	<input type="hidden" name="p" value="hafazan_stu_edit">
	<input type="hidden" name="isprint">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="clslvl" value="<?php echo $clslvl;?>">
	<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('delete')" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitlebg">
  <tr>
    <td width="100%" align="left">EDIT - <?php echo strtoupper($sname);?></td>
  </tr>
</table>
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
        <td width="14%">NAMA</td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td>MATRIK</td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
	  <tr>
        <td><?php echo "$lg_sekolah";?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	

 	</td>
  </tr>
</table>

<table width="100%" cellspacing="0">
  <tr>
 	 	<td id="mytabletitle" width="2%"></td>
		<td align="center" id="mytabletitle" width="8%">Tarikh</td>
		<td align="center" id="mytabletitle" width="10%">Surah</td>
		<td align="center" id="mytabletitle" width="10%">Ayat</td>
        <td align="center" id="mytabletitle" width="10%">Categori</td>
		<td id="mytabletitle" width="50%">&nbsp;Guru Tasmi' </td>
  </tr>
<?php
	$sql="select * from surah_stu_read  where uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$dt=$row['dt'];
		$adm=$row['adm'];
		$sn=$row['surahname'];
		$sa=$row['surahayat'];
		$type=$row['reading'];
		
		if($type==1)
			$types="Tilawah";
		else
			$types="HAFALAN";
			
		if(($q++)%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
		$disabled="";

?>
  <tr <?php echo "$bg";?>>
  	<td id="myborder" align="center"><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)"></td>
    <td id="myborder" align="center"><?php echo "$dt";?></td>
    <td id="myborder" align="center"><?php echo "$sn";?></td>
    <td id="myborder" align="center"><?php echo "$sa";?></td>
    <td id="myborder" align="center"><?php echo "$types";?></td>
	<td id="myborder"><?php echo "$adm";?></td>
     
  </tr>
<?php } ?>
</table>

</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->