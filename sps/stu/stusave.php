<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];

	$id=$_POST['id'];
	$sid=$_POST['sid'];
	$uid=$_POST['uid'];
        $nisn=$_POST['nisn'];
	$acc=$_POST['acc'];
	$nogiliran=$_POST['nogiliran'];
	$pass=$_POST['pass'];
	$sponser=$_POST['sponser'];
	$name=$_POST['namapelajar'];
	$name=addslashes($name);
	$ic=$_POST['ic'];
	$sex=$_POST['sex'];
	$mentor=$_POST['mentor'];
	$etc=$_POST['etc'];
	$etc=addslashes($etc);
        $reason=$_POST['reason'];
	$reason=addslashes($reason);
	$race=$_POST['race'];
	$bstate=$_POST['bstate'];
	$bstate=addslashes($bstate);
	$citizen=$_POST['citizen'];
	$religion=$_POST['religion'];
	$d=$_POST['day'];
	$m=$_POST['month'];
	$y=$_POST['year'];
	$bday="$y-$m-$d";
	$tel=$_POST['tel'];
	$intake=$_POST['intake'];
	$fax=$_POST['fax'];
	$hp=$_POST['hp'];
	$rdate=$_POST['rdate'];
	$edate=$_POST['edate'];
	$status=$_POST['status'];
	if($status=="")
		$status=0;
	$mel=$_POST['mel'];
	$addr=addslashes($_POST['addr']);
	$bandar=$_POST['bandar'];
	$poskod=$_POST['poskod'];
	$state=$_POST['state'];
	$clslevel=$_POST['clslevel'];
	$pschool=$_POST['pschool'];
	$pschool=addslashes($pschool);
	$transport=$_POST['transport'];
	$isislah=$_POST['isislah'];
	if($isislah=="")
		$isislah=0;
	$isfakir=$_POST['isfakir'];
	if($isfakir=="")
		$isfakir=0;
	$isspecial=$_POST['isspecial'];
	if($isspecial=="")
		$isspecial=0;
	$iskawasan=$_POST['iskawasan'];
	if($iskawasan=="")
		$iskawasan=0;
	$isstaff=$_POST['isstaff'];
	if($isstaff=="")
		$isstaff=0;
	$isyatim=$_POST['isyatim'];
	if($isyatim=="")
		$isyatim=0;
        
        $isasuh=$_POST['isasuh'];
	if($isasuh=="")
		$isasuh=0;
                
	$ishostel=$_POST['ishostel'];
	if($ishostel=="")
		$ishostel=0;
	$isblock=$_POST['isblock'];
	if($isblock=="")
		$isblock=0;
	$onsms=$_POST['onsms'];
	if($onsms=="")
		$onsms=0;
	$ill=$_POST['ill'];
	
	$isfeenew=$_POST['isfeenew'];
	if($isfeenew=="")
		$isfeenew=0;
	$isfeefree=$_POST['isfeefree'];
	if($isfeefree=="")
		$isfeefree=0;
	$isfeeonanak=$_POST['isfeeonanak'];
	if($isfeeonanak=="")
		$isfeeonanak=0;
	$isinter=$_POST['isinter'];
	if($isinter=="")
		$isinter=0;
		
	$feehutang=$_POST['feehutang'];
	
	$regsta=$_POST['regsta'];
	
	
			$p1name=addslashes($_POST['p1name']);
			$p1ic=$_POST['p1ic'];
			$p1rel=$_POST['p1rel'];
			$p1job=$_POST['p1job'];
			$p1sal=$_POST['p1sal'];
			$p1com=addslashes($_POST['p1com']);
			$p1tel=$_POST['p1tel'];
			$p1tel2=$_POST['p1tel2'];
			$p1fax=$_POST['p1fax'];
			$p1state=$_POST['p1state'];
			$p1addr=addslashes($_POST['p1addr']);
			$p1mel=$_POST['p1mel'];
			$p1hp=$_POST['p1hp'];
			$p1wn=$_POST['p1wn'];
			$p1edu=$_POST['p1edu'];
			$p1bandar=addslashes($_POST['p1bandar']);
			$p1poskod=$_POST['p1poskod'];
			
			$p2name=addslashes($_POST['p2name']);
			$p2ic=$_POST['p2ic'];
			$p2rel=$_POST['p2rel'];
			$p2job=$_POST['p2job'];
			$p2sal=$_POST['p2sal'];
			$p2com=addslashes($_POST['p2com']);
			$p2tel=$_POST['p2tel'];
			$p2tel2=$_POST['p2tel2'];
			$p2fax=$_POST['p2fax'];
			$p2state=$_POST['p2state'];
			$p2addr=addslashes($_POST['p2addr']);
			$p2mel=$_POST['p2mel'];
			$p2hp=$_POST['p2hp'];
			$p2wn=$_POST['p2wn'];
			$p2edu=$_POST['p2edu'];
			$p2bandar=addslashes($_POST['p2bandar']);
			$p2poskod=$_POST['p2poskod'];
			
			$p3name=addslashes($_POST['p3name']);
			$p3ic=$_POST['p3ic'];
			$p3rel=$_POST['p3rel'];
			$p3tel=$_POST['p3tel'];
			$p3tel2=$_POST['p3tel2'];
			$p3fax=$_POST['p3fax'];
			$p3state=$_POST['p3state'];
			$p3addr=addslashes($_POST['p3addr']);
			$p3mel=$_POST['p3mel'];
			$p3hp=$_POST['p3hp'];

	$q01=$_POST['q01'];
	$q02=$_POST['q02'];
	$q03=$_POST['q03'];
	$q0=addslashes("$q01|$q02|$q03");
	$q11=$_POST['q11'];
	$q12=$_POST['q12'];
	$q13=$_POST['q13'];
	$q1=addslashes("$q11|$q12|$q13");
	$q21=$_POST['q21'];
	$q22=$_POST['q22'];
	$q23=$_POST['q23'];
	$q2=addslashes("$q21|$q22|$q23");
	$q31=$_POST['q31'];
	$q32=$_POST['q32'];
	$q33=$_POST['q33'];
	$q3=addslashes("$q31|$q32|$q33");
	
	$q41=$_POST['q41'];
	$q42=$_POST['q42'];
	$q43=$_POST['q43'];
	$q4=addslashes("$q41|$q42|$q43");
	$q51=$_POST['q51'];
	$q52=$_POST['q52'];
	$q53=$_POST['q53'];
	$q5=addslashes("$q51|$q52|$q53");
	$q61=$_POST['q61'];
	$q62=$_POST['q62'];
	$q63=$_POST['q63'];
	$q6=addslashes("$q61|$q62|$q63");
	$q71=$_POST['q71'];
	$q72=$_POST['q72'];
	$q73=$_POST['q73'];
	$q7=addslashes("$q71|$q72|$q73");
	$q81=$_POST['q81'];
	$q82=$_POST['q82'];
	$q83=$_POST['q83'];
	$q8=addslashes("$q81|$q82|$q83");
	$q91=$_POST['q91'];
	$q92=$_POST['q92'];
	$q93=$_POST['q93'];
	$q9=addslashes("$q91|$q92|$q93");
		
	
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $schlevel=$row['level'];
	$pref=$row['stuprefix'];
    mysql_free_result($res);					  
	
	
	$id=$_POST['id'];
	$operation=$_POST['operation'];

	if($operation=="delete"){
		$sql="delete from stu where id=$id";
		mysql_query($sql)or die("$sql 1query failed:".mysql_error());
		
		$y=date("Y");
		$sql="delete from ses_stu where stu_uid='$uid' and year='$y';";
		mysql_query($sql)or die("$sql 2query failed:".mysql_error());
		$st=2;
		echo "<script language=\"javascript\">location.href='p.php?p=stureg&st=2'</script>";
	}
	else if($operation=="update"){
			$sql="update stu set uid='$uid',nisn='$nisn',name='$name',ic='$ic',acc='$acc',sex='$sex',nogiliran='$nogiliran',etc='$etc',
					race='$race',religion='$religion',bday='$bday',ill='$ill',tel='$tel',tel2='$tel2',citizen='$citizen',
					fax='$fax',hp='$hp',mel='$mel',bandar='$bandar',poskod='$poskod',addr='$addr',state='$state',q0='$q0',q1='$q1',q2='$q2',
					q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',p1name='$p1name',
					p1ic='$p1ic',p1rel='$p1rel',p1job='$p1job',p1sal='$p1sal',p1com='$p1com',p1addr='$p1addr',
					p1state='$p1state',p1tel='$p1tel',p1tel2='$p1tel2',p1fax='$p1fax',p1hp='$p1hp',p1mel='$p1mel',p1wn='$p1wn',p1edu='$p1edu',
					p2name='$p2name',p2ic='$p2ic',p2rel='$p2rel',p2job='$p2job',p2sal='$p2sal',p2wn='$p2wn',p2edu='$p2edu',
					p2com='$p2com',p2addr='$p2addr',p2state='$p2state',p2tel='$p2tel',p2tel2='$p2tel2',p2fax='$p2fax',
					p2hp='$p2hp',p2mel='$p2mel',isislah=$isislah,ishostel=$ishostel,iskawasan=$iskawasan,onsms=$onsms,status=$status,ill='$ill',
					rdate='$rdate',edate='$edate',pschool='$pschool',transport='$transport',sponser='$sponser',
					isstaff=$isstaff,isyatim=$isyatim,isasuh=$isasuh,isfeenew=$isfeenew,isfeeonanak=$isfeeonanak,isfakir=$isfakir,isspecial=$isspecial,
					isfeefree=$isfeefree,isinter=$isinter,feehutang='$feehutang',
					p3name='$p3name',p3ic='$p3ic',p3rel='$p3rel',p3addr='$p3addr',p3tel='$p3tel',
					p3tel2='$p3tel2',p3fax='$p3fax',p3hp='$p3hp',p3mel='$p3mel',bstate='$bstate',intake='$intake',
					adm='$username',ts=now(),
                                        reasonleaving='$reason'
                                        where id=$id";
			mysql_query($sql)or die("$sql 3query failed:".mysql_error());		
			$sql="update ses_stu set stu_name='$name',stu_ic='$ic' where stu_uid='$uid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update exam set stu_name='$name',stu_ic='$ic' where stu_uid='$uid'";
			mysql_query($sql)or die("$sql 5query failed:".mysql_error());
			$st=1;
	
			$fn=basename( $_FILES['file']['name']);
			if($fn!=""){
				$sql="select file from stu where id='$id'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$fname=$row['file'];
				if($fname!=""){
					$tmp=$dir_image_student.$fname;
					unlink($tmp);
				}
					
				$ext=substr($fn,-4,4);
				$fn=sprintf("%s%s",$id,$ext);
				$target_path = $dir_image_student.$fn;
				if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
					$sql="update stu set file='$fn' where id=$id";
					mysql_query($sql)or die("query failed:".mysql_error());
				}
				else{
					echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
					exit;
				}
			}
                        
                        $img=basename( $_FILES['img']['name']);
			if($img!=""){
				$sql="select img from stu where uid='$uid'";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res2);
				$image=$row['img'];
				if($image!=""){
					$tmp=$dir_image_student.$image;
					unlink($tmp);
				}
					
				$ex=substr($img,-5,5);
				$img=sprintf("%s%s",$id,$ex);
				$target_path = $dir_image_student.$img;
				if(move_uploaded_file($_FILES['img']['tmp_name'], $target_path)) {
					$sql="update stu set img='$img' where id='$id'";
					mysql_query($sql)or die("query failed:".mysql_error());
				}
				else{
					echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ $img";
					exit;
				}
			}
	}
	else{
		$sql="select ic from stu where ic='$ic' and sch_id=$sid";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){
			echo "Maaf. Kad pengenalan '$ic' telah wujud";
			$st=0;
			$uid="";
		}
		else{
			$status=6;//pelajar
			$sql="insert into stu(cdate,sch_id,clslevel,nisn,pass,name,ic,sex,acc,
				race,religion,bday,ill,tel,tel2,fax,hp,mel,addr,bandar,poskod,state,
				q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,p1name,p1ic,p1rel,p1job,p1sal,p1com,
				 p1addr,p1state,p1tel,p1tel2,p1fax,p1hp,p1mel,p2name,p2ic,p2rel,p2job,
				 p2sal,p2com,p2addr,p2state,p2tel,p2tel2,p2fax,p2hp,p2mel,
				 isislah,ishostel,onsms,status,rdate,edate,
				 pschool,transport,isstaff,isyatim,isasuh,iskawasan,isfeenew,isfeeonanak,
				 isfeefree,feehutang,isfakir,isspecial,isinter,mentor,etc,isblock,adm,ts,sponser,bstate,reasonleaving) values
				(now(),$sid,$clslevel,'$nisn','$pass','$name','$ic','$sex','$acc',
				'$race','$religion','$bday','$ill','$tel','$tel2','$fax','$hp','$mel','$addr','$bandar','$poskod',
				'$state','$q0','$q1','$q2','$q3','$q4','$q5','$q6','$q7','$q8','$q9','$p1name',
				'$p1ic','$p1rel','$p1job','$p1sal','$p1com','$p1addr','$p1state','$p1tel',
				'$p1tel2','$p1fax','$p1hp','$p1mel','$p2name','$p2ic','$p2rel','$p2job','$p2sal',
				'$p2com','$p2addr','$p2state','$p2tel','$p2tel2','$p2fax','$p2hp','$p2mel',
				$isislah,$ishostel,$onsms,$status,'$rdate','$edate','$pschool','$transport',
				$isstaff,$isyatim,$isasuh,$iskawasan,$isfeenew,$isfeeonanak,$isfeefree,'$feehutang',
				$isfakir,$isspecial,$isinter,'$mentor','$etc',$isblock,'$username',now(),'$sponser','$bstate','$reason')";
				//echo $sql;
				mysql_query($sql)or die("$sql 6query failed:".mysql_error());
				$id=mysql_insert_id();
				
				if($si_student_global_id)
					$sql="update sch set stuid=stuid+1";
				else
					$sql="update sch set stuid=stuid+1 where id=$sid";
					
				mysql_query($sql)or die("$sql 7query failed:".mysql_error());
				$sql="select stuid from sch where id=$sid";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$c=$row['stuid'];
				$uid=sprintf("%s%04s",$pref,$c);
				$sql="update stu set uid='$uid' where id=$id";
				mysql_query($sql)or die("query failed:".mysql_error());
				$st=1;
				
				$fn=basename( $_FILES['file']['name']);
				if($fn!=""){
					$sql="select file from stu where id='$id'";
					$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$fname=$row['file'];
					if($fname!=""){
						$tmp=$dir_image_student.$fname;
						unlink($tmp);
					}
						
					$ext=substr($fn,-4,4);
					$fn=sprintf("%s%s",$id,$ext);
					$target_path = $dir_image_student.$fn;
					if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
						$sql="update stu set file='$fn' where id=$id";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
					else{
						echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
						exit;
					}
				}//if fn
                                
                                $img=basename( $_FILES['img']['name']);
                                if($img!=""){
                                        $sql="select img from stu where uid='$uid'";
                                        $res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
                                        $row=mysql_fetch_assoc($res2);
                                        $image=$row['img'];
                                        if($image!=""){
                                                $tmp=$dir_image_student.$image;
                                                unlink($tmp);
                                        }
                                                
                                        $ex=substr($img,-5,5);
                                        $img=sprintf("%s%s",$id,$ex);
                                        $target_path = $dir_image_student.$img;
                                        if(move_uploaded_file($_FILES['img']['tmp_name'], $target_path)) {
                                                $sql="update stu set img='$img' where id='$id'";
                                                mysql_query($sql)or die("query failed:".mysql_error());
                                        }
                                        else{
                                                echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ $img";
                                                exit;
                                        }
                                } //if img
			}//else ic no exist
		}//else
?> 
