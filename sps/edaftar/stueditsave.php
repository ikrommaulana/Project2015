<?php
	
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	include_once("$MYLIB/inc/language_$LG.php");
	$adm = $_SESSION['username'];
	if($adm!="")
		$isadminreg=1;
	else
		$isadminreg=0;
	
    $edit=$_REQUEST['edit'];
	$regsid=$_REQUEST['sid'];
	if($regsid=="")
		$regsid=0;

	$sql="select * from sch where id=$regsid";
    $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $regsname=$row['name']; //school name
	 $sname=$row['name'];
			$progname=$row['name']; //school name
	$sch_lvl=$row['level'];
	if($sch_lvl=="Diploma")
		$exam="SPM";
	else
		$exam="UPSR";
	
	$exam="UPSR";
	
    mysql_free_result($res);					  
	
	$sid=$regsid;
/************************* process pendaftaran *****************************/ 
	
			$e=$_REQUEST['e'];
			$id=$_REQUEST['id'];
			$ic=mysql_real_escape_string($_REQUEST['ic']);
			$nosb=mysql_real_escape_string($_REQUEST['nosb']);
			$name=mysql_real_escape_string($_REQUEST['name']);
			$sex=mysql_real_escape_string($_REQUEST['sex']);
			$race=mysql_real_escape_string($_REQUEST['race']);
			$religion=mysql_real_escape_string($_REQUEST['religion']);
			$d=$_REQUEST['day'];
			$m=$_REQUEST['month'];
			$y=$_REQUEST['year'];
			$bday="$y-$m-$d";
			$bstate=mysql_real_escape_string($_REQUEST['bstate']);
			$addr=mysql_real_escape_string($_REQUEST['addr']);
			$addr1=mysql_real_escape_string($_REQUEST['addr1']);
			$addr2=mysql_real_escape_string($_REQUEST['addr2']);
			$poskod=mysql_real_escape_string($_REQUEST['poskod']);
			$bandar=mysql_real_escape_string($_REQUEST['bandar']);
			$state=mysql_real_escape_string($_REQUEST['state']);
			$tel=mysql_real_escape_string($_REQUEST['tel']);
			$hp=mysql_real_escape_string($_REQUEST['hp']);
			$mel=mysql_real_escape_string($_REQUEST['mel']);
			$citizen=mysql_real_escape_string($_REQUEST['citizen']);
			$transport=mysql_real_escape_string($_REQUEST['transport']);
			$clslevel=mysql_real_escape_string($_REQUEST['clslevel']);
			$pschool=mysql_real_escape_string($_REQUEST['pschool']);
			$pschoolyear=mysql_real_escape_string($_REQUEST['pschoolyear']);
			$sakit=$_REQUEST['sakit'];
			$sakitlain=mysql_real_escape_string($_REQUEST['sakitlain']);
			$ill="";
			if (count($sakit)>0) {
				for ($i=0; $i<count($sakit); $i++) {
					$ill=$ill.$sakit[$i]."|";
				}
			}
			$ill=mysql_real_escape_string("$ill"."$sakitlain");
			$regsta=mysql_real_escape_string($_REQUEST['regsta']);
			if($regsta=="")
				$regsta=0;	
	
			$p1name=mysql_real_escape_string($_REQUEST['p1name']);
			$p1ic=mysql_real_escape_string($_REQUEST['p1ic']);
			$p1bstate=mysql_real_escape_string($_REQUEST['p1bstate']);
			$p1job=mysql_real_escape_string($_REQUEST['p1job']);
			$p1sal=mysql_real_escape_string($_REQUEST['p1sal']);
			$p1com=mysql_real_escape_string($_REQUEST['p1com']);
			$p1tel=mysql_real_escape_string($_REQUEST['p1tel']);
			$p1tel2=mysql_real_escape_string($_REQUEST['p1tel2']);
			$p1fax=mysql_real_escape_string($_REQUEST['p1fax']);
			$p1addr=mysql_real_escape_string($_REQUEST['p1addr']);
			$p1mel=mysql_real_escape_string($_REQUEST['p1mel']);
			$p1hp=mysql_real_escape_string($_REQUEST['p1hp']);
			
			$totalsal=$p1sal+$p2sal;
			
			$p2name=mysql_real_escape_string($_REQUEST['p2name']);
			$p2ic=mysql_real_escape_string($_REQUEST['p2ic']);
			$p2job=mysql_real_escape_string($_REQUEST['p2job']);
			$p2sal=mysql_real_escape_string($_REQUEST['p2sal']);
			$p2com=mysql_real_escape_string($_REQUEST['p2com']);
			$p2tel=mysql_real_escape_string($_REQUEST['p2tel']);
			$p2tel2=mysql_real_escape_string($_REQUEST['p2tel2']);
			$p2fax=mysql_real_escape_string($_REQUEST['p2fax']);
			$p2bstate=mysql_real_escape_string($_REQUEST['p2bstate']);
			$p2addr=mysql_real_escape_string($_REQUEST['p2addr']);
			$p2mel=mysql_real_escape_string($_REQUEST['p2mel']);
			$p2hp=mysql_real_escape_string($_REQUEST['p2hp']);
			
			$p3name=mysql_real_escape_string($_REQUEST['p3name']);
			$p3ic=mysql_real_escape_string($_REQUEST['p3ic']);
			$p3rel=mysql_real_escape_string($_REQUEST['p3rel']);
			$p3tel=mysql_real_escape_string($_REQUEST['p3tel']);
			$p3tel2=mysql_real_escape_string($_REQUEST['p3tel2']);
			$p3fax=mysql_real_escape_string($_REQUEST['p3fax']);
			$p3addr=mysql_real_escape_string($_REQUEST['p3addr']);
			$p3mel=mysql_real_escape_string($_REQUEST['p3mel']);
			$p3hp=mysql_real_escape_string($_REQUEST['p3hp']);
			$clslevel=mysql_real_escape_string($_REQUEST['clslevel']);
			$sesyear=mysql_real_escape_string($_REQUEST['sesyear']);
			$pt=mysql_real_escape_string($_REQUEST['pt']);
			
			$sendername=mysql_real_escape_string($_REQUEST['sendername']);
			$collectorname=mysql_real_escape_string($_REQUEST['collectorname']);
			$cartype=mysql_real_escape_string($_REQUEST['cartype']);
			$carno=mysql_real_escape_string($_REQUEST['carno']);
			$cartype2=mysql_real_escape_string($_REQUEST['cartype2']);
			$carno2=mysql_real_escape_string($_REQUEST['carno2']);
			$istransport=mysql_real_escape_string($_REQUEST['istransport']);
			$twoway=mysql_real_escape_string($_REQUEST['twoway']);
			$saddr=mysql_real_escape_string($_REQUEST['saddr']);
			$faddr=mysql_real_escape_string($_REQUEST['faddr']);
			$clssession=mysql_real_escape_string($_REQUEST['clssession']);
			
			
			$sttb=$_REQUEST['sttb'];
			$stopdate=$_REQUEST['stopdate'];
			$yearinschool=$_REQUEST['yearinschool'];
			$ussbn=$_REQUEST['ussbn'];
			$uan=$_REQUEST['uan'];
			$reasonleaving=$_REQUEST['reasonleaving'];
			$pschool2=$_REQUEST['pschool2'];
			$sttb2=$_REQUEST['sttb2'];
			$stopdate2=$_REQUEST['stopdate2'];
			$yearinschool2=$_REQUEST['yearinschool2'];
			$nick=$_REQUEST['nick'];
			$bahasa=$_REQUEST['bahasa'];
			$anakke=$_REQUEST['anakke'];
			$jumkandung=$_REQUEST['jumkandung'];
			$jumtiri=$_REQUEST['jumtiri'];
			$jumangkat=$_REQUEST['jumangkat'];
			$transport=$_REQUEST['transport'];
			$tinggalbersama=$_REQUEST['tinggalbersama'];
			$jaraksekolah=$_REQUEST['jaraksekolah'];
			$p1edu=$_REQUEST['p1edu'];
			$p2edu=$_REQUEST['p2edu'];
			$yatim=$_REQUEST['yatim'];
			
			
			$blood=$_REQUEST['blood'];
			$beratmasuk=$_REQUEST['beratmasuk'];
			$beratkeluar=$_REQUEST['beratkeluar'];
			$tinggimasuk=$_REQUEST['tinggimasuk'];
			$tinggikeluar=$_REQUEST['tinggikeluar'];
			$cacat=$_REQUEST['cacat'];
			$tahunkeluar=$_REQUEST['tahunkeluar'];
			$sebabkeluar=$_REQUEST['sebabkeluar'];
			$dateend=$_REQUEST['dateend'];
			$naikkelas=$_REQUEST['naikkelas'];
			$ulangkelas=$_REQUEST['ulangkelas'];
			
			$isnew=mysql_real_escape_string($_REQUEST['isnew']);
			if($isnew=="")
				$isnew=1;
				
			$sid2=$_REQUEST['sid2'];
			if($sid2=="")
				$sid2=0;
			$sid3=$_REQUEST['sid3'];
			if($sid3=="")
				$sid3=0;
			$daerah=$_REQUEST['daerah'];
			$isforen=$_REQUEST['isforen'];
			
			$q01=$_REQUEST['q01'];
			$q02=$_REQUEST['q02'];
			$q03=$_REQUEST['q03'];
			$q0=mysql_real_escape_string("$q01|$q02|$q03");
			$q11=$_REQUEST['q11'];
			$q12=$_REQUEST['q12'];
			$q13=$_REQUEST['q13'];
			$q1=mysql_real_escape_string("$q11|$q12|$q13");
			$q21=$_REQUEST['q21'];
			$q22=$_REQUEST['q22'];
			$q23=$_REQUEST['q23'];
			$q2=mysql_real_escape_string("$q21|$q22|$q23");
			$q31=$_REQUEST['q31'];
			$q32=$_REQUEST['q32'];
			$q33=$_REQUEST['q33'];
			$q3=mysql_real_escape_string("$q31|$q32|$q33");
			$q41=$_REQUEST['q41'];
			$q42=$_REQUEST['q42'];
			$q43=$_REQUEST['q43'];
			$q4=mysql_real_escape_string("$q41|$q42|$q43");
			$q51=$_REQUEST['q51'];
			$q52=$_REQUEST['q52'];
			$q53=$_REQUEST['q53'];
			$q5=mysql_real_escape_string("$q51|$q52|$q53");
			$q61=$_REQUEST['q61'];
			$q62=$_REQUEST['q62'];
			$q63=$_REQUEST['q63'];
			$q6=mysql_real_escape_string("$q61|$q62|$q63");
			$q71=$_REQUEST['q71'];
			$q72=$_REQUEST['q72'];
			$q73=$_REQUEST['q73'];
			$q7=mysql_real_escape_string("$q71|$q72|$q73");
			$q81=$_REQUEST['q81'];
			$q82=$_REQUEST['q82'];
			$q83=$_REQUEST['q83'];
			$q8=mysql_real_escape_string("$q81|$q82|$q83");
			$q91=$_REQUEST['q91'];
			$q92=$_REQUEST['q92'];
			$q93=$_REQUEST['q93'];
			$q9=mysql_real_escape_string("$q91|$q92|$q93");


			
	/**		
			if($id==''){
				$sql="select id from stureg where ic='$ic' and sch_id='$regsid' and confirm=1 and sesyear='$sesyear'";
				$res=mysql_query($sql) or die("$sql error:".mysql_error());
				$row=mysql_fetch_row($res);
				$num=$row[0];
				if($num>0){
					echo "<script language=\"javascript\">location.href='reg_existed.php?id=$num&ic=$ic'</script>";
				}
			}
	**/
			$sql="select id from stureg where id='$id'";
			$res=mysql_query($sql) or die("$sql error:".mysql_error());
			$row=mysql_fetch_row($res);
			$num=$row[0];
			if($num>0){
					$sql="update stureg set ts=now(),sch_id=$regsid,name='$name',ic='$ic',bstate='$bstate',
					sex='$sex',race='$race',religion='$religion',bday='$bday',ill='$ill',tel='$tel',hp='$hp',mel='$mel'
					,addr='$addr',addr1='$addr1',addr2='$addr2',state='$state',transport='$transport',totalsal='$totalsal',
					 p1name='$p1name',p1ic='$p1ic',p1bstate='$p1bstate',p1job='$p1job',p1sal='$p1sal',
					 p1com='$p1com',p1addr='$p1addr',p1tel='$p1tel',p1tel2='$p1tel2',p1fax='$p1fax',p1hp='$p1hp',p1mel='$p1mel',
					 p2name='$p2name',p2ic='$p2ic',p2bstate='$p2bstate',p2job='$p2job',p2sal='$p2sal',
					 p2com='$p2com',p2addr='$p2addr',p2tel='$p2tel',p2tel2='$p2tel2',p2fax='$p2fax',p2hp='$p2hp',p2mel='$p2mel',
					 p3name='$p3name',p3ic='$p3ic',p3rel='$p3rel',p3addr='$p3addr',p3tel='$p3tel',
					 p3tel2='$p3tel2',p3fax='$p3fax',p3hp='$p3hp',p3mel='$p3mel',poskod='$poskod',bandar='$bandar',upsr_result='$sumgred',
					 anaknegeri='$anaknegeri',pschool='$pschool',pschoolyear='$pschoolyear',
					 nosb='$nosb',cls_level=$clslevel,sesyear=$sesyear,citizen='$citizen',
					 q0='$q0',q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',pt='$pt',
					 noinfamily='$noinfamily',nameatschool='$nameatschool',nameathome='$nameathome',firstaid='$firstaid',
					 allergic_reaction='$allergic_reaction',allergic='$allergic',
					 sendername='$sendername',collectorname='$collectorname',cartype='$cartype',carno='$carno',cartype2='$cartype2',
					 istransport='$istransport',twoway='$twoway',saddr='$saddr',saddr='$saddr',faddr='$faddr',
					 clssession='$clssession',language1='$language1',language2='$language2',isnew='$isnew',isforen='$isforen',
					 
					 sttb='$sttb',stopdate='$stopdate',yearinschool='$yearinschool',ussbn='$ussbn',uan='$uan',
					 reasonleaving='$reasonleaving',pschool2='$pschool2',sttb2='$sttb2',stopdate2='$stopdate2',yearinschool2='$yearinschool2',
					 nick='$nick',bahasa='$bahasa',anakke='$anakke',jumkandung='$jumkandung',jumtiri='$jumtiri',
					 jumangkat='$jumangkat',transport='$transport',tinggalbersama='$tinggalbersama',p1edu='$p1edu',p2edu='$p2edu',
					 yatim='$yatim',jaraksekolah='$jaraksekolah',
					 
					  blood='$blood',beratmasuk='$beratmasuk',beratkeluar='$beratkeluar',tinggimasuk='$tinggimasuk',tinggikeluar='$tinggikeluar',
					  cacat='$cacat',tahunkeluar='$tahunkeluar',sebabkeluar='$sebabkeluar',dateend='$dateend',naikkelas='$naikkelas',
					  ulangkelas='$ulangkelas',
					 
					 daerah='$daerah',sid2=$sid2,sid3=$sid3 where id='$id'";
					 mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
			}else{
					$sql="insert into stureg(cdate,sch_id,name,ic,bstate,sex,race,religion,bday,ill,tel,hp,mel,
					 addr,addr1,addr2,state,transport,
					 p1name,p1ic,p1bstate,p1job,p1sal,p1com,p1addr,p1tel,p1tel2,p1fax,p1hp,p1mel,
					 p2name,p2ic,p2bstate,p2job,p2sal,p2com,p2addr,p2tel,p2tel2,p2fax,p2hp,p2mel,
					 p3name,p3ic,p3rel,p3addr,p3tel,p3tel2,p3fax,p3hp,p3mel,
					 poskod,bandar,upsr_result,anaknegeri,pschool,pschoolyear,cls_level,sesyear,isadminreg,
					 q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,operator,adm,ts,nosb,citizen,pt,clssession,isnew,isforen,
					 
					 sttb,stopdate,yearinschool,ussbn,uan,
					 reasonleaving,pschool2,sttb2,stopdate2,yearinschool2,
					 nick,bahasa,anakke,jumkandung,jumtiri,jumangkat,
					 tinggalbersama,p1edu,p2edu,yatim,jaraksekolah,
					 
					 blood,beratmasuk,beratkeluar,tinggimasuk,tinggikeluar,
						cacat,tahunkeluar,sebabkeluar,dateend,naikkelas,ulangkelas,
					 
					 daerah,sid2,sid3,totalsal,confirm,status) 
					 values
					(now(),$regsid,'$name','$ic','$bstate','$sex','$race','$religion','$bday','$ill','$tel','$hp','$mel',
					'$addr','$addr1','$addr2','$state','$transport',
					'$p1name','$p1ic','$p1bstate','$p1job','$p1sal','$p1com','$p1addr','$p1tel','$p1tel2','$p1fax','$p1hp','$p1mel',
					'$p2name','$p2ic','$p2bstate','$p2job','$p2sal','$p2com','$p2addr','$p2tel','$p2tel2','$p2fax','$p2hp','$p2mel',
					'$p3name','$p3ic','$p3rel','$p3addr','$p3tel','$p3tel2','$p3fax','$p3hp','$p3mel',
					'$poskod','$bandar','$sumgred','$anaknegeri','$pschool','$pschoolyear',$clslevel,$sesyear,$isadminreg,
					'$q0','$q1','$q2','$q3','$q4','$q5','$q6','$q7','$q8','$q9','$adm','$adm',now(),'$nosb','$citizen','$pt',
					'$clssession','$isnew','$isforen',
					 '$sttb','$stopdate','$yearinschool','$ussbn','$uan',
					 '$reasonleaving','$pschool2','$sttb2','$stopdate2','$yearinschool2',
					 '$nick','$bahasa','$anakke','$jumkandung','$jumtiri','$jumangkat',
					 '$tinggalbersama','$p1edu','$p2edu','$yatim','$jaraksekolah',
					 
					 '$blood','$beratmasuk','$beratkeluar','$tinggimasuk','$tinggikeluar',
					 '$cacat','$tahunkeluar','$sebabkeluar','$dateend','$naikkelas','$ulangkelas',

					 '$daerah','$sid2','$sid3','$totalsal',1,11)";
					mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
					$id=mysql_insert_id();
			}
			
			$sql="delete from stureg_akademik where ic='$ic'";
			mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
			
			$sql="insert into stureg_akademik(xid,ic,sch,year,exam,result,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,s11,s12,s13,s14,s15,s16,ts)values
			($id,'$ic','$upsrsch','$upsryear','$exam','$sumgred','$xsub[0]','$xsub[1]','$xsub[2]','$xsub[3]','$xsub[4]',
			'$xsub[5]','$xsub[6]','$xsub[7]','$xsub[8]','$xsub[9]','$xsub[10]','$xsub[11]','$xsub[12]','$xsub[13]',
			'$xsub[14]','$xsub[15]',now())";
			mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
					
			$sql="insert into stureg_akademik(xid,ic,exam,s1,s2,s3,s4,s5,ts)values
			($id,'$ic','AL-QURAN','$bacaquran','$bacajawi','$tulisjawi','$khatam_info','$hafaz_info',now())";
			mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
					
			$sql="insert into stureg_akademik(xid,ic,exam,s1,s2,s3,s4,s5,ts)values
			($id,'$ic','KOQ','$koqinfo[0]','$koqinfo[1]','$koqinfo[2]','$koqinfo[3]','$koqinfo[4]',now())";
			mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
			
			$sql="insert into stureg_akademik(xid,ic,exam,sch,year,etc,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,ts)values
			($id,'$ic','LAIN-LAIN','$examlain_sch','$examlain_year','$examlain_name',
			'$examlain[0]','$examlain[1]','$examlain[2]','$examlain[3]','$examlain[4]','$examlain[5]',
			'$examlain[6]','$examlain[7]','$examlain[8]','$examlain[9]',now())";
			mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
			$xid=$id;
		if($EREG_QUESTIONAIR){
				$sql="delete from stureg_akademik where ic='$ic'";
				mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
				
				$jaw=$_REQUEST['s'];
				$qid=$_REQUEST['qid'];
				for ($i=0; $i<count($jaw); $i++) {
						$sta=$_REQUEST["a$i"]; 
						$xsub[$i]=$sta."|".$jaw[$i];
				}
				$exam='Child';
				$sql="insert into stureg_akademik(xid,ic,sch,year,exam,result,s1,s2,s3,s4,s5,s6,s7,s8,s9,s10,s11,s12,s13,s14,s15,s16,ts)values
				($id,'$ic','$upsrsch','$upsryear','$exam','$sumgred','$xsub[0]','$xsub[1]','$xsub[2]','$xsub[3]','$xsub[4]',
				'$xsub[5]','$xsub[6]','$xsub[7]','$xsub[8]','$xsub[9]','$xsub[10]','$xsub[11]','$xsub[12]','$xsub[13]',
				'$xsub[14]','$xsub[15]',now())";
				mysql_query($sql)or die("$sql <br> query failed:".mysql_error());
		}



		$fn=basename( $_FILES['pic']['name']);
		if($fn!=""){				
					$ext=substr($fn,-3,3);
					$fn="$sid_$ic.$ext";
					$target_path = $dir_image_student.$fn;
					if(move_uploaded_file($_FILES['pic']['tmp_name'], $target_path)) {
						$sql="update stureg set pic='$fn' where sch_id='$sid' and ic='$ic'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
					else{
						echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
						exit;
					}
		}//if fn
		$fn=basename( $_FILES['picend']['name']);
		if($fn!=""){
					$ext=substr($fn,-3,3);
					$fn="$sid_$ic_2.$ext";
					$target_path = $dir_image_student.$fn;
					if(move_uploaded_file($_FILES['picend']['tmp_name'], $target_path)) {
						$sql="update stureg set picend='$fn' where sch_id='$sid' and ic='$ic'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
					else{
						echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
						exit;
					}
		}
		$fn=basename( $_FILES['p1pic']['name']);
		if($fn!=""){
					$ext=substr($fn,-3,3);
					$fn="$sid_$ic_p1.$ext";
					$target_path = $dir_image_student.$fn;
					if(move_uploaded_file($_FILES['p1pic']['tmp_name'], $target_path)) {
						$sql="update stureg set p1pic='$fn' where sch_id='$sid' and ic='$ic'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
					else{
						echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
						exit;
					}
		}//if fn
		$fn=basename( $_FILES['p2pic']['name']);
		if($fn!=""){
					$ext=substr($fn,-3,3);
					$fn="$sid_$ic_p2.$ext";
					$target_path = $dir_image_student.$fn;
					if(move_uploaded_file($_FILES['p2pic']['tmp_name'], $target_path)) {
						$sql="update stureg set p2pic='$fn' where sch_id='$sid' and ic='$ic'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
					else{
						echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
						exit;
					}
		}//if fn
				
	
		
		
?>


<html>
<head>
<title>Re-Direct</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body onLoad="javascript:myform.submit();">
<center>
<form method="post" action="bukuinduk.php" name="myform">
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="ic" value="<?php echo $ic;?>">
<input type="hidden" name="sid" value="<?php echo $sid;?>">
<input type="submit" value="Your request have been process. Please click here to check your status..">
</form>
</center>
</body>
</html> 