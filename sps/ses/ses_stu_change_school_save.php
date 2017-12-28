<?php
//160910 5.0.0 - update gui
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');
$username = $_SESSION['username'];
$stu=$_POST['stu'];
$sid=$_POST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>

</head>

<body>
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div>
<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
				<a href="p.php?p=../ses/ses_stu_change_school" id="mymenuitem"><img src="../img/goback.png"><br>Kembali</a>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
				<div id="mymenu_seperator"></div>
				<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
</div>
<div id="story">
<div id="mytitle">PERUBAHAN STATUS MURID</div>

<table width="100%">
           <tr id="mytabletitle">
              <td width="7%">NO</td>
              <td width="12%">NIS</td>
              <td width="30%">NAMA SISWA</td>
			  <td width="18%">NO AKTE</td>
			  <td width="18%">KETERANGAN STATUS</td>
            </tr>
<?php
	if (count($stu)>0) {
		for ($i=0; $i<count($stu); $i++) {
				$data=$stu[$i];
				
				if($data=="")
					continue;
				$new_sid=strtok($data,"|");
				if($new_sid=="")
					continue;
				$stuuid=strtok("|");
				if($stuuid=="")
					continue;
				
				if($new_sid<0){//tamat belajar
					$sql="select * from stu where uid='$stuuid' and sch_id=$sid";
					$res=mysql_query($sql,$link)or die("$sql - query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$sid=$row['sch_id'];
					$uid=$row['uid'];
					$id=$row['id'];
					$pass=$row['pass'];
					$name=$row['name'];
					$ic=$row['ic'];
					
					$setsta=substr($new_sid,1,1);
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
					echo "<td>$status</td></tr>";
				}
				else{ //pindah sekolah
					$sql="select * from stu where uid='$stuuid' and sch_id=$sid";
					$res=mysql_query($sql,$link)or die("$sql - query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$sid=$row['sch_id'];
					$uid=$row['uid'];
					$id=$row['id'];
					$pass=$row['pass'];
					$name=$row['name'];
					$xname=addslashes($name);
					$ic=$row['ic'];
					$sex=$row['sex'];
					$race=$row['race'];
					$religion=$row['religion'];
					$bday=$row['bday'];
					list($y,$m,$d)=split('[/.-]',$bday);
					$tel=$row['tel'];
					$tel2=$row['tel2'];
					$fax=$row['fax'];
					$hp=$row['hp'];
					$mel=$row['mel'];
					$addr=$row['addr'];
					$xaddr=addslashes($addr);
					$state=$row['state'];
					$file=$row['file'];
					$clslevel=$row['cls_level'];
					$isislah=$row['isislah'];
					$ishostel=$row['ishostel'];
					$isstaff=$row['isstaff'];
					$isyatim=$row['isyatim'];
					$iskawasan=$row['iskawasan'];
					$isfeenew=$row['isfeenew'];
					$isfeefree=$row['isfeefree'];
					$isfeeonanak=$row['isfeeonanak'];
					$isfakir=$row['isfakir'];
					$feehutang=$row['feehutang'];
					
					$transport=$row['transport'];
					$pschool=$row['pschool'];
					$onsms=$row['onsms'];
					$ill=$row['ill'];
					$status=$row['status'];
					$edate=$row['edate'];
					$rdate=$row['rdate'];
					
					$p1name=$row['p1name'];
					$xp1name=addslashes($p1name);
					$p1ic=$row['p1ic'];
					$p1rel=$row['p1rel'];
					$p1job=$row['p1job'];
					$p1sal=$row['p1sal'];
					$p1com=$row['p1com'];
					$p1tel=$row['p1tel'];
					$p1tel2=$row['p1tel2'];
					$p1fax=$row['p1fax'];
					$p1state=$row['p1state'];
					$p1addr=$row['p1addr'];
					$xp1addr=addslashes($p1addr);
					$p1mel=$row['p1mel'];
					$p1hp=$row['p1hp'];
					
					$p2name=$row['p2name'];
					$xp2name=addslashes($p2name);
					$p2ic=$row['p2ic'];
					$p2rel=$row['p2rel'];
					$p2job=$row['p2job'];
					$p2sal=$row['p2sal'];
					$p2com=$row['p2com'];
					$p2tel=$row['p2tel'];
					$p2tel2=$row['p2tel2'];
					$p2fax=$row['p2fax'];
					$p2state=$row['p2state'];
					$p2addr=$row['p2addr'];
					$xp2addr=addslashes($p2addr);
					$p2mel=$row['p2mel'];
					$p2hp=$row['p2hp'];
					
					
					$q0=addslashes($row['q0']);
					list($q01,$q02,$q03)=split('[/.|]',$q0);
					$q1=addslashes($row['q1']);
					list($q11,$q12,$q13)=split('[/.|]',$q1);
					$q2=addslashes($row['q2']);
					list($q21,$q22,$q23)=split('[/.|]',$q2);
					$q3=addslashes($row['q3']);
					list($q31,$q32,$q33)=split('[/.|]',$q3);
					$q4=addslashes($row['q4']);
					list($q41,$q42,$q43)=split('[/.|]',$q4);
					$q5=addslashes($row['q5']);
					list($q51,$q52,$q53)=split('[/.|]',$q5);
					$q6=addslashes($row['q6']);
					list($q61,$q62,$q63)=split('[/.|]',$q6);
					$q7=addslashes($row['q7']);
					list($q71,$q72,$q73)=split('[/.|]',$q7);
					$q8=addslashes($row['q8']);
					list($q81,$q82,$q83)=split('[/.|]',$q8);
					$q9=addslashes($row['q9']);
					list($q91,$q92,$q93)=split('[/.|]',$q9);
					
					$sql="select ic from stu where ic='$ic' and sch_id=$new_sid";
					$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					$num=mysql_num_rows($res);
					if($num>0){
						if($q++%2==0)
							echo "<tr bgcolor=#FAFAFA>";
						else
							echo "<tr bgcolor=#FFFFFF>";
						echo "<td>$q</td>";
						echo "<td>$uid</td>";
						echo "<td>$name</td>";
						echo "<td>$ic</td>";
						echo "<td>Sorry. '$ic' already exist</td></tr>";
					}
					else{
						$sql="select * from sch where id='$new_sid'";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$schlevel=$row['level'];
						$pref=$row['stuprefix'];
						$sname=$row['name'];
						mysql_free_result($res);	
							
						if($si_student_global_id){
							$sql="update stu set sch_id=$new_sid,ses=0,clslevel=0,clscode='' where uid='$uid'";
							mysql_query($sql)or die("$sql - query failed:".mysql_error());
						}
						else{
							$status=6;//pelajar
							
							$sql="insert into stu(cdate,sch_id,pass,name,ic,sex,file,
							race,religion,bday,ill,tel,tel2,fax,hp,mel,addr,state,
							q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,p1name,p1ic,p1rel,p1job,p1sal,p1com,
							 p1addr,p1state,p1tel,p1tel2,p1fax,p1hp,p1mel,p2name,p2ic,p2rel,p2job,
							 p2sal,p2com,p2addr,p2state,p2tel,p2tel2,p2fax,p2hp,p2mel,
							 isislah,ishostel,onsms,status,rdate,
							 pschool,transport,isstaff,isyatim,iskawasan,isfeenew,isfeeonanak,isfeefree,feehutang,isfakir) values
							(now(),$new_sid,'$pass','$xname','$ic','$sex','$file',
							'$race','$religion','$bday','$ill','$tel','$tel2','$fax','$hp','$mel','$xaddr',
							'$state','$q0','$q1','$q2','$q3','$q4','$q5','$q6','$q7','$q8','$q9','$xp1name',
							'$p1ic','$p1rel','$p1job','$p1sal','$p1com','$xp1addr','$p1state','$p1tel',
							'$p1tel2','$p1fax','$p1hp','$p1mel','$xp2name','$p2ic','$p2rel','$p2job','$p2sal',
							'$p2com','$xp2addr','$p2state','$p2tel','$p2tel2','$p2fax','$p2hp','$p2mel',
							$isislah,$ishostel,$onsms,$status,now(),'$pschool','$transport',
							$isstaff,$isyatim,$iskawasan,$isfeenew,$isfeeonanak,$isfeefree,'$feehutang',$isfakir)";
							//echo $sql;
							mysql_query($sql)or die("$sql - query failed:".mysql_error());
							$id=mysql_insert_id();
							
							$sql="update sch set stuid=stuid+1 where id=$new_sid";
							mysql_query($sql)or die("$sql 7query failed:".mysql_error());
							$sql="select stuid from sch where id=$new_sid";
							$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
							$row=mysql_fetch_assoc($res);
							$c=$row['stuid'];
							$uid=sprintf("%s%04s",$pref,$c);
							$sql="update stu set uid='$uid' where id=$id";
							mysql_query($sql)or die("$sql - query failed:".mysql_error());
							//terminal old
							$sql="update stu set status=3,edate=now() where uid='$stuuid' and sch_id=$sid";
							$res=mysql_query($sql,$link)or die("$sql - query failed:".mysql_error());
						}
						
						
							
						if($q++%2==0)
							echo "<tr bgcolor=#FAFAFA>";
						else
							echo "<tr bgcolor=#FFFFFF>";
						echo "<td>$q</td>";
						echo "<td>$uid</td>";
						echo "<td>$name</td>";
						echo "<td>$ic</td>";
						echo "<td>$sname</td></tr>";
					}//else
				}//else pindah sekolah
			}//for
		}//if
?>
	</table>          
</div><!-- story -->
</div><!-- content -->  
</body>
</html>
<!-- 30/01/08 -->
