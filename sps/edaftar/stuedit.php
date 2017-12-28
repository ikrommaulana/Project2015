<?php
//Standard Edition
//09/07/10 - 4.1.0  
$vmod="v5.0.0";
$vdate="09/07/10";
	
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	include_once("$MYLIB/inc/language_$LG.php");
	

	$ic=$_REQUEST['ic'];
	$sid=$_REQUEST['sid'];
	$isforen1=$_REQUEST['isforen1'];
			$sql="select * from stureg where sch_id='$sid' and ic='$ic'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$num=mysql_num_rows($res);
			$id=$row['id'];
			if($num==0){
				$sql="select * from stu where  sch_id='$sid' and ic='$ic'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
			}
			$rdate=$row['rdate'];
			$regsid=$row['sch_id'];
			$sid2=$row['sid3'];
			if($sid2=="")
				$sid2=0;
			$sid3=$row['sid2'];
			if($sid3=="")
				$sid3=0;
			$daerah=$row['daerah'];
			$xid=$row['id'];
			$pt=$row['pt'];
			$nosb=$row['nosb'];
			$clslevel=$row['cls_level'];
			$sesyear=$row['sesyear'];
			$ptdate=$row['ptdate'];
			$uid=$row['uid'];
			$citizen=stripslashes($row['citizen']);
			$name=stripslashes($row['name']);
			$regic=$row['ic'];
			$bstate=$row['bstate'];
			$sex=$row['sex'];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=explode("-",$bday);
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=stripslashes($row['addr']);
			$addr1=stripslashes($row['addr1']);
			$addr2=stripslashes($row['addr2']);
			$state=$row['state'];
			$poskod=$row['poskod'];
			$bandar=$row['bandar'];
			$transport=$row['transport'];
			$ill=stripslashes($row['ill']);
			$sakit=explode("|",$ill);
			$status=$row['status'];
			$pschool=stripslashes($row['pschool']);
			$pschoolyear=$row['pschoolyear'];
			
			$p1name=stripslashes($row['p1name']);
			$p1ic=$row['p1ic'];
			$p1bstate=$row['p1bstate'];
			$p1job=$row['p1job'];
			$p1sal=$row['p1sal'];
			$p1com=stripslashes($row['p1com']);
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1state=$row['p1state'];
			$p1addr=stripslashes($row['p1addr']);
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			
			$p2name=stripslashes($row['p2name']);
			$p2ic=$row['p2ic'];
			$p2bstate=$row['p2bstate'];
			$p2job=$row['p2job'];
			$p2sal=$row['p2sal'];
			$p2com=stripslashes($row['p2com']);
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2state=$row['p2state'];
			$p2addr=stripslashes($row['p2addr']);
			$p2mel=$row['p2mel'];
			$p2hp=$row['p2hp'];
			
			$p3name=stripslashes($row['p3name']);
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3rel=$row['p3rel'];
			$p3state=$row['p3state'];
			$p3addr=stripslashes($row['p3addr']);
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			
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
			
			$pschool=stripslashes($row['pschool']);	
			$pschoolyear=$row['pschoolyear'];	

			$allergic=stripslashes($row['allergic']);
			$allergic_reaction=stripslashes($row['allergic_reaction']);
			$firstaid=stripslashes($row['firstaid']);
			$nameathome=stripslashes($row['nameathome']);
			$nameatschool=stripslashes($row['nameatschool']);
			$noinfamily=stripslashes($row['noinfamily']);
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
			$clssession=stripslashes($row['clssession']);
			$language1=stripslashes($row['language1']);
			$language2=stripslashes($row['language2']);
			$isnew=$row['isnew'];
			$isforen=$row['isforen'];
			
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
			$p1edu=$row['p1edu'];
			$p2edu=$row['p2edu'];
			$yatim=$row['yatim'];
			$jaraksekolah=$row['jaraksekolah'];
			
			$blood=$row['blood'];
			$beratmasuk=$row['beratmasuk'];
			$beratkeluar=$row['beratkeluar'];
			$tinggimasuk=$row['tinggimasuk'];
			$tinggikeluar=$row['tinggikeluar'];
			$cacat=$row['cacat'];
			$tahunkeluar=$row['tahunkeluar'];
			$sebabkeluar=$row['sebabkeluar'];
			$dateend=$row['dateend'];
			$naikkelas=$row['naikkelas'];
			$ulangkelas=$row['ulangkelas'];
			
			
			$sql="select * from sch where id=$regsid";
        	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        	$row=mysql_fetch_assoc($res);
        	$regsname=$row['name']; //school name
			$sch_lvl=$row['level'];
			
			
			$sql="select * from stureg_akademik where ic='$ic'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$s1=explode("|",$row['s1']);
				$s2=explode("|",$row['s2']);
				$s3=explode("|",$row['s3']);
				$s4=explode("|",$row['s4']);
				$s5=explode("|",$row['s5']);
				$s6=explode("|",$row['s6']);
				$s7=explode("|",$row['s7']);
				$s8=explode("|",$row['s8']);
				$s9=explode("|",$row['s9']);
				$s10=explode("|",$row['s10']);
				$s11=explode("|",$row['s11']);
				$s12=explode("|",$row['s12']);
				$s13=explode("|",$row['s13']);
				$s14=explode("|",$row['s14']);
				
				$sql="select * from sch where id=$sid2";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$sid2name=$row['name']; //school name
				$sql="select * from sch where id=$sid3";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$sid3name=$row['name']; //school name
		
		
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript">
function process_chkic(){
		var xmlhttp;
		var msg;
	
		if(document.myform.sid.value==""){
				alert("Please select school..");
				document.myform.sid.focus();
				return;
			}
		if(document.myform.ic.value==""){
				alert("Please enter ic number..");
				document.myform.ic.focus();
				return;
			}
		ic=document.myform.ic.value;
		sid=document.myform.sid.value;
			
	
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("div_chkic").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","div_chkic.php?sid="+sid+"&ic="+ic,true);
		xmlhttp.send();

}
function process_form(operation){
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}

		ret = confirm("Save the record / information ?")	
		if (ret == true){
			document.myform.action='stueditsave.php';
			document.myform.submit();
		}
}

function chkno(ele){
		var str=ele.value;
		if(isNaN(str)){
			alert("Invalid number.."+str);
			ele.value='';
			ele.focus();
			return;
		}
}

function chkic(){

		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.ic.value='';
			document.myform.sid.focus();
			return;
		}
		if(document.myform.isforen1.checked){
				var str=document.myform.ic.value;
				if(str.search(" ")>=0){
					alert("Invalid Passport number. Please remove any space.");
					document.myform.ic.value='';
					document.myform.ic.focus();
					return;
				}		
		}else{
				var str=document.myform.ic.value;
				if(str.search(" ")>=0){
					alert("Invalid IC Number. Please remove any space. Require 12 digits IC numbers. Eg 0801075251");
					document.myform.ic.value='';
					document.myform.ic.focus();
					return;
				}
				if(isNaN(str)){
					alert("Invalid number '"+str+"'. Require 12 digits IC numbers. Eg 0801075251");
					document.myform.ic.value='';
					document.myform.ic.focus();
					return;
				}
		
				if(str.length==12)
					process_chkic();
		}
}
function veric(){
	/**
	if(document.myform.isforen.checked==false){
			var str=document.myform.ic.value;
			if(str.length!=12){
				alert("Invalid IC Number. Lenght must be 12 digit numbers without space or '-'");
				document.myform.ic.focus();
				return false;
			}
	}
	**/
}

</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
    <link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
    <script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
	</head>

<body>

<form name="myform" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
	

<div id="content">



<div id="story" style="background-color:#FFFFFF;">

<div id="mytitle2">
	<?php echo $lg_student_information?>
</div>
<div id="mytitlebg" style=" color:#FF6600">

<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<div id="mytitlebg" style="background-color:#FFFF00 ">A. <?php echo strtoupper($lg_registration_information);?></div>
<table width="100%" id="mytitle" style="font-size:10px; background-color:#FFF;">
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">
					<?php echo strtoupper($lg_application_for_school);?>*
				</td>  
                <td width="80%" style="border-top:none;border-left:none;border-right:none;">
                  		<select name="sid">
                    	<?php	
                                if($regsid=="0")
                                    echo "<option value=\"\">- $lg_select -</option>";
                                else
                                    echo "<option value=$regsid>$regsname</option>";
								
								$sql="select * from sch where id!=$regsid and id<100 order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
                                    	$s=$row['name'];
                                    	$t=$row['id'];
                                        echo "<option value=$t>$s</option>";
								}
                    	?>
                      	</select>
				</td>
  			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo strtoupper("$lg_year $lg_intake");?>*
					</td>
				<td>
						<select name="sesyear">
						<?php
						if($sesyear>0) 
							echo "<option value=\"$sesyear\">$sesyear</option>";
						?>
							<option value="<?php echo date('Y')+1;?>"><?php echo date('Y')+1;?></option>
							<option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
						</select>
				</td>
			</tr>
            
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td >
					<?php echo strtoupper("$lg_class");?>*
				</td>  
                <td width="80%" > 
					<select name="clslevel">
					<?php
						if($clslevel>1) 
							echo "<option value=\"$clslevel\">$clslevel</option>";
					?>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
                        <option value="7">7</option>
					</select>
				</td>
  			</tr>
             
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''"  style="display:none;">
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo strtoupper($lg_preferred_session);?>*
					</td>
				<td>
						<select name="clssession">
						<?php
						if($clssession==$lg_morning){
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
						}elseif($clssession==$lg_afternoon){
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
						}else{
							echo "<option value=\"\">- $lg_select -</option>";
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
						}
						?>
						</select>
						<?php echo $lg_preferred_session_note;?>
				</td>
			</tr>
          </table>
<div id="mytitlebg">Asal Sekolah</div>
<table width="100%" cellspacing="0" style="background-color:#FFF;">
<tr><td id="myborder" width="50%" valign="top"  style="border-right:none;">
        <table width="100%" id="mytitle" style="font-size:10px;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;"> Sekolah Dasar </td>
                      <td><input type="text" name="pschool" size="48" value="<?php echo $pschool;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;"> No STTB</td>
                      <td><input type="text" name="sttb" value="<?php echo $sttb;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tanggal Berhenti</td>
                        <td><input type="text" name="stopdate" value="<?php echo $stopdate;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Lamanya Belajar</td>
                        <td><input type="text" name="yearinschool" value="<?php echo $yearinschool;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Nilai UASBN</td>
                        <td><input type="text" name="ussbn" value="<?php echo $ussbn;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Nilai UAN</td>
                        <td><input type="text" name="uan" value="<?php echo $uan;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Alasan Berpindah</td>
                        <td><input type="text" name="reasonleaving" size="48" value="<?php echo $reasonleaving;?>"></td>
                </tr>
        </table>
</td><td id="myborder" width="50%" valign="top"  style="border-right:none;">


	 <table width="100%" id="mytitle" style="font-size:10px; background-color:#FFF;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="30%"style="border-top:none;border-left:none;border-right:none;"> Sekolah Dasar Lainnya</td>
                      <td><input type="text" name="pschool2" size="48" value="<?php echo $pschool2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;"> No STTB</td>
                      <td><input type="text" name="sttb2" value="<?php echo $sttb2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tanggal Berhenti</td>
                        <td><input type="text" name="stopdate2" value="<?php echo $stopdate2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Lamanya Belajar</td>
                        <td><input type="text" name="yearinschool2" value="<?php echo $yearinschool2;?>"></td>
                </tr>
        </table>

</td></tr></table>        
<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<div id="mytitlebg" style="background-color:#FFFF00 ">B. <?php echo strtoupper($lg_student_information);?></div>
<table width="100%" cellspacing="0" style="background-color:#FFF;">
<tr><td id="myborder" width="50%" valign="top"  style="border-right:none; ">
		<table width="100%" id="mytitle" style="font-size:10px;">
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_student_name;?>*</td>
                  <td width="70%"><input name="name" type="text" id="name" value="<?php echo $name;?>" size="38"></td>
		</tr>
        <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Nama Panggilan</td>
                  <td width="70%"><input name="nick" type="text" value="<?php echo $nick;?>"></td>
		</tr>
        <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_citizen;?></td>
					<td>
                    <input type="text" name="citizen" value="<?php if($citizen=="") $citizen="$BASE_COUNTRY"; echo $BASE_COUNTRY?>" size="18">
                    
                    
                    </td>
  		</tr>
 		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_ic;?>/<?php echo $lg_birth_no;?>/<br>Passport*</td>
                  <td style="border-top:none;border-left:none;border-right:none;">
                  <input name="ic" type="text" id="ic" value="<?php echo $regic;?>" size="15"> 
                  (<?php echo $lg_no_space_and_dash;?>)
                  <div id="div_chkic" style="font-size:10px"></div>
                  </td>
		</tr>
        <!--
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_birth_no;?></td>
                  <td><input name="nosb" type="text" id="nosb" value="<?php echo $nosb;?>" size="20"></td>
		</tr>
        -->
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_birth_place;?>*
				  	</td>
					<td>
						<?php
					 if ($isforen1!='1')
					 
					 {?>
						<select name="bstate" id="bstate" >
                      <?php	
						if($state=="")
							echo "<option value=\"\">- $lg_birth_place -</option>";
						else
							echo "<option value=\"$bstate\">$bstate</option>";
						$sql="select * from state where name!='$bstate' order by name";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
					?>
                    </select>
						<?php }else {?>
					<input name="bstate" type="text" id="bstate" value="<?php echo $bstate;?>">
					<?php }?>
                    
                     <label style="cursor:pointer;">
                    	<input type="checkbox" name="isforen1" value="1" 
                        	onClick="document.myform.submit();
				if(document.myform.isforen1.checked)document.myform.bstate.value='';else document.myform.bstate.value='';"
                         <?php if($isforen1) echo "checked";?>> 
                       Tempat Lain
                     </label>
					</td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo $lg_birth_date;?>*
				  </td>
                  <td >
				  <select name="day" id="day">
<?php 
					if($d=="")
						echo "<option value=\"\">- $lg_day -</option>";
					else
						echo "<option value=\"$d\">$d</option>";
					for($i=1;$i<=31;$i++) 
							echo "<option value=\"$i\">$i</option>" 
?>
                    </select>
                    <select name="month" id="month">
<?php 
						if($m=="")
							echo "<option value=\"\">- $lg_month -</option>";
						else
							echo "<option value=\"$m\">$m</option>";
						for($i=1;$i<=12;$i++) 
							echo "<option value=\"$i\">$i</option>" 
?>
                    </select>
                      <select name="year" id="year">
                       
                        <?php 
							if($y=="")
								echo "<option value=\"\">- $lg_year -</option>";
							else
								echo "<option value=\"$y\">$y</option>";
							$yy=date("Y");
							$my=$yy-20;
							for($i=$yy;$i>$my;$i--) 
								echo "<option value=\"$i\">$i</option>" 
						?>
                      </select>     
		  </td>
		  </tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo $lg_sex;?>/<?php echo $lg_religion;?>*
				  </td>
                  <td >
				  	<select name="sex" id="sex">
						<?php	
						if($sex==""){
							echo "<option value=\"\">- $lg_sex -</option>";
							$sql="select * from type where grp='sex' order by val";
						}
						else{
							$sextype=$lg_malefemale[$sex];
							echo "<option value=\"$sex\"> $sextype </option>";
							$sql="select * from type where grp='sex' and prm!='$sex' order by val"; 	
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									$v=$row['val'];
									echo "<option value=\"$v\">$s</option>";
						}
					?>
			</select>
<!--            
			<select name="race" id="race" >
<?php	    		
			if($race==""){
				echo "<option value=\"\">- $lg_race -</option>";
				$sql="select * from type where grp='race' order by val";
			}
			else{
				echo "<option value=\"$race\">$race</option>";
				$sql="select * from type where grp='race' and prm!='$race' order by val"; 	
			}
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>
                  </select>
-->				  
				  <select name="religion" id="religion">
				  	 <?php	    		
			if($religion==""){
				echo "<option value=\"\">- $lg_religion -</option>";
				$sql="select * from type where grp='religion' order by val";
			}
			else{
				echo "<option value=\"$religion\">$religion</option>";
				$sql="select * from type where grp='religion' and prm!='$religion' order by val"; 	
			}
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
            }
?>
                  </select>
               </td>
  			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Anak Ke</td>
                  <td width="70%"><input name="anakke" type="text" value="<?php echo $anakke;?>"></td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Kandung</td>
                  <td width="70%"><input name="jumkandung" type="text" value="<?php echo $jumkandung;?>"></td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Tiri</td>
                  <td width="70%"><input name="jumtiri" type="text" value="<?php echo $jumtiri;?>"></td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Angkat</td>
                  <td width="70%"><input name="jumangkat" type="text" value="<?php echo $jumangkat;?>"></td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Tinggal Bersama</td>
                  <td width="70%"><input name="tinggalbersama" type="text" value="<?php echo $tinggalbersama;?>"></td>
			</tr>
                            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Golongan Darah</td>
                        <td><input type="text" size="8" name="blood" value="<?php echo $blood;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Kelainan Jasmani</td>
                        <td><input type="text" name="cacat" value="<?php echo $cacat;?>"></td>
                </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">                  
				  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_mobile;?>* (<?php echo $lg_parent;?>)
                  <td><input name="hp" type="text" id="hp" value="<?php echo $hp;?>" size="15" maxlength="10" onKeyUp="chkno(this);">
				  (<?php echo $lg_no_space_and_dash;?>)</td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  
				  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_email;?> (<?php echo $lg_parent;?>)
                  <td ><input name="mel" type="text" id="mel" value="<?php echo $mel;?>" size="38"><br>
						Eg. mel@yahoo.com</td>
			</tr>
		</table>
</td>
<td id="myborder" width="50%" valign="top" >

		<table width="100%" id="mytitle" style="font-size:10px;">
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  	<td width="30%" id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_home;?>
				  	<td width="70%"><input name="tel" type="text" id="tel" value="<?php echo $tel;?>" size="20" maxlength="10" onKeyUp="chkno(this);">
                   
                    </td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_home_address;?>*
				  </td>
      				<td ><input type="text" name="addr" value="<?php echo $addr?>" size="40"><?php echo $lg_line;?> 1</td>
	 		</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"></td>
      				<td ><input type="text" name="addr1" value="<?php echo $addr1?>" size="40"><?php echo $lg_line;?> 2</td>
	 		</tr>
	 		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_city;?>*
				  </td>
					<td><input type="text" name="bandar" value="<?php echo $bandar?>" size="40"></td>
  			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
						<?php echo $lg_postcode;?>*
				  	</td>
					<td>
						<input name="poskod" type="text"  value="<?php echo $poskod;?>" size="10" maxlength="5" onKeyUp="chkno(this);">
						<select name="state" id="state" >
						<?php	
							if($state=="")
								echo "<option value=\"\">- $lg_state -</option>";
							else
								echo "<option value=\"$state\">$state</option>";
							$sql="select * from state where name!='$state' order by name";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['name'];
										echo "<option value=\"$s\">$s</option>";
							}
							?>
   					  </select>    
	  				</td>
  			</tr>


			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" colspan="3" style="border-left:none;border-right:none;">
					<?php echo $lg_illness;?>
				  <table width="100%" cellpadding="0">
				  <tr>
					<?php 
					$sql="select * from type where grp='penyakit' order by idx,id";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						for($i=0;$i<count($sakit);$i++){
							$chk="";
							if($s==$sakit[$i]){
								$chk="checked";
								break;
							}
						}
						$sakitlain=$sakit[count($sakit)-1];
						if($sakitlain==$s)
							$sakitlain="";
						$w++;
						//if(($w%6)==0)
							//echo "<tr>";
					?>
					  
						<td width="10%"><label><input type="checkbox" name="sakit[]" value="<?php echo $s;?>"  <?php echo $chk;?>><?php echo $s;?></label></td>
					 
					 <?php 
					 if(($w%3)==0)
							echo "</tr><tr>";
					} ?>
					  </tr>
					</table><br>

				 &nbsp;<?php echo $lg_other;?> (<?php echo $lg_specify;?>)
				 &nbsp;<input name="sakitlain" type="text" value="<?php echo $sakitlain;?>" size="38"></td>
				</tr>
			 
             
             	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Jarak Rumah Dari Sekolah</td>
                        <td><input type="text" name="jaraksekolah" value="<?php echo $jaraksekolah;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Kenderaan Ke Sekolah</td>
                        <td><input type="text" name="transport" value="<?php echo $transport;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Bahasa Dirumah</td>
                        <td><input type="text" name="bahasa" value="<?php echo $bahasa;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Anak Yatim/Piatu/Yatim Piatu</td>
                        <td><input type="text" name="yatim" value="<?php echo $yatim;?>"></td>
                </tr>

                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Berat Badan (KG)</td>
                        <td>
                        <input type="text" size="8" name="beratmasuk" value="<?php echo $beratmasuk;?>"> Ketika Masuk
                        <input type="text" size="8" name="beratkeluar" value="<?php echo $beratkeluar;?>"> Ketika Keluar
                        </td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                		<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tinggi (CM)</td>
                       	<td>
                        <input type="text" size="8" name="tinggimasuk" value="<?php echo $tinggimasuk;?>"> Ketika Masuk
                        <input type="text" size="8" name="tinggikeluar" value="<?php echo $tinggikeluar;?>"> Ketika Keluar
                        </td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                		<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Gambar Ketika Masuk</td>
                       	<td><input type="file" name="pic">(Tidak melebihi 100KB)</td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                		<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Gambar Ketika Keluar</td>
                       	<td><input type="file" name="picend">(Tidak melebihi 100KB)</td>
                </tr>
                
		</table>
</td></tr></table>

<?php 
	include ('reg_parent_ex.php');
?>



<table width="100%">
<tr><td width="50%">
<td width="50%">
<input type="button" name="Submit2" value="<?php echo $lg_send_this_application;?>" 
onClick="return process_form('')"  style="font-weight:bold; color:#0000FF; width:100%; font-size:16px">
</td>
</tr>
</table>


</div></div>
</form>	
</body>



</html>
