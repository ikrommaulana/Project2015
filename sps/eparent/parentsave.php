<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];

			$id=$_POST['id'];
			$sid=$_POST['sid'];
			$acc=$_POST['acc'];
	
			$p1name=addslashes($_POST['p1name']);
			$p1ic=$_POST['p1ic'];
                        $pob1=$_POST['pob1'];
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
                        $d1=$_POST['d1'];
	                $m1=$_POST['m1'];
                        $y1=$_POST['y1'];
                        $d2=$_POST['d2'];
	                $m2=$_POST['m2'];
                        $y2=$_POST['y2'];
			//$p1bday=$_POST['p1bday'];
                        $p1bday=$d1."-".$m1."-".$y1;
                        $p2bday=$d2."-".$m2."-".$y2;
                        
                        $p1hp=$_POST['p1hp'];
			$p1wn=$_POST['p1wn'];
			$p1edu=$_POST['p1edu'];
			$p1bandar=addslashes($_POST['p1bandar']);
			$p1poskod=$_POST['p1poskod'];
			
                        
			$p2name=addslashes($_POST['p2name']);
			$p2ic=$_POST['p2ic'];
                        $pob2=$_POST['pob2'];
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
                        $d=$_POST['day'];
                        $m=$_POST['month'];
                        $y=$_POST['year'];
			$p2bday=$_POST['p2bday'];
                        
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

	if($operation=="save"){
				/**
					$sql="update stu set q0='$q0',q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',
					p1name='$p1name',p1ic='$p1ic',p1job='$p1job',p1wn='$p1wn',p1edu='$p1edu',
					p1sal='$p1sal',p1com='$p1com',p1addr='$p1addr',p1bandar='$p1bandar',p1poskod='$p1poskod',p1state='$p1state',
					p1tel='$p1tel',	p1tel2='$p1tel2',p1fax='$p1fax',p1hp='$p1hp',p1mel='$p1mel',
					p2name='$p2name',p2ic='$p2ic',p2job='$p2job',p2wn='$p2wn',p2edu='$p2edu',
					p2sal='$p2sal',p2com='$p2com',p2addr='$p2addr',p2bandar='$p2bandar',p2poskod='$p2poskod',p2state='$p2state',
					p2tel='$p2tel',p2tel2='$p2tel2',p2fax='$p2fax',p2hp='$p2hp',p2mel='$p2mel',
					p3name='$p3name',p3ic='$p3ic',p3rel='$p3rel',p3addr='$p3addr',p3tel='$p3tel',
					p3tel2='$p3tel2',p3fax='$p3fax',p3hp='$p3hp',p3mel='$p3mel',
					adm='$username',ts=now() where p1ic='$p1ic'";
				**/
				        $sql="update stu set q0='$q0',q1='$q1',q2='$q2',q3='$q3',q4='$q4',q5='$q5',q6='$q6',q7='$q7',q8='$q8',q9='$q9',
					p1name='$p1name',p1ic='$p1ic',pob1='$pob1',p1job='$p1job',p1wn='$p1wn',d1='$d1',m1='$m1',y1='$y1',p1bday='$p1bday',p1edu='$p1edu',
					p1sal='$p1sal',p1com='$p1com',p1addr='$p1addr',
					p1tel='$p1tel',	p1tel2='$p1tel2',p1fax='$p1fax',p1hp='$p1hp',p1mel='$p1mel',
					p2name='$p2name',p2ic='$p2ic',pob2='$pob2',p2job='$p2job',p2wn='$p2wn',p2edu='$p2edu',
					p2sal='$p2sal',p2com='$p2com',p2addr='$p2addr',
					p2tel='$p2tel',p2tel2='$p2tel2',p2fax='$p2fax',p2hp='$p2hp',p2mel='$p2mel',d2='$d2',m2='$m2',y2='$y2',p2bday='$p2bday',
					p3name='$p3name',p3ic='$p3ic',p3rel='$p3rel',p3addr='$p3addr',p3tel='$p3tel',
					p3tel2='$p3tel2',p3fax='$p3fax',p3hp='$p3hp',p3mel='$p3mel',
					adm='$username',ts=now() where p1ic='$p1ic'";
					mysql_query($sql)or die("$sql query failed:".mysql_error());
					$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
	}
				
?> 
