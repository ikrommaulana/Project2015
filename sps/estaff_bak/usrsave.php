<?php
$vmod="v6.0.0";
$vdate="110701";
include_once('../etc/db.php');
include_once('../etc/session.php');
	$username = $_SESSION['username'];
	$app=$_POST['app'];
	$sid=$_POST['sid'];
	$uid=$_POST['uidx'];
	$pass=$_POST['passx'];
	$name=addslashes($_POST['name']);
	$ic=$_POST['ic'];
	$sex=$_POST['sex'];
	$race=$_POST['race'];
	$citizen=$_POST['citizen'];
	$country=$_POST['country'];
	$edulevel=$_POST['edulevel'];
	$religion=$_POST['religion'];
	$dd=$_POST['day'];
	$mm=$_POST['month'];
	$yy=$_POST['year'];
	$bday="$yy-$mm-$dd";
	$status=$_POST['status'];
	$tel=$_POST['tel'];
	$tel2=$_POST['tel2'];
	$fax=$_POST['fax'];
	$hp=$_POST['hp'];
	$mel=$_POST['mel'];
	$addr=addslashes($_POST['addr']);
	$state=$_POST['state'];
	
			$addr1=$_POST['addr1'];
			$addr2=$_POST['addr2'];
			$city=$_POST['city'];
			$pcode=$_POST['pcode'];
			$spname=$_POST['spname'];
			$spic=$_POST['spic'];
			$spjob=$_POST['spjob'];
			
	$job=addslashes($_POST['job']);
	$jobdiv=addslashes($_POST['jobdiv']);
	$jobsta=$_POST['jobsta'];
	$jobstart=$_POST['jobstart'];
	$jobend=$_POST['jobend'];
	$syslevel=$_POST['syslevel'];
	$joblevel=$_POST['joblevel'];


	$qx=$_POST['q1'];
	$q1=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['q2'];
	$q2=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['q3'];
	$q3=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['q4'];
	$q4=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['q5'];
	$q5=addslashes("$qx[0]|$qx[1]|$qx[2]");
		
	$qx=$_POST['x1'];
	$x1=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['x2'];
	$x2=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['x3'];
	$x3=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['x4'];
	$x4=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['x5'];
	$x5=addslashes("$qx[0]|$qx[1]|$qx[2]");
	
	$qx=$_POST['a1'];
	$a1=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a2'];
	$a2=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a3'];
	$a3=addslashes("$qx[0]|$qx[1]|$qx[2]");
	
	$qx=$_POST['a4'];
	$a4=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a5'];
	$a5=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a6'];
	$a6=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a7'];
	$a7=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a8'];
	$a8=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a9'];
	$a9=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a10'];
	$a10=addslashes("$qx[0]|$qx[1]|$qx[2]");
	$qx=$_POST['a11'];
	$a11=addslashes("$qx[0]|$qx[1]|$qx[2]");
	
	
	$id=$_POST['id'];
	$operation=$_POST['operation'];
	$checkupload=0;
	
	$pass=trim($pass);
			if($pass!=""){
				$sqlpass=",pass=md5('$pass')";
				$strpas="&amp; NEW PASSWORD HAS BEEN SET";
			}
			
	if($operation=="delete"){
		$sql="delete from usr where uid='$uid'";
		mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY DELETED&gt;</font>";
	}		
	elseif($operation=="update"){

				$sql="update usr set uid='$uid',name='$name',ic='$ic',sex='$sex',
						race='$race',religion='$religion',bday='$bday',addr='$addr',state='$state',
						hp='$hp',tel='$tel',tel2='$tel2',fax='$fax',mel='$mel',
						job='$job',jobsta='$jobsta',jobdiv='$jobdiv',jobstart='$jobstart',jobend='$jobend',
						q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',
						x1='$x1',x2='$x2',x3='$x3',x4='$x4',x5='$x5',
						a1='$a1',a2='$a2',a3='$a3',a4='$a4',a5='$a5',a6='$a6',
						a7='$a7',a8='$a8',a9='$a9',a10='$a10',a11='$a11',
						syslevel='$syslevel',joblvl='$joblevel',sch_id=$sid,
						status=$status,
						citizen='$citizen',country='$country',edulevel='$edulevel',
						city='$city',pcode='$pcode',addr1='$addr1',addr2='$addr2',
						spname='$spname',spic='$spic',spjob='$spjob',
						adm='$username',ts=now() $sqlpass where uid='$uid'";

			mysql_query($sql)or die("$sql failed:".mysql_error());
			$f="<font color=blue>&lt; SUCCESSFULLY UPDATED $strpas &gt;</font>";
			$checkupload=1;
		}
		else{
			$sql="select uid from usr where uid='$uid'";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num=mysql_num_rows($res);
			if($num>0){
				echo "Sorry. Staff ID '$uid' already exist";
				$f="<font color=red>&lt; Sorry. Staff ID '$uid' already exist! &gt;</font>";
				$uid="";
				echo "&nbsp;&nbsp;<a href=\"#\" onClick=\"window.close()\">CLOSE(X)</a>";
				exit;
			}
			$sql="select ic from usr where ic='$ic' and sch_id=$sid";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num=mysql_num_rows($res);
			if($num>0){
				echo "Sorry. IC '$ic' already exist";
				$f="<font color=red>&lt; Sorry. IC '$ic' already exist! &gt;</font>";
				$uid="";
				echo "&nbsp;&nbsp;<a href=\"#\" onClick=\"window.close()\">CLOSE(X)</a>";
				exit;
			}else{
				if($si_staff_auto_id){
					$sql="select * from type where grp='staffid' and prm='$sid'";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$count=$row['val']+1;
					$pref=$row['code'];
					$uid=sprintf("%s%03d",$pref,$count);
					$pass="123456";
				
				}
								
				$sql="insert into usr(cdate,sch_id,uid,pass,name,ic,sex,race,religion,bday,addr,state,tel,tel2,hp,fax,mel,
					job,jobsta,jobdiv,jobstart,jobend,q1,q2,q3,q4,q5,x1,x2,x3,x4,x5,syslevel,status,joblvl,
					a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,
					citizen,country,edulevel,
					city,pcode,addr1,addr2,spname,spic,spjob,adm,ts) values 
					(now(),$sid,'$uid',md5('$pass'),'$name','$ic','$sex','$race','$religion','$bday','$addr','$state',
					'$tel','$tel2','$hp','$fax','$mel','$job','$jobsta','$jobdiv','$jobstart','$jobend'
					,'$q1','$q2','$q3','$q4','$q5','$x1','$x2','$x3','$x4','$x5','$syslevel',$status,'$joblevel',
					'$a1','$a2','$a3','$a4','$a5','$a6','$a7','$a8','$a9','$a10','$a11',
					'$citizen','$country','$edulevel',
					'$city','$pcode','$addr1','$addr2','$spname','$spic','$spjob','$username',now())";
					
				mysql_query($sql)or die("$sql failed:".mysql_error());
				$id=mysql_insert_id();
				$f="<font color=blue>&lt; SUCCESSFULLY REGISTER $strpas &gt;</font>";
				$checkupload=1;
			}
		}

	
	if($checkupload){
		$fn=basename( $_FILES['file']['name']);
		if($fn!=""){
			$sql="select file from usr where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$fname=$row['file'];
			if($fname!=""){
				$tmp=$dir_image_user.$fname;
				unlink($tmp);
			}
     		$ext=substr($fn,-4,4);
			$fn=sprintf("%s%s",$uid,$ext);
			$target_path =$dir_image_user.$fn;
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		    	$sql="update usr set file='$fn' where uid='$uid'";
	    	  	mysql_query($sql)or die("query failed:".mysql_error());
			}
			else{
				echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
				exit;
			}
		}
	}
	
?> 
