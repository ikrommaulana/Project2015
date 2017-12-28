<?php
//10/05/2010 - update slahses
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR");
$username = $_SESSION['username'];

	  	$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_POST['sid'];

		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$id=$row['id'];
			$uid=$row['uid'];
			$pass=$row['pass'];
			$name=ucwords(strtolower(stripslashes($row['name'])));
			$transport=$row['transport'];
			$ic=$row['ic'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$tel2=$row['tel2'];
			$bstate=$row['bstate'];
			$fax=$row['fax'];
			$acc=$row['acc'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=ucwords(strtolower(stripslashes($row['addr'])));
			$bandar=$row['bandar'];
			$poskod=$row['poskod'];
			$state=$row['state'];
			$file=$row['file'];
			$pschool=ucwords(strtolower($row['pschool']));
			$clslevel=$row['cls_level'];
			$isislah=$row['isislah'];
			$isstaff=$row['isstaff'];
			$isyatim=$row['isyatim'];
			$iskawasan=$row['iskawasan'];
			$ishostel=$row['ishostel'];
			$onsms=$row['onsms'];
			$ill=$row['ill'];
			$status=$row['status'];
			$edate=$row['edate'];
			$rdate=$row['rdate'];
			
			$p1name=ucwords(strtolower(stripslashes($row['p1name'])));
			$p1ic=$row['p1ic'];
			$p1rel=$row['p1rel'];
			$p1job=ucwords(strtolower(stripslashes($row['p1job'])));
			$p1sal=$row['p1sal'];
			$p1com=ucwords(strtolower(stripslashes($row['p1com'])));
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1state=$row['p1state'];
			$p1addr=ucwords(strtolower(stripslashes($row['p1addr'])));
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			
			$p2name=ucwords(strtolower(stripslashes($row['p2name'])));
			$p2ic=$row['p2ic'];
			$p2rel=$row['p2rel'];
			$p2job=ucwords(strtolower(stripslashes($row['p2job'])));
			$p2sal=$row['p2sal'];
			$p2com=ucwords(strtolower(stripslashes($row['p2com'])));
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2state=$row['p2state'];
			$p2addr=ucwords(strtolower(stripslashes($row['p2addr'])));
			$p2mel=$row['p2mel'];
			$p2hp=$row['p2hp'];
			
			$p3name=ucwords(strtolower(stripslashes($row['p3name'])));
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3job=ucwords(strtolower(stripslashes($row['p3job'])));
			$p3sal=$row['p3sal'];
			$p3com=ucwords(strtolower(stripslashes($row['p3com'])));
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3state=$row['p3state'];
			$p3addr=ucwords(strtolower(stripslashes($row['p3addr'])));
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			
			
			$q0=$row['q0'];
			list($q01,$q02,$q03)=split('[/.|]',$q0);
			$q1=$row['q1'];
			list($q11,$q12,$q13)=split('[/.|]',$q1);
			$q2=$row['q2'];
			list($q21,$q22,$q23)=split('[/.|]',$q2);
			$q3=$row['q3'];
			list($q31,$q32,$q33)=split('[/.|]',$q3);
			$q4=$row['q4'];
			list($q41,$q42,$q43)=split('[/.|]',$q4);
			$q5=$row['q5'];
			list($q51,$q52,$q53)=split('[/.|]',$q5);
			$q6=$row['q6'];
			list($q61,$q62,$q63)=split('[/.|]',$q6);
			$q7=$row['q7'];
			list($q71,$q72,$q73)=split('[/.|]',$q7);
			$q8=$row['q8'];
			list($q81,$q82,$q83)=split('[/.|]',$q8);
			$q9=$row['q9'];
			list($q91,$q92,$q93)=split('[/.|]',$q9);
			
			
			
			mysql_free_result($res);
		}
		if($sid!=""){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$slevel=$row['level'];
			$feephp="fee".$slevel."v.php";
            mysql_free_result($res);					  
		}
		if($rdate=="")
			$rdate=date("Y-m-d");
			
		$sql="select * from type where grp='stusta' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $statuspelajar=$row['prm'];
	$year=date('Y');
	$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clsname=$row['cls_name'];
			$clscode=$row['cls_code'];
			$clslvl=$row['cls_level'];
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="stu_profile">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
</form>
<div id="content">
<div id="mypanel" class="printhidden">
		<div id="mymenu" align="center">
			<!-- <a href="stu_info.php?p=stureg&uid=<?php echo $uid;?>&sid=<?php echo $sid;?>" id="mymenuitem"><img src="../img/tool.png"><br>Edit</a> -->
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div>
<div id="story">
<div id="mytitlebg"><?php echo $lg_student_information;?></div>
<table width="100%">
  <tr>
  	<td width="75" align="center" id="myborder">
		 <?php if($file!=""){?>
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>" width="75" height="80">
		<?php }else echo "&nbsp;"; ?>
	</td>
    <td valign="top">	
	<table width="100%" >
      <tr>
        <td width="20%"><?php echo $lg_name;?></td>
        <td width="1%">:</td>
        <td width="79%">&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo $lg_ic_number;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td ><?php echo $lg_school;?></td>
        <td >:</td>
        <td >&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_class;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname / $year";?></td>
      </tr>
    </table>


	</td>
    <td valign="top">
	
	<table width="100%">
	  <tr>
        <td width="29%"><?php echo $lg_register;?></td>
        <td width="1%">:</td>
        <td width="70%"><?php echo "$rdate";?> </td>
      </tr>
	  <tr>
        <td><?php echo $lg_end;?></strong></td>
        <td>:</td>
        <td><?php echo "$edate";?> </td>
      </tr>
	  <tr>
        <td><?php echo $lg_status;?></strong></td>
        <td>:</td>
        <td><?php echo "$statuspelajar";?> </td>
      </tr>
<?php if($ISPARENT_ACC){?>
	  <tr>
        <td><?php echo $lg_school_account;?></strong></td>
        <td>:</td>
        <td><?php echo "$acc";?> </td>
      </tr>
<?php } ?>
    </table>
 	</td>
  </tr>
</table>
<div id="mytitlebg"><?php echo $lg_profile;?></div>
<table width="100%"><tr><td id="myborder" width="50%" valign="top">
		<table width="100%">  
                <tr>
                  <td width="20%"><?php echo $lg_sex;?> </td>
				  <td width="1%">:</td>
                  <td width="79%"><?php echo $lg_malefemale[$sex];?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_race;?></td>
				  <td>:</td>
                  <td><?php echo $race;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_relegion;?> </td>
				  <td>:</td>
                  <td><?php echo $religion;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_birth_date;?></td>
				  <td>:</td>
                  <td><?php echo "$bday"; ?></td>
                </tr>
				<tr>
                  <td ><?php echo $lg_birth_place;?></td>
				  <td>:</td>
                  <td><?php echo "$bstate"; ?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_telephone;?></td>
				  <td>:</td>
                  <td><?php echo $tel;?></td>
                </tr>
				<tr>
                  <td ><?php echo "$lg_handphone ($lg_parent)";?></td>
				  <td>:</td>
                  <td><?php echo $hp;?></td>
                </tr>
                <tr>
                  <td ><?php echo "$lg_email ($lg_parent)";?></td>
				  <td>:</td>
                  <td><?php echo $mel;?></td>
   				</tr>
		  <tr>
				<td valign="top"><?php echo $lg_address;?></td>
				<td>:</td>
				<td><?php echo $addr;?></td>
		  </tr>
		  <tr>
				<td  valign="top"><?php echo $lg_city;?></td>
				<td>:</td>
				<td><?php echo $bandar;?></td>
		  </tr>
		  <tr>
				<td valign="top"><?php echo $lg_postcode;?></td>
				<td>:</td>
				<td><?php echo $poskod;?></td>
		  </tr>
		  <tr>
				<td ><?php echo $lg_state;?></td>
				<td>:</td>
				<td><?php echo $state;?> </td>
		  </tr>
		</table>
</td><td width="50%" valign="top" id="myborder">
	
		<table width="100%"
				<tr>
					<td width="20%"><?php echo $lg_transport;?></td>
					<td width="1%">:</td>
					<td width="79%"><?php echo $transport;?></td>
           		</tr>
				<tr>
					  <td><?php echo $lg_illness;?></td>
					  <td>:</td>
					  <td><?php echo $ill;?></td>
                </tr>
				<tr>
					  <td><?php echo $lg_previous_school;?></td>
					  <td>:</td>
					  <td><?php echo $pschool;?></td>
                </tr>
				<tr>
					  <td><?php echo $lg_hostel;?></td>
					  <td>:</td>
					  <td><?php if($ishostel) echo "YA"?></td>
               	</tr>
				<tr>
					  <td><?php echo $lg_xprimary;?></td>
					  <td>:</td>
					  <td><?php if($isislah) echo "YA"; else echo "-";?> </td>
                </tr>
				<tr>
					  <td ><?php echo $lg_staff_child;?></td>
					  <td>:</td>
					  <td><?php if($isstaff) echo "YA"; else echo "-";?> </td>
                </tr>
				<tr>
					  <td><?php echo $lg_kariah;?></td>
					  <td>:</td>
					  <td><?php if($iskawasan) echo "YA"; else echo "-";?> </td>
                </tr>
				<tr>
					  <td><?php echo $lg_orphan;?></td>
					  <td>:</td>
					  <td><?php if($isyatim) echo "YA"; else echo "-";?> </td>
                </tr>
				<tr>
					  <td><?php echo $lg_international;?></td>
					  <td>:</td>
					  <td><?php if($isyatim) echo "YA"; else echo "-";?> </td>
                </tr>
		</table>

</td>
</tr></table>

<table width="100%" >
		<tr>
				<td id="mytitlebg" width="50%"><?php echo $lg_father_information;?></td>
				<td id="mytitlebg" width="50%"><?php echo $lg_mother_information;?></td>
		</tr>
		<tr>
              <td width="50%" id="myborder" valign="top"><table width="100%" >
                <tr>
                  <td width="20%"><?php echo $lg_name;?></td>
				  <td width="1%">:</td>
                  <td width="79%"><?php echo $p1name;?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_ic_number;?></td>
				  <td>:</td>
                  <td><?php echo $p1ic;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_handphone;?></td>
				  <td>:</td>
                  <td><?php echo $p1hp;?></td>
                </tr>
				<tr>
				  <td ><?php echo $lg_email;?></td>
				  <td>:</td>
				  <td><?php echo $p1mel;?></td>
			  </tr>
                <tr>
                  <td><?php echo $lg_job;?></td>
				  <td>:</td>
                  <td><?php echo $p1job;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_employer;?></td>
				  <td>:</td>
                  <td><?php echo $p1com;?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_address;?></td>
				  <td>:</td>
                  <td><?php echo $p1addr;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_telephone;?></td>
				  <td>:</td>
                  <td><?php echo $p1tel2;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_fax;?></td>
				  <td>:</td>
                  <td><?php echo $p1fax;?></td>
                </tr>
				<tr>
                  <td><?php echo $lg_salary;?></td>
				  <td>:</td>
                  <td><?php echo $p1sal;?></td>
                </tr>

              </table></td>
              <td width="50%" id="myborder" valign="top"><table width="100%" >
				<tr>
                  <td width="20%"><?php echo $lg_name;?></td>
				  <td width="1%">:</td>
                  <td width="79%"><?php echo $p2name;?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_ic_number;?></td>
				  <td>:</td>
                  <td><?php echo $p2ic;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_handphone;?></td>
				  <td>:</td>
                  <td><?php echo $p2hp;?></td>
                </tr>
				<tr>
				  <td ><?php echo $lg_email;?></td>
				  <td>:</td>
				  <td><?php echo $p2mel;?></td>
			  </tr>
                <tr>
                  <td><?php echo $lg_job;?></td>
				  <td>:</td>
                  <td><?php echo $p2job;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_employer;?></td>
				  <td>:</td>
                  <td><?php echo $p2com;?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_address;?></td>
				  <td>:</td>
                  <td><?php echo $p2addr;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_telephone;?></td>
				  <td>:</td>
                  <td><?php echo $p2tel2;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_fax;?></td>
				  <td>:</td>
                  <td><?php echo $p2fax;?></td>
                </tr>
				<tr>
                  <td><?php echo $lg_salary;?></td>
				  <td>:</td>
                  <td><?php echo $p2sal;?></td>
                </tr>
		</table>
			  
			  
			  </td>
            </tr>
          </table>

<table width="100%" ><tr><td id="myborder" width="50%" valign="top">
<div id="mytitlebg"><?php echo $lg_contact;?>&nbsp;(<?php echo $lg_for_emergency_call;?>)</div>


	<table width="100%" >
				<tr>
                  <td width="20%"><?php echo $lg_name;?></td>
				  <td width="1%">:</td>
                  <td width="79%"><?php echo $p3name;?></td>
                </tr>
                <tr>
                  <td><?php echo $lg_relation;?></td>
				  <td>:</td>
                  <td><?php echo $p3relation;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_handphone;?></td>
				  <td>:</td>
                  <td><?php echo $p3hp;?></td>
                </tr>
				<tr>
				  <td ><?php echo $lg_email;?></td>
				  <td>:</td>
				  <td><?php echo $p3mel;?></td>
			  </tr>
                <tr>
                  <td><?php echo $lg_address;?></td>
				  <td>:</td>
                  <td><?php echo $p3addr;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_telephone;?></td>
				  <td>:</td>
                  <td><?php echo $p3tel;?></td>
                </tr>
                <tr>
                  <td ><?php echo $lg_fax;?></td>
				  <td>:</td>
                  <td><?php echo $p3fax;?></td>
                </tr>
		</table>

</td>
<td id="myborder" width="50%" valign="top">
<div id="mytitlebg"><?php echo $lg_family_information;?></div>

          <table width="100%"  border="0">
                  <tr>
                    <td width="30%"><?php echo $lg_name;?></td>
                  	<td width="50%"><?php echo $lg_school_ipt_job;?></td>
                  	<td width="20%"><?php echo $lg_birth_year;?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q01";?></td>
                    <td><?php echo "$q02";?></td>
                    <td><?php echo "$q03";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q11";?></td>
                    <td><?php echo "$q12";?></td>
                    <td><?php echo "$q13";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q21";?></td>
                    <td><?php echo "$q22";?></td>
                    <td><?php echo "$q23";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q31";?></td>
                    <td><?php echo "$q32";?></td>
                    <td><?php echo "$q33";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q41";?></td>
                    <td><?php echo "$q42";?></td>
                    <td><?php echo "$q43";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q51";?></td>
                    <td><?php echo "$q52";?></td>
                    <td><?php echo "$q53";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q61";?></td>
                    <td><?php echo "$q62";?></td>
                    <td><?php echo "$q63";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q71";?></td>
                    <td><?php echo "$q72";?></td>
                    <td><?php echo "$q73";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q81";?></td>
                    <td><?php echo "$q82";?></td>
                    <td><?php echo "$q83";?></td>
                  </tr>
                  <tr>
                    <td><?php echo "$q91";?></td>
                    <td><?php echo "$q92";?></td>
                    <td><?php echo "$q93";?></td>
                  </tr>
              </table>
</td></tr></table>
</div></div>
</body>
</html>
