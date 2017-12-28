<?php
//09/07/10 4.1.0 - clearwindow
$vmod="v5.0.0";
$vdate="09/07/10";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

		$username = $_SESSION['username'];
	  	$id=$_REQUEST['id'];
		$op=$_REQUEST['op'];
		$sid=$_REQUEST['sid'];
		$clssession=$_REQUEST['clssession'];
	  	$f=$_REQUEST['f'];

		if($op=="save"){
			$status=$_REQUEST['status'];
			$pt=$_REQUEST['pt'];
			if($status!=11){
				$sql="update stureg set sch_id=$sid,pt='$pt',status=$status,clssession='$clssession',adm='$username',ts=now() where id=$id";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$f="<font color=blue><b>&lt;SUCCESSFULLY UPDATE&gt;</b></font>";
			}
			
			if($status==11){
						$sql="select ic,sch_id from stureg where id='$id'";
						$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$ic=$row['ic'];
						$xsid=$row['sch_id'];
						$sql="select ic from stu where ic='$ic' and sch_id='$xsid'";
						$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
						$num=mysql_num_rows($res);
						if($num>0){
								$sql="update stureg set sch_id=$sid,pt='$pt',status=100,clssession='$clssession',adm='$username',ts=now() where id=$id";
								$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
								$f="<font color=blue><b>&lt;SUCCESSFULLY UPDATE STATUS. THIS IS EXISTING STUDENT&gt;</b></font>";
						}else{
								$sql="insert into stu(cdate,sch_id,uid,name,ic,sex,race,religion,bday,ill,tel,hp,mel,addr,state,
								 q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,
								 p1name,p1ic,p1job,p1sal,p1com,p1addr,p1tel,p1tel2,p1fax,p1hp,p1mel,
								 p2name,p2ic,p2job,p2sal,p2com,p2addr,p2tel,p2tel2,p2fax,p2hp,p2mel,
								 isislah,ishostel,rdate,edate,
								 transport,isstaff,isyatim,iskawasan,isfeenew,isfeeonanak,
								 isfeefree,feehutang,poskod,bandar,bstate,intake,
								 adm,ts,status) 
								 select now(),sch_id,uid,name,ic,sex,race,religion,bday,ill,tel,hp,mel,addr,state,
								 q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,
								 p1name,p1ic,p1job,p1sal,p1com,p1addr,p1tel,p1tel2,p1fax,p1hp,p1mel,
								 p2name,p2ic,p2job,p2sal,p2com,p2addr,p2tel,p2tel2,p2fax,p2hp,p2mel,
								 isislah,ishostel,now(),edate,
								 transport,isstaff,isyatim,iskawasan,isfeenew,isfeeonanak,
								 isfeefree,feehutang,poskod,bandar,bstate,sesyear,
								 '$username',now(),'6'
								 from stureg where id=$id";
		
								mysql_query($sql)or die("$sql query failed:".mysql_error());
								$xid=mysql_insert_id();
							
								/*if($si_student_global_id)
									$sql="update sch set stuid=stuid+1";
								else
									$sql="update sch set stuid=stuid+1 where id=$sid";
								mysql_query($sql)or die("$sql query failed:".mysql_error());
								$sql="select stuid,stuprefix from sch where id=$sid";
								$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
								$row=mysql_fetch_assoc($res);
								$c=$row['stuid'];
								$pref=$row['stuprefix'];
								$uid=sprintf("%s%04s",$pref,$c);
								$sql="update stu set uid='$uid' where id=$xid";
								mysql_query($sql)or die("$sql query failed:".mysql_error());
								*/
								
								$sql="update stureg set sch_id=$sid,pt='$pt',status=$status,clssession='$clssession',adm='$username',ts=now() where id=$id";
								$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
								$f="<font color=blue><b>&lt;SUCCESSFULLY UPDATE REGISTRATION&gt;</b></font>";
						}
				}
		}
	
		if($id!=""){
			$sql="select * from stureg where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$rdate=$row['rdate'];
			$sid=$row['sch_id'];
			$xid=$row['id'];
			$pt=$row['pt'];
			$ptdate=$row['ptdate'];
			$uid=$row['uid'];
			$name=stripslashes($row['name']);
			$citizen=stripslashes($row['citizen']);
			$ic=$row['ic'];
			$nosb=$row['nosb'];
			$bstate=$row['bstate'];
			$sex=$lg_malefemale[$row['sex']];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=$row['addr'];
			$bandar=$row['bandar'];
			$poskod=$row['poskod'];
			$state=$row['state'];
			$transport=$row['transport'];
			$ill=$row['ill'];
			$status=$row['status'];
			
			$p1name=$row['p1name'];
			$p1ic=$row['p1ic'];
			$p1bstate=$row['p1bstate'];
			$p1job=$row['p1job'];
			$p1sal=$row['p1sal'];
			$p1com=$row['p1com'];
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1state=$row['p1state'];
			$p1addr=$row['p1addr'];
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			$clssession=stripslashes($row['clssession']);
			
			$p2name=$row['p2name'];
			$p2ic=$row['p2ic'];
			$p2bstate=$row['p2bstate'];
			$p2job=$row['p2job'];
			$p2sal=$row['p2sal'];
			$p2com=$row['p2com'];
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2state=$row['p2state'];
			$p2addr=$row['p2addr'];
			$p2mel=$row['p2mel'];
			$p2hp=$row['p2hp'];
			
			$p3name=$row['p3name'];
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3state=$row['p3state'];
			$p3addr=$row['p3addr'];
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			
			$sendername=stripslashes($row['sendername']);
			$collectorname=stripslashes($row['collectorname']);
			$cartype=stripslashes($row['cartype']);
			$carno=stripslashes($row['carno']);
			$cartype2=stripslashes($row['cartype2']);
			$carno2=stripslashes($row['carno2']);
			$istransport=stripslashes($row['istransport']);
			$twoway=stripslashes($row['twoway']);
			$saddr=stripslashes($row['saddr']);
			$faddr=stripslashes($row['faddr']);
			
			$sttb=$row['sttb'];
			$stopdate=$row['stopdate'];
			$yearinschool=$row['yearinschool'];
			$ussbn=$row['ussbn'];
			$uan=$row['uan'];
			
			$reasonleaving=$row['reasonleaving'];
			$pschool2=$row['pschool2'];
			$sttb2=$row['sttb2'];
			$stopdate2=$row['stopdate2'];
			$yearinschool2=$row['yearinschool2'];
			$nick=$row['nick'];
			$bahasa=$row['bahasa'];
			$anakke=$row['anakke'];
			$jumkandung=$row['jumkandung'];
			$jumtiri=$row['jumtiri'];
			$jumangkat=$row['jumangkat'];
			$transport=$row['transport'];
			$tinggalbersama=$row['tinggalbersama'];
			$jaraksekolah=$row['jaraksekolah'];

			$p1edu=$row['p1edu'];
			$p2edu=$row['p2edu'];
			$yatim=$row['yatim'];
			
			$q0=stripslashes($row['q0']);
			list($q01,$q02,$q03)=split('[/.|]',$q0);
			$q1=stripslashes($row['q1']);
			list($q11,$q12,$q13)=split('[/.|]',$q1);
			$q2=stripslashes($row['q2']);
			list($q21,$q22,$q23)=split('[/.|]',$q2);
			$q3=stripslashes($row['q3']);
			list($q31,$q32,$q33)=split('[/.|]',$q3);
			$q4=stripslashes($row['q4']);
			list($q41,$q42,$q43)=split('[/.|]',$q4);
			$q5=stripslashes($row['q5']);
			list($q51,$q52,$q53)=split('[/.|]',$q5);
			$q6=stripslashes($row['q6']);
			list($q61,$q62,$q63)=split('[/.|]',$q6);
			$q7=stripslashes($row['q7']);
			list($q71,$q72,$q73)=split('[/.|]',$q7);
			$q8=stripslashes($row['q8']);
			list($q81,$q82,$q83)=split('[/.|]',$q8);
			$q9=stripslashes($row['q9']);
			list($q91,$q92,$q93)=split('[/.|]',$q9);
			
			$pschool=$row['pschool'];	
			$pschoolyear=$row['pschoolyear'];	

			
			mysql_free_result($res);
		}
		if($sid!=""){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$simg=$row['img'];
            mysql_free_result($res);					  
		}
		if($pt!=""){
			$sql="select * from type where grp='pusattemuduga' and val='$pt'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $ptname=$row['prm'];
            mysql_free_result($res);					  
		}
/**
		$sql="select * from type where grp='statusmohon' and val=$status";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $a=$row['prm'];
		$b=$row['val'];
**/
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $name;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body <?php if($f!=""){?>onLoad="opener.document.myform.curr.value=opener.document.myform.xcurr.value;opener.document.myform.submit();" <?php } ?> >

<form name="myform" method="post" action="">
	<input type="hidden" name="id" value="<?php echo "$id";?>">
	<input type="hidden" name="op" value="save">


<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if(is_verify('ADMIN')){?>
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="<?php echo $REG_FILE;?><?php echo "?id=$id&ic=$ic&e=1";?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/tool.png"><br>
<?php echo $lg_edit;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>
<?php echo $lg_print;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="stu_info.php?id=<?php echo $id;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a></a>
</div> <!-- end mymenu -->
</div> <!-- end mypanel -->
<div id="story">
<?php if(is_verify('ADMIN')){?>
<div id="mytitlebg">ADMINISTRATOR <?php echo $f;?></div>
<table width="100%" >
			<tr>
              <td width="10%" ><?php echo strtoupper("$lg_status");?></td>
			  <td width="1%" >:</td>
              <td width="89%">
			  <select name="status" id="status">
<?php	
			    $sql="select * from type where grp='statusmohon' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $a=$row['prm'];
				$b=$row['val'];
				mysql_free_result($res);	
                echo "<option value=\"$b\">$a</option>";				
				$sql="select * from type where grp='statusmohon' and val!=$status and val<100 order by val";

            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
            mysql_free_result($res);
?>
              </select>
              <?php if(is_verify("ADMIN")){?><input type="button" value="+" onClick="newwindow('../adm/prm.php?grp=statusmohon',0)"><?php } ?>
                 </td>
			</tr>
			  <tr>
                  <td><?php echo strtoupper("$lg_school");?></td>
                  <td >:</td>
                  <td  ><select name="sid" id="sid" >
                      <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if(is_verify('ADMIN')){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['name']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}					  			  
			
?>
                    </select>                  
					</td>
                </tr>

            </tr>
	  </table>
<?php } ?>
<table width="100%">
  <tr><td width="60%" valign="top" style="font-size:12px;">
  
<table width="100%" cellspacing="0">
  <tr><td width="50%" valign="top"  id="myborder">
			<div id="mytitlebg"><?php echo "$lg_student_information";?></div>
			<table width="100%" cellspacing="0" cellpadding="3" style="font-size:11px;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td width="30%"><?php echo "$lg_name";?></td>
                  <td width="1%" >:</td>
                  <td width="69%"><?php echo "$name";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td width="30%">Nama Penggilan</td>
                  <td width="1%" >:</td>
                  <td width="69%"><?php echo "$nick";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_ic_number";?></td>
                  <td >:</td>
                  <td><?php echo "$ic";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_birth_no";?></td>
                  <td >:</td>
                  <td><?php echo "$nosb";?></td>
                </tr>
		
		 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "NIS";?></td>
                  <td >:</td>
                  <td><?php echo "$uid";?></td>
                </tr>
		
		
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_sex";?></td>
                  <td >:</td>
                  <td><?php echo "$sex";?></td>
                </tr>
                <!--
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_race";?></td>
                  <td >:</td>
                  <td><?php echo "$race";?></td>
                </tr>
                -->
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_religion";?></td>
                  <td >:</td>
                  <td><?php echo "$religion";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_birth_date";?></td>
                  <td >:</td>
                  <td><?php echo "$bday";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td><?php echo "$lg_birth_place";?></td>
                  <td >:</td>
                  <td><?php echo "$bstate";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_citizen";?></td>
                  <td >:</td>
                  <td><?php echo "$citizen";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Anak Ke</td>
                  <td >:</td>
                  <td><?php echo "$anakke";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Jumlah Saudara Kandung</td>
                  <td >:</td>
                  <td><?php echo "$jumkandung";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Jumlah Saudara Tiri</td>
                  <td >:</td>
                  <td><?php echo "$jumtiri";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Jumlah Saudara Angkat</td>
                  <td >:</td>
                  <td><?php echo "$jumangkat";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Anak Yatim/Piatu/Yatim Piatu</td>
                  <td >:</td>
                  <td><?php echo "$yatim";?></td>
                </tr>
                 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Tinggal Bersama</td>
                  <td >:</td>
                  <td><?php echo "$tinggalbersama";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td ><?php echo $lg_illness;?></td>
					<td >:</td>
					<td><?php $ill=str_replace('|',',',$ill); echo "$ill";?></td>
				  </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_tel_home";?></td>
                  <td >:</td>
                  <td><?php echo "$tel";?> </td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td ><?php echo "$lg_tel_mobile";?> (<?php echo "$lg_parent";?>)</td>
                  <td >:</td>
                  <td><?php echo "$hp";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td >Email</td>
                  <td >:</td>
                  <td><?php echo "$mel";?></td>
                </tr>             
            </table>
                   
</td><td id="myborder" width="50%" valign="top">
			<div id="mytitlebg"> Asal Sekolah</div>
            

        <table width="100%" cellpadding="3" style="font-size:11px;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                      <td width="30%"> Sekolah Dasar </td>
                      <td width="1%">:</td>
                      <td><?php echo $pschool;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td> No STTB</td>
                        <td width="1%">:</td>
                      <td><?php echo $sttb;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Tanggal Berhenti</td>
                        <td width="1%">:</td>
                        <td><?php echo $stopdate;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Lamanya Belajar</td>
                        <td width="1%">:</td>
                        <td><?php echo $yearinschool;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Nilai UASBN</td>
                        <td width="1%">:</td>
                        <td><?php echo $ussbn;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Nilai UAN</td>
                        <td width="1%">:</td>
                        <td><?php echo $uan;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Alasan Berpindah</td>
                        <td width="1%">:</td>
                        <td><?php echo $reasonleaving;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                      <td> Sekolah Dasar Lainnya</td>
                      <td width="1%">:</td>
                      <td><?php echo $pschool2;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td> No STTB</td>
                        <td width="1%">:</td>
                      <td><?php echo $sttb2;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Tanggal Berhenti</td>
                        <td width="1%">:</td>
                        <td><?php echo $stopdate2;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td>Lamanya Belajar</td>
                        <td width="1%">:</td>
                        <td><?php echo $yearinschool2;?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td colspan="3" id="mytitlebg">Keterangan Tempat Tinggal</td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td valign="top"><?php echo $lg_address;?></td>
					  <td valign="top">:</td>
					  <td><?php echo "$addr<br>$addr1<br>$poskod $bandar";?></td>
				  </tr>
				  <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td ><?php echo $lg_state;?></td>
					<td >:</td>
					<td><?php echo "$state";?></td>
				  </tr>
				  <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td ><?php echo $lg_transport_to_school;?></td>
					<td >:</td>
					<td><?php echo "$transport";?></td>
				  </tr>
                  <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td >Jarak Rumah ke Sekolah</td>
					<td >:</td>
					<td><?php echo "$jaraksekolah";?></td>
				  </tr>
        </table>
            
            
            
            
		
</td>
</tr>
</table>

<table width="100%" cellspacing="0">
  <tr><td width="50%" valign="top"  id="myborder">
  		<div id="mytitlebg"><?php echo $lg_father_information;?></div>
			<table width="100%" cellpadding="3" style="font-size:11px;">
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td width="30%"><?php echo $lg_name;?></td>
              <td width="1%">:</td>
              <td width="69%"><?php echo $p1name;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_ic_number;?></td>
              <td>:</td>
              <td><?php echo $p1ic;?></td>
            </tr>
<!--
             <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_birth_place;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1bstate;?></td>
            </tr>
 -->
 			 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td >Pendidikan Tertinggi</td>
              <td width="1%" >:</td>
              <td><?php echo $p1edu;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_job;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1job;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo "$lg_salary ($lg_rm)";?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1sal;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_employer;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1com;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_address;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1addr;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_tel_mobile;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1hp;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_tel_home;?></td>
              <td>:</td>
              <td><?php echo $p1tel;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_tel_office;?></td>
              <td>:</td>
              <td><?php echo $p1tel2;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_fax;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p1fax;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td ><?php echo $lg_email;?></td>
			  <td width="1%" >:</td>
			  <td><?php echo $p1mel;?></td>
			</tr>
          </table>
	
</td><td width="50%" valign="top"  id="myborder">
		<div id="mytitlebg"><?php echo $lg_mother_information;?></div>
		<table width="100%" cellpadding="3" style="font-size:11px;">
           <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td width="30%"><?php echo $lg_name;?></td>
              <td width="1%">:</td>
              <td width="69%"><?php echo $p2name;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_ic_number;?></td>
              <td>:</td>
              <td><?php echo $p2ic;?></td>
            </tr>
  <!--
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_birth_place;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2bstate;?></td>
            </tr>
    -->
    		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td >Pendidikan Tertinggi</td>
              <td width="1%" >:</td>
              <td><?php echo $p2edu;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_job;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2job;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo "$lg_salary ($lg_rm)";?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2sal;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_employer;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2com;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_address;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2addr;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_tel_mobile;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2hp;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_tel_home;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2tel;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_tel_office;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2tel2;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td ><?php echo $lg_fax;?></td>
              <td width="1%" >:</td>
              <td><?php echo $p2fax;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td >Email</td>
			  <td width="1%" >:</td>
			  <td><?php echo $p2mel;?></td>
			</tr>
          </table> 

		
</td>
</tr>
</table>


<table width="100%">
  <tr><td width="50%" valign="top" style="font-size:12px;">
  		    
		  <div id="mytitlebg"><?php $lg_family_information;?></div>
			  <table width="100%" cellpadding="3" style="font-size:11px;">
                <tr bgcolor="#FAFAFA">
                  <td id="mytitlebg" width="3%"><?php echo $lg_no;?></td>
				  <td id="mytitlebg" width="30%"><?php echo $lg_name;?></td>
                  <td id="mytitlebg" width="40%"><?php echo $lg_school_ipt_job;?></td>
                  <td id="mytitlebg" width="20%"><?php echo $lg_birth_year;?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">1.</td>
                  <td><?php echo "$q01";?></td>
                  <td><?php echo "$q02";?></td>
                  <td><?php echo "$q03";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">2.</td>
                  <td><?php echo "$q11";?></td>
                  <td><?php echo "$q12";?></td>
                  <td><?php echo "$q13";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">3.</td>
                  <td><?php echo "$q21";?></td>
                  <td><?php echo "$q22";?></td>
                  <td><?php echo "$q23";?></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">4.</td>
                  <td><?php echo "$q31";?></td>
                  <td><?php echo "$q32";?></td>
                  <td><?php echo "$q33";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">5.</td>
                  <td><?php echo "$q41";?></td>
                  <td><?php echo "$q42";?></td>
                  <td><?php echo "$q43";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">6.</td>
                  <td><?php echo "$q51";?></td>
                  <td><?php echo "$q52";?></td>
                  <td><?php echo "$q53";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">7.</td>
                  <td><?php echo "$q61";?></td>
                  <td><?php echo "$q62";?></td>
                  <td><?php echo "$q63";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">8.</td>
                  <td><?php echo "$q71";?></td>
                  <td><?php echo "$q72";?></td>
                  <td><?php echo "$q73";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">9.</td>
                  <td><?php echo "$q81";?></td>
                  <td><?php echo "$q82";?></td>
                  <td><?php echo "$q83";?></td>
                </tr>
				<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				  <td bgcolor="#FAFAFA">10.</td>
                  <td><?php echo "$q91";?></td>
                  <td><?php echo "$q92";?></td>
                  <td><?php echo "$q93";?></td>
                </tr>
			</table>
            
</td><td width="50%" valign="top"  id="myborder">
		<div id="mytitlebg"><?php echo $lg_for_emergency_call;?></div>
          <table width="100%" cellpadding="3" style="font-size:11px;">
          <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td width="30%"><?php echo $lg_name;?></td>
			  <td width="1%" >:</td>
              <td width="69%"><?php echo $p3name;?></td>
            </tr>
 <!--
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_ic_number;?></td>
              <td>:</td>
              <td><?php echo $p3ic;?></td>
            </tr>
   -->
			 <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_relation;?></td>
              <td>:</td>
              <td><?php echo $p3rel;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_address;?></td>
              <td>:</td>
              <td><?php echo $p3addr;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_handphone;?></td>
              <td>:</td>
              <td><?php echo $p3hp;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_telephone;?></td>
              <td>:</td>
              <td><?php echo $p3tel;?></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_telephone;?> (<?php echo $lg_office;?>)</td>
              <td>:</td>
              <td><?php echo $p3tel2;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
              <td><?php echo $lg_fax;?></td>
              <td>:</td>
              <td><?php echo $p3fax;?></td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
			  <td >Email</td>
			  <td>:</td>
			  <td><?php echo $p3mel;?></td>
			</tr>
          </table>
		
</td>
</tr>
</table>            
	



</div></div>
</form>
</body>
</html>
