<?php
$vmod="v6.0.0";
$vdate="110701";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("estaff",1);
$username = $_SESSION['username'];

	$uid=$_REQUEST['uid'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	$operation=$_REQUEST['operation'];
	if($operation!=""){
		include_once('usrsave.php');
	}

		
		if($uid!=""){
			$sql="select * from usr where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$id=$row['id'];
			$sid=$row['sch_id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=ucwords(strtolower(stripslashes($row['name'])));
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($yy,$mm,$dd)=explode("-",$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$fax=$row['fax'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=ucwords(strtolower(stripslashes($row['addr'])));
			$state=$row['state'];
	
			$job=$row['job'];
			$joblevel=$row['joblvl'];
			$jobdiv=$row['jobdiv'];
			$jobsta=$row['jobsta'];
			$jobstart=$row['jobstart'];
			$jobend=$row['jobend'];
			$syslevel=$row['syslevel'];
			$file=$row['file'];
			
			$q1=explode("|",$row['q1']);
			$q2=explode("|",$row['q2']);
			$q3=explode("|",$row['q3']);
			$q4=explode("|",$row['q4']);
			$q5=explode("|",$row['q5']);
			
			$x1=explode("|",$row['x1']);
			$x2=explode("|",$row['x2']);
			$x3=explode("|",$row['x3']);
			$x4=explode("|",$row['x4']);
			$x5=explode("|",$row['x5']);
			
			$a1=explode("|",$row['a1']);
			$a2=explode("|",$row['a2']);
			$a3=explode("|",$row['a3']);
			$a4=explode("|",$row['a4']);
			$a5=explode("|",$row['a5']);
			$a6=explode("|",$row['a6']);
			$a7=explode("|",$row['a7']);
			$a8=explode("|",$row['a8']);
			$a9=explode("|",$row['a9']);
			$a10=explode("|",$row['a10']);
			$a11=explode("|",$row['a11']);
		}
		if($sid>0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>

<div id="content">
		<div id="mypanel">
				<div id="mymenu" align="center">
						<a href="usrreg.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/tool.png"><br><?php echo $lg_edit;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<a href="#" onClick="window.print();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<a href="usr_info.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
				</div> <!-- end mymenu -->
				<div align="right">
					<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
				</div>
		</div> <!-- end mypanel -->
		
		<div id="story">
		
		
		<div id="mytitlebg">A. <?php echo strtoupper($lg_personal_information);?></div>
		 <table width="100%">
            <tr>
              	<td width="82%">
					 <table width="100%"  id="myborder" cellspacing="0">
						<tr>
						  <td width="18%"><?php echo strtoupper($lg_name);?></td>
						  <td width="82%">:&nbsp;<?php echo $name;?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_ic_number);?></td>
						  <td>:&nbsp;<?php echo $ic;?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_sex);?></td>
						  <td>:&nbsp;<?php echo $lg_sexmalefemale[$sex];?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_race);?></td>
						  <td>:&nbsp;<?php echo $race;?></td>
						</tr>
					   <tr>
						  <td><?php echo strtoupper($lg_religion);?></td>
						  <td>:&nbsp;<?php echo $religion;?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_birth_date);?></td>
						  <td>:&nbsp;<?php echo "$dd-$mm-$yy";?></td>
						</tr>
					   <tr>
						  <td><?php echo strtoupper($lg_tel_home);?></td>
						  <td>:&nbsp;<?php echo $tel;?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_tel_mobile);?></td>
						  <td>:&nbsp;<?php echo $hp;?></td>
						</tr>
						<tr>
						  <td><?php echo strtoupper($lg_email);?></td>
						  <td>:&nbsp;<?php echo $mel;?></td>
						</tr>
							<td><?php echo strtoupper($lg_address);?></td>
							<td>:&nbsp;<?php echo $addr;?></td>
						</tr>
					  <tr>
						<td><?php echo strtoupper($lg_state);?></td>
						<td>:&nbsp;<?php echo $state;?></td>
					  </tr>
				</table>
			</td>
			<td valign="top"  align="center" id="myborder">

						<?php if($file!=""){?>
							<img name="picture" src="<?php if($file!="") echo "../adm/imgresize.php?w=88&h=90&img=$dir_image_user$file"; ?>" width="130" height="135" alt="Picture">
						<?php } ?>

				</td>
		   
	</tr>
</table>

<div id="mytitlebg">B. <?php echo strtoupper($lg_job_information);?></div>
<table width="100%" id="myborder" cellspacing="0">
			<tr>
              <td width="15%"><?php echo strtoupper($lg_position);?></td>
              <td>:&nbsp;<?php echo $job;?></td>
            </tr>
			<tr>
              <td><?php echo strtoupper($lg_grade);?></td>
              <td>:&nbsp;<?php echo $joblevel;?></td>
            </tr>
			<tr>
                <td><?php echo strtoupper($lg_division);?></td>
                <td>:&nbsp;<?php echo $jobdiv;?></td>
            </tr>
			<tr>
              <td><?php echo strtoupper($lg_status);?></td>
              <td>:&nbsp;<?php echo $jobsta;?></td>
            </tr>
		  <tr>
            <td><?php echo strtoupper($lg_start_date);?></td>
            <td>:&nbsp;<?php echo $jobstart;?></td>
          </tr>
		  			          <tr>
            <td><?php echo strtoupper($lg_end_date);?></td>
            <td>:&nbsp;<?php echo $jobend;?></tr>
</table>

<div id="mytitlebg">C. <?php echo strtoupper($lg_academic_information);?></div>
			  <table width="100%" cellspacing="0">
                <tr id="mytitle">
                  <td width="40%"><?php echo $lg_academic_level;?></td>
                  <td width="45%"><?php echo $lg_institution;?></td>
                  <td width="15%"><?php echo $lg_year;?></td>
                </tr>
                <tr>
                  <td>1. <?php echo $q1[0];?></td>
                  <td><?php echo $q1[1];?></td>
                  <td><?php echo $q1[2];?></td>
                </tr>
                <tr>
                  <td>2. <?php echo $q2[0];?></td>
                  <td><?php echo $q2[1];?></td>
                  <td><?php echo $q2[2];?></td>
                </tr>
                <tr>
                  <td>3. <?php echo $q3[0];?></td>
                  <td><?php echo $q3[1];?></td>
                  <td><?php echo $q3[2];?></td>
                </tr>
				<tr>
                  <td>4. <?php echo $q4[0];?></td>
                  <td><?php echo $q4[1];?></td>
                  <td><?php echo $q4[2];?></td>
                </tr>
				<tr>
                  <td>5. <?php echo $q5[0];?></td>
                  <td><?php echo $q5[1];?></td>
                  <td><?php echo $q5[2];?></td>
                </tr>
              </table>


<div id="mytitlebg">D. <?php echo strtoupper($lg_working_experience);?> </div>
			  <table width="100%" cellspacing="0">
                <tr id="mytitle">
                  <td width="40%"><?php echo $lg_position;?></td>
                  <td width="45%"><?php echo $lg_employer;?></td>
                  <td width="15%"><?php echo $lg_year;?></td>
                </tr>
                <tr>
                  <td>1. <?php echo $x1[0];?></td>
                  <td><?php echo $x1[1];?></td>
                  <td><?php echo $x1[2];?></td>
                </tr>
                <tr>
                  <td>2. <?php echo $x2[0];?></td>
                  <td><?php echo $x2[1];?></td>
                  <td><?php echo $x2[2];?></td>
                </tr>
                <tr>
                  <td>3. <?php echo $x3[0];?></td>
                  <td><?php echo $x3[1];?></td>
                  <td><?php echo $x3[2];?></td>
                </tr>
				<tr>
                  <td>4. <?php echo $x4[0];?></td>
                  <td><?php echo $x4[1];?></td>
                  <td><?php echo $x4[2];?></td>
                </tr>
				<tr>
                  <td>5. <?php echo $x5[0];?></td>
                  <td><?php echo $x5[1];?></td>
                  <td><?php echo $x5[2];?></td>
                </tr>
              </table>

<div id="mytitlebg">E. <?php echo strtoupper($lg_family_information);?> </div>
			  <table width="100%" cellspacing="0">
                <tr id="mytitle">
                  <td width="40%"><?php echo $lg_name;?></td>
                  <td width="45%"><?php echo $lg_school_ipt_job;?></td>
                  <td width="15%"><?php echo $lg_birth_year;?></td>
                </tr>
                <tr>
                  <td>1. <?php echo $a1[0];?></td>
                  <td><?php echo $a1[1];?></td>
                  <td><?php echo $a1[2];?></td>
                </tr>
                <tr>
                  <td>2. <?php echo $a2[0];?></td>
                  <td><?php echo $a2[1];?></td>
                  <td><?php echo $a2[2];?></td>
                </tr>
                <tr>
                  <td>3. <?php echo $a3[0];?></td>
                  <td><?php echo $a3[1];?></td>
                  <td><?php echo $a3[2];?></td>
                </tr>
				<tr>
                  <td>4. <?php echo $a4[0];?></td>
                  <td><?php echo $a4[1];?></td>
                  <td><?php echo $a4[2];?></td>
                </tr>
				<tr>
                  <td>5. <?php echo $a5[0];?></td>
                  <td><?php echo $a5[1];?></td>
                  <td><?php echo $a5[2];?></td>
                </tr>
				<tr>
                  <td>6. <?php echo $a6[0];?></td>
                  <td><?php echo $a6[1];?></td>
                  <td><?php echo $a6[2];?></td>
                </tr>
				<tr>
                  <td>7. <?php echo $a7[0];?></td>
                  <td><?php echo $a7[1];?></td>
                  <td><?php echo $a7[2];?></td>
                </tr>
				<tr>
                  <td>8. <?php echo $a8[0];?></td>
                  <td><?php echo $a8[1];?></td>
                  <td><?php echo $a8[2];?></td>
                </tr>
				<tr>
                  <td>9. <?php echo $a9[0];?></td>
                  <td><?php echo $a9[1];?></td>
                  <td><?php echo $a9[2];?></td>
                </tr>
				<tr>
                  <td>10. <?php echo $a10[0];?></td>
                  <td><?php echo $a10[1];?></td>
                  <td><?php echo $a10[2];?></td>
                </tr>
				<tr>
                  <td>11. <?php echo $a11[0];?></td>
                  <td><?php echo $a11[1];?></td>
                  <td><?php echo $a11[2];?></td>
                </tr>
              </table>


</div>
</div>

</body>
</html>
