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
            $sname=$row['name'];
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
			
		
			$dt=$_POST['dt'];
			if($dt=="")
				$dt=date("Y-m-d");
			
			$op=$_POST['op'];
			if($op=='delete'){
				if (count($del)>0) {
					for ($i=0; $i<count($del); $i++) {
						$sql="select * from hafazan where id=$del[$i]";
						$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$dt=$row['dt'];
						$sid=$row['sid'];
						list($y,$m,$d)=explode("-",$dt);
					
						$sql="delete from hafazan where id=$del[$i]";
						mysql_query($sql)or die("$sql - failed:".mysql_error());
						
						$sql="delete from hafazan_rec where xid=$del[$i]";
						mysql_query($sql)or die("$sql - failed:".mysql_error());
						
						$sql="delete from hafazan_rep where uid='$uid' and year=$y and month=$m and sid=$sid";
						$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
						$sql="delete from hafazan_sem where uid='$uid' and year=$y and sid=$sid";
						$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
				
					}
				}
				
				
				//reset culculate balik...
				
					
				$sql="select * from hafazan where uid='$uid' and dt like '$year%' order by id";
				$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$ms=$row['ms'];
					$jk=$row['juzuk'];
					$id=$row['id'];
					$dt=$row['dt'];
					$sid=$row['sid'];
					list($y,$m,$d)=explode("-",$dt);

					$sql="select * from ses_stu where stu_uid='$uid' and year=$y";
					$res2=mysql_query($sql)or die("query failed:".mysql_error());
					if($row2=mysql_fetch_assoc($res2)){
						$cc=$row2['cls_code'];
						$cl=$row2['cls_level'];
					}
					
									//save to report
					$total_ms_month=0;
					$sql="select count(*) from hafazan_rec where uid='$uid' and year=$y and month=$m";
					$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					if($row3=mysql_fetch_row($res3))
						$total_ms_month=$row3[0];
					
					$total_ms_year=0;
					$sql="select count(*) from hafazan_rec where uid='$uid' and year=$y";
					$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					if($row3=mysql_fetch_row($res3))
						$total_ms_year=$row3[0];
			
					$total_juzuk_year=sprintf("%d",$total_ms_year/20);
					$mar=0;
					
					$sql="select * from grading where name='Hafazan' and point<=$total_ms_month order by point desc limit 1";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$row3=mysql_fetch_assoc($res3);
					$gred=$row3['grade'];
					$mar=$row3['val'];
					if($clslevel=="")
						$clslevel=0;
					
					
					$sql="insert into hafazan_rep (year,month,sid,cls_code,cls_level,uid,totalms,mg,mp,totaljk,currjk,currms,adm,ts) values 
												  ($y,'$m',$sid,'$clscode','$clslevel','$uid',$total_ms_month,'$gred',$mar,$total_juzuk_year,$jk,$ms,'$username',now())";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					
					$sql="insert into hafazan_sem (year,sid,cls_code,cls_level,uid,currms,totalms,currjk,totaljk,adm,ts) values 
												  ($y,$sid,'$clscode','$clslevel','$uid',$ms,$total_ms_year,$jk,$total_juzuk_year,'$username',now())";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					
					
				}
				
				
			}
			
			$ms=0;$juz=0;
			$sql="select * from hafazan where sid=$sid and uid='$uid'  order by dt desc, id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$ms=$row2['ms'];
			$pp=$row2['prev_page'];
			$jz=$row2['juzuk'];
			
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
<a href="#" onClick="javascript: href='hafazan_stu_reg.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
</div>
</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitlebg">
  <tr>
    <td width="100%" align="left">HAFAZAN - <?php echo strtoupper($sname);?></td>
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
	
	<table width="100%">
	  <tr>
        <td width="30%">Juzuk Semasa</td>
        <td width="1%">:</td>
        <td width="69%"><?php echo $jz;?></td>
      </tr>
	  <tr>
        <td>M/S&nbsp;Hafazan&nbsp;Terakhir</td>
        <td>:</td>
		<td><?php echo $pp;?></td>
      </tr>
	  <tr>
        <td>M/S&nbsp;Bacaan&nbsp;Semasa</td>
        <td>:</td>
		<td><?php echo $ms;?></td>
      </tr>
	  <!-- 
	  <tr>
        <td>No&nbsp;Ayat</td>
        <td>:</td>
		<td><?php if($xmss>0) echo "$xno"; else echo "$xno30";?></td>
      </tr>
	   -->
    </table>
 	</td>
  </tr>
</table>

<table width="100%" cellspacing="0">
  <tr>
 	 	<td id="mytabletitle" width="2%"></td>
		<td align="center" id="mytabletitle" width="8%">Tarikh</td>
		<td align="center" id="mytabletitle" width="10%">M/S Hafazan<br>Terakhir</td>
		<td align="center" id="mytabletitle" width="10%">M/S Bacaan<br>Semasa</td>
		<!-- <td align="center" id="mytabletitle" width="10%">Nombor<br>Ayat</td> -->
		<td align="center" id="mytabletitle" width="10%">Juzuk<br>Semasa</td>
		<td id="mytabletitle" width="50%">&nbsp;Guru Tasmi' </td>
  </tr>
<?php
	$sql="select * from hafazan where uid='$uid' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$ms=$row['ms'];
		$pp=$row['prev_page'];
		$no=$row['no'];
		$dt=$row['dt'];
		$tid=$row['tid'];
		$jk=$row['juzuk'];
		if(($q++)%2==0)
            $bg="bgcolor=#FAFAFA";
        else
        	$bg="";
		$disabled="";
		if($username!=$tid)
			$disabled="disabled";
		if(is_verify('admin'))
			$disabled="";
?>
  <tr <?php echo "$bg";?>>
  	<td id="myborder" align="center"><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)" <?php echo $disabled;?>></td>
    <td id="myborder" align="center"><?php echo "$dt";?></td>
    <td id="myborder" align="center"><?php echo "$ms";?></td>
    <td id="myborder" align="center"><?php echo "$pp";?></td>
    <!-- <td id="myborder" align="center"><?php echo "$no";?></td> -->
    <td id="myborder" align="center"><?php echo "$jk";?></td>
	<td id="myborder"><?php echo "$tid";?></td>
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