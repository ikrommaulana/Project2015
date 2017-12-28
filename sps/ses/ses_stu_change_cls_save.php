<?php
//160910 5.0.0 - update gui
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
	$sid=$_POST['sid'];
	$year=$_POST['year'];
	$del=$_POST['del'];
	$usr=$_POST['usr'];
	$operation=$_POST['operation'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>

</head>

<body>
<div id="content">
<div id="mypanel" align="center">
	<div id="mymenu">
		<a href="p.php?p=../ses/ses_stu_change_cls<?php echo "&sid=$sid";?>" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div>
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_student_class_placement);?></div>
<table width="100%">
           <tr id="mytabletitle">
              <td width="7%"><?php echo strtoupper($lg_no);?></td>
              <td width="12%"><?php echo strtoupper($lg_matric);?></strong></td>
              <td width="30%"><?php echo strtoupper($lg_name);?></td>
			  <td width="18%"><?php echo strtoupper($lg_ic);?></td>
			  <td width="18%"><?php echo strtoupper($lg_class);?></td>
			  <td width="5%"><?php echo strtoupper($lg_session);?></td>
            </tr>
<?php
	if (count($usr)>0) {
			for ($i=0; $i<count($usr); $i++) {
				$data=$usr[$i];
				
				if($data=="")
					continue;
				$clscode=strtok($data,"|");
				if($clscode=="")
					continue;
				$stuuid=strtok("|");
				if($stuuid=="")
					continue;
				
				if($clscode<0){//tamat belajar
					$sql="select * from stu where uid='$stuuid' and sch_id=$sid";
					$res=mysql_query($sql,$link)or die("$sql - query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$sid=$row['sch_id'];
					$uid=$row['uid'];
					$id=$row['id'];
					$pass=$row['pass'];
					$name=ucwords(strtolower(stripslashes($row['name'])));
					$ic=$row['ic'];
					/*if($clscode==-1){
						$stusta=3;
						$status="$lg_end";
					}elseif($clscode==-2){
						$stusta=2;
						$status="Change School";
					}else{
						$stusta=4;
						$status="Terminate";
					}*/

					$setsta=substr($clscode,1,1);
					$sqlstusta="select * from type where grp='stusta' and val='$setsta'";
					$resstusta=mysql_query($sqlstusta)or die("$sqlstusta query failed:".mysql_error());
					$rowstusta=mysql_fetch_assoc($resstusta);
					$stusta=$rowstusta['val'];
					$status=$rowstusta['prm'];
					
					$sql="update stu set status='$stusta',edate=now() where uid='$stuuid' and sch_id=$sid";
					$res=mysql_query($sql,$link)or die("$sql - query failed:".mysql_error());
					if($q++%2==0)
						echo "<tr bgcolor=#FAFAFA>";
					else
						echo "<tr bgcolor=#FFFFFF>";
						echo "<td>$q</td>";
						echo "<td>$uid</td>";
						echo "<td>$name</td>";
						echo "<td>$ic</td>";
						echo "<td>$status</td>";
						echo "<td></td></tr>";
				}
				else{ //setting kelas
						$sql="select * from stu where uid='$stuuid'";
						$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$stuid=$row['id'];
						$uid=$row['uid'];
						$ic=$row['ic'];
						$name=ucwords(strtolower(stripslashes($row['name'])));
						if($q++%2==0)
							echo "<tr bgcolor=#FAFAFA>";
						else
							echo "<tr bgcolor=#FFFFFF>";
						echo "<td>$q</td>";
						echo "<td>$uid</td>";
						echo "<td>$name</td>";
						echo "<td>$ic</td>";
						
						$sql="select * from cls where code='$clscode' and sch_id=$sid";
						$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$clsname=stripslashes($row['name']);
						$clslevel=$row['level'];
						$v=$row['id'];
						echo "<td>$clsname</td>";
						echo "<td>$year</td></tr>";
						if(!$SYSTEM_ALLOW_STUDENT_MULTI_CLASS){
							$sql="delete from ses_stu where stu_uid='$uid' and year='$year'";
							mysql_query($sql)or die("$sql - query failed:".mysql_error());
						}
						$clsname=addslashes($clsname);
						$name=addslashes($name);
						$sql="insert into ses_stu(stu_id,stu_uid,stu_name,stu_ic,cls_name,cls_code,cls_level,year,sch_id,adm,ts)
								values ($stuid,'$uid','$name','$ic','$clsname','$clscode','$clslevel','$year',$sid,'$username',now())";
								
						mysql_query($sql)or die("$sql - query failed:".mysql_error());
						
						$sql="update stu set clslevel='$clslevel',clscode='$clscode',ses=$year where uid='$uid'";
						mysql_query($sql)or die("$sql - query failed:".mysql_error());
				}//setting classs
			}
		}
?>
	</table>          
</div><!-- story -->
</div><!-- content -->  
</body>
</html>
	
