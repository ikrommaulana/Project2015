<?php
/**
$df=explode("-","1976-01-03");
$dt=explode("-","2013-08-07");

//gregoriantojd(month, day, year);
$start=gregoriantojd($df[1], $df[2], $df[0]);
$end=gregoriantojd($dt[1], $dt[2], $dt[0]);

$days=$end-$start;
$year=floor($days/365);
$monthday=$days%365;
$month=round($monthday/30);

echo "$year.$month";
**/


//Standard Edition
//09/07/10 - 4.1.0  
$vmod="v5.0.0";
$vdate="09/07/10";
	
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	include_once("$MYLIB/inc/language_$LG.php");
	
    $sql="select * from type where grp='openexam' and prm='EPENDAFTARAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1"){
		if(!is_verify("ADMIN|AKADEMIK|KEWANGAN|GURU|HR|OPERATOR"))
			echo "<script language=\"javascript\">location.href='close.php'</script>";
	}
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=0;

	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $regsname=stripslashes($row['name']); //school name
		$sname=stripslashes($row['sname']); //school name
		$sch_lvl=$row['level'];
    }
	$ic=$_REQUEST['ic'];
	$id=$_REQUEST['id'];
	$op=$_REQUEST['op'];
	$clslevel=$_REQUEST['clslevel'];
	$sesyear=$_REQUEST['sesyear'];
	$name=$_REQUEST['name'];
	$d=$_REQUEST['day'];
	$m=$_REQUEST['month'];
	$y=$_REQUEST['year'];
	
	$bday="$y-$m-$d";
	
	$SHOW_MAIN_FORM=0;
	
	$isforen1=$_REQUEST['isforen1'];
	if($isforen1){$SHOW_MAIN_FORM=1;}
	if($op=="semak"){
		
		$sql="select count(*) from stureg where ic='$ic' and sch_id='$sid' and sesyear='$sesyear' and isdel=0";
		$res=mysql_query($sql) or die("$sql error:".mysql_error());
		$row=mysql_fetch_row($res);
		$num=$row[0];
		if($num>0){
				$ayat="Harap Maaf. Nomor Akte Lahir/Passport '$ic' sudah digunakan.
					<br>Jika nomornya benar, lanjutkan untuk memeriksa status.";
		}else{
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm='$clslevel'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$cuttoff_total=$row['etc'];
			$cuttoff_bday=$row['des'];
				if($cuttoff_total=="0"){
                             $ayat="HARAP MAAF. PERMOHONAN DITUTUP";
                }
				elseif($cuttoff_total>"0"){			
					$sql="select count(*) from stureg where sch_id='$sid' and cls_level='$clslevel' and sesyear='$sesyear' and isdel=0";
					$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
					$row=mysql_fetch_row($res);
					$totalstu=$row[0];
					//echo "DD".$totalstu;
					$kekosongan=$cuttoff_total-$totalstu;
					if($cuttoff_total>$totalstu){
							if($cuttoff_bday>0){	
									//kira jika ada cuff_off bday
									//gregoriantojd(month, day, year);
									$yearschool=explode("/",$sesyear);
									$start=gregoriantojd($m, $d, $y);
									$end=gregoriantojd("6", "30", $yearschool[0]);
									$xdays=$end-$start;
									$xyear=floor($xdays/365);
									$xmonthday=$xdays%365;
									$xmonth=sprintf("%02d",round($xmonthday/30));
									$age="$xyear.$xmonth";
									//echo "DD:$age : $end-$start ::$m, $d, $y +". $yearschool[1];
									if(floatval($age)>=floatval($cuttoff_bday)){
											$SHOW_MAIN_FORM=1;
											$ayat="Tersedia peluang untuk $kekosongan pendaftar. Silahkan isi informasi di bawah ini."; //($cuttoff_bday:$age)//
									}else{
										if($sname=="SDU")
												$ayat="Maaf. Usia anak anda belum mencukupi"; // $yearschool[0] ($cuttoff_bday:$age)//
										else
												$ayat="Maaf. Usia anak anda belum mencukupi"; // $yearschool[0] ($cuttoff_bday:$age)//
									}	
							}else{
									$SHOW_MAIN_FORM=1;
									$ayat="Tersedia peluang untuk $kekosongan pendaftar. Silahkan isi informasi di bawah ini.";
							}
						
					}else{
						
						$ayat="HARAP MAAF. PERMOHONAN SUDAH PENUH";
					}
			}else{
					$SHOW_MAIN_FORM=1;	
					$ayat="Silahkan isi informasi dibawah ini";
			}
		}//check duplicate ic
	}
	
	if((($id!="")||($ic!=""))&&($op!="semak")){
			
			$sql="select * from stureg where id='$id' and ic='$ic'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$num=mysql_num_rows($res);
			//$id=$row['id'];
			if($num==0){
					$sql="select * from stu where ic='$ic'";
					$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$num=mysql_num_rows($res);
			}
			if($num>0){
				$SHOW_MAIN_FORM=1;	
					$rdate=$row['rdate'];
					$sid=$row['sch_id'];
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
					$nisn=$row['nisn'];
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
					$rt=$row['rt'];
					$rw=$row['rw'];
					$kecamatan=$row['kecamatan'];
					$kelurahan=$row['kelurahan'];
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
					
					
					$sql="select * from sch where id=$sid";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$regsname=stripslashes($row['name']); //school name
					$sch_lvl=$row['level'];
					
	
						
						$sql="select * from sch where id=$sid2";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$sid2name=$row['name']; //school name
						$sql="select * from sch where id=$sid3";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$sid3name=$row['name']; //school name
			}
		}else{
			
			include("../edaftar/reg_save.php");//get data from get request
		}
		
		if(($isnew=="")||($isnew=="1"))
				$isnewchecked="checked";
		else
				$isoldchecked="checked";
		
		
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
function process_semak(operation){
		if(document.myform.sid.value==""){
			alert("Pilih Sekolah");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.sesyear.value==""){
			alert("Masukkan tahun angkatan");
			document.myform.sesyear.focus();
			return;
		}
		if(document.myform.name.value==""){
			alert("Masukkan nama siswa");
			document.myform.name.focus();
			return;
		}
		if(document.myform.ic.value==""){
			alert("Masukkan NO KTP / NO. AKTA LAHIR/ PASSPORT");
			document.myform.ic.focus();
			return;
		}
		if(document.myform.day.value==""){
			alert("Masukkan tarikh lahir");
			document.myform.day.focus();
			return;
		}
		if(document.myform.month.value==""){
			alert("Maasukkan bulan lahir");
			document.myform.month.focus();
			return;
		}
		if(document.myform.year.value==""){
			alert("Masukkan tahun lahir");
			document.myform.year.focus();
			return;
		}
		if(document.myform.clslevel.value==""){
			alert("Pilih kelas");
			document.myform.clslevel.focus();
			return;
		}
		
		document.myform.op.value='semak';
		document.myform.submit();
		
}
function process_form(operation){
		if(document.myform.sid.value==""){
			alert("Pilih sekolah");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.name.value==""){
			alert("Masukkan nama siswa");
			document.myform.name.focus();
			return;
		}
		if(document.myform.isforen1.checked){
				if(document.myform.ic.value==""){
					alert("Masukkan KTP/Passport/Akta Lahir");
					document.myform.ic.focus();
					return;
				}
		}else{
				if(document.myform.ic.value==""){
					alert("Masukkan KTP/Passport/Akta Lahir");
					document.myform.ic.focus();
					return;
				}

		}
		if(document.myform.sex.value==""){
			alert("Pilih jenis kelamin");
			document.myform.sex.focus();
			return;
		}

		if(document.myform.religion.value==""){
			alert("Masukkan agama");
			document.myform.religion.focus();
			return;
		}
		if(document.myform.day.value==""){
			alert("Masukkan tanggal lahir");
			document.myform.day.focus();
			return;
		}
		if(document.myform.month.value==""){
			alert("Masukkan bulan tanggal lahir");
			document.myform.month.focus();
			return;
		}
		if(document.myform.year.value==""){
			alert("Masukkan tahun tanggal lahir");
			document.myform.year.focus();
			return;
		}
		if(document.myform.addr.value==""){
			alert("Masukkan alamat");
			document.myform.addr.focus();
			return;
		}
		if(document.myform.bandar.value==""){
			alert("Masukkan kota");
			document.myform.bandar.focus();
			return;
		}
		if(document.myform.poskod.value==""){
			alert("Masukkan kode pos");
			document.myform.poskod.focus();
			return;
		}
		if(document.myform.state.value==""){
			alert("Masukkan provinsi");
			document.myform.state.focus();
			return;
		}
		if(document.myform.hp.value==""){
			alert("Masukkan no HP (Orang tua)");
			document.myform.hp.focus();
			return;
		}
		if(document.myform.p1name.value==""){
			alert("Masukkan nama ayah");
			document.myform.p1name.focus();
			return;
		}
		if(document.myform.p1ic.value==""){
			alert("Masukkan KTP ayah");
			document.myform.p1ic.focus();
			return;
		}
		if(document.myform.p1job.value==""){
			alert("Masukkan pekerjaan ayah");
			document.myform.p1job.focus();
			return;
		}
		if(document.myform.p1sal.value==""){
			alert("Masukkan gaji ayah");
			document.myform.p1sal.focus();
			return;
		}
		if(document.myform.p2sal.value==""){
			alert("Masukkan gaji ibu ");
			document.myform.p2sal.focus();
			return;
		}
		if(document.myform.p3name.value==""){
			alert("Masukkan nama untuk dihubungi (selain orang tuan(");
			document.myform.p3name.focus();
			return;
		}
		if(document.myform.p3rel.value==""){
			alert("Masukkan hubungannya");
			document.myform.p3rel.focus();
			return;
		}
		if(document.myform.p3hp.value==""){
			alert("Masukkan no HP");
			document.myform.p3hp.focus();
			return;
		}
		if(document.myform.pengesahan.checked==false){
			alert("Sila tandakan pengesahan");
			document.myform.pengesahan.focus();
			return;
		}
		ret = confirm("Kirim formulir ini ?")	
		if (ret == true){
			document.myform.p.value='reg_save';
			document.myform.op.value='save';
			document.myform.action='reg_save.php';
			document.myform.submit();
		}
}

function chkno(ele){
		var str=ele.value;
		if(isNaN(str)){
			alert("Nomor tidak sah.."+str);
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
		if(document.myform.isforen.checked){
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


function process_kota(op,id){
		var xmlhttp;
		var bstate;
		

		bstate=document.myform.bstate.value;
	
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("ajxkota").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","../edaftar/ajxkota.php?xstate="+bstate,true);
		xmlhttp.send();

}

</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>

<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>
<!-- SETTING FANCYBOX -->
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!-- My SETTING -->
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>	
<script type="text/javascript" src="<?php echo $MYLIB;?>/inc/myfancybox.js" type="text/javascript"></script>

</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 	<input type="hidden" name="p" value="reg">
    <input type="hidden" name="op">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="e" value="<?php echo $e;?>">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
	

<div id="content">



<div id="story" style="background-color:#FFFFFF;">

<div id="mytitle2">
	<?php echo $lg_online_student_registration_form;?> - <?php echo $organization_name;?>
</div>
<div id="mytitlebg" style=" color:#FF6600">

<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<div id="mytitlebg" style="background-color:#FFFF00 "><?php echo strtoupper($lg_registration_information);?></div>
<table width="100%" id="mytitle" style="font-size:10px; background-color:#FFF;">
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
				<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">
					<?php echo strtoupper($lg_application_for_school);?>*
				</td>  
                <td width="80%" style="border-top:none;border-left:none;border-right:none;">
                  		<select name="sid" onChange="document.myform.clslevel[0].value='';document.myform.submit();">
                    	<?php	
                                if($sid=="0")
                                    echo "<option value=\"\">- $lg_select -</option>";
                                else
                                    echo "<option value=$sid>$regsname</option>";								
								$sql="select * from sch where id!=$sid and id<100 order by name";
								$res=mysql_query($sql)or die("query failed:".mysql_error());
								while($row=mysql_fetch_assoc($res)){
                                    	$s=stripslashes($row['name']);
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
								if($sesyear!="")
									echo "<option value=\"$sesyear\">$sesyear</option>";
								else
									echo "<option value=\"\">- $lg_select -</option>";
								$sql="select * from type where grp='session' order by val desc";
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
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo strtoupper($lg_student_name);?>*</td>
                  <td width="70%"><input name="name" type="text" id="name" value="<?php echo $name;?>" size="38"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                      <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo strtoupper("$lg_birth_no / Passport $lg_student");?>*</td>
                      <td style="border-top:none;border-left:none;border-right:none;">
                      <input name="ic" type="text" id="ic" value="<?php echo $ic;?>" size="20"> 
                      <!--(<?php echo $lg_no_space_and_dash;?>)-->
                      <div id="div_chkic" style="font-size:10px"></div>
                      </td>
            </tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo strtoupper($lg_birth_date);?>*
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
				<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo strtoupper("$lg_class");?>*</td>  
                <td width="80%" >
                <select name="clslevel" onchange="process_semak();">
				<?php    
                    if($clslevel=="")
                        			echo "<option value=\"\">- $lg_select $lg_class -</option>";
                    else
                        			echo "<option value=\"$clslevel\">$lg_class $clslevel</option>";
                    $sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                    while($row=mysql_fetch_assoc($res)){
                                    $s=$row['prm'];
                                    echo "<option value=\"$s\">$lg_class $s</option>";
                    }
            	?>
                  </select>
				</td>
  			</tr>
             

<?php if(!$SHOW_MAIN_FORM){?>          
          <!--<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                      <td></td>
                      <td><input type="button" value="Lanjut" onClick="process_semak();"></td>
            </tr>-->
			<tr><td colspan="2" align="center"><font color="red">** SILAHKAN ISI DATA DI ATAS UNTUK MELENGKAPI INFORMASI YANG MASIH KOSONG</font></td></tr>
<?php } ?>            


</table>

<div id="ayat" style="font-size:14px; color:#03F;"> <?php echo $ayat;?> </div>


<div id="main_form" <?php if(!$SHOW_MAIN_FORM) echo "style=\"display:none;\"";?> > 



<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>

<table width="100%" cellspacing="0" style="background-color:#FFF;">
<tr><td id="myborder" width="50%" valign="top"  style="border-right:none;">
	<div id="mytitlebg" style="background-color:#FFFF00 ">A. ASAL SEKOLAH </div>
        <table width="100%" id="mytitle" style="font-size:10px;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Nama Sekolah </td>
                      <td><input type="text" name="pschool" size="48" value="<?php echo $pschool;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;"> No. Ijazah</td>
                      <td><input type="text" name="sttb" value="<?php echo $sttb;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tanggal Lulus</td>
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
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">NIS</td>
                  <td width="70%"><input name="uid" type="text" id="uid" value="<?php echo $uid;?>"></td>
            </tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">NISN</td>
                  <td width="70%"><input name="nisn" type="text" id="nisn" value="<?php echo $nisn;?>"></td>
            </tr> 
        </table>
</td><td id="myborder" width="50%" valign="top"  style="border-right:none;">

	<div id="mytitlebg" style="background-color:#FFFF00 ">B. MUTASI (Diisi Khusus untuk Murid Pindahan)</div>
	 <table width="100%" id="mytitle" style="font-size:10px; background-color:#FFF;">
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Nama Sekolah</td>
                      <td><input type="text" name="pschool2" size="48" value="<?php echo $pschool2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;"> No. Ijazah</td>
                      <td><input type="text" name="sttb2" value="<?php echo $sttb2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tanggal Lulus</td>
                        <td><input type="text" name="stopdate2" value="<?php echo $stopdate2;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Lamanya Belajar</td>
                        <td><input type="text" name="yearinschool2" value="<?php echo $yearinschool2;?>"></td>
                </tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Alasan Berpindah</td>
                        <td><input type="text" name="reasonleaving" size="48" value="<?php echo $reasonleaving;?>"></td>
                </tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Mutasi Ke Kelas</td>
                        <td><input type="text" name="naikkelas" value="<?php echo $naikkelas;?>"></td>
                </tr>
        </table>

</td></tr></table>        

<div id="myborder" style="border-color:#333333; border-bottom:none;"></div>
<div id="mytitlebg" style="background-color:#FFFF00 ">C. <?php echo strtoupper($lg_student_information);?></div>
<table width="100%" cellspacing="0" style="background-color:#FFF;">
<tr><td id="myborder" width="50%" valign="top"  style="border-right:none; ">
		<table width="100%" id="mytitle" style="font-size:10px;">
        <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Nama Panggilan</td>
                  <td width="70%"><input name="nick" type="text" value="<?php echo $nick;?>"></td>
		</tr>
        <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_citizen;?></td>
					<td>
                    <input type="text" name="citizen" value="<?php if($citizen=="") $citizen="$BASE_COUNTRY"; echo $BASE_COUNTRY?>" size="18">
                   <!--  <label style="cursor:pointer;">
                    	<input type="checkbox" name="isforen" value="1" 
                        	onClick="if(document.myform.isforen.checked)document.myform.citizen.value='';else document.myform.citizen.value='<?php echo $BASE_COUNTRY;?>';"
                         <?php if($isforen) echo "checked";?>> 
                        Foreign Student
                     </label>
                    
                    </td> -->
  		</tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_birth_no;?></td>
                  <td><input name="nosb" type="text" id="nosb" value="<?php echo $nosb;?>" size="20"></td>
		</tr>
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_birth_place;?>*
				  	</td>
					<td>
					<?php
					 if ($isforen1!='1')
					 
					 {?>
					<select name="bstate" id="bstate">
                      <?php
		      
						if($bstate=="")
							echo "<option value=\"\">- $lg_birth_place -</option>";
						else
							echo "<option value=\"$bstate\">$bstate</option>";
						//$sql="select * from state where name!='$bstate' order by name";
						//$sql="select distinct(state) from daerah where state!='$bstate' order by state";
                                                $sql="select * from daerah where state!='$bstate' order by name";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['name'];
									echo "<option value=\"$s\">$s</option>";
						}
					?>
                    </select>  
			<?php }else {?>
					<input name="bstate" type="text" id="bstate" value="<?php echo $state;?>">
					<?php }?>
                    
                     <label style="cursor:pointer;">
                    	<input type="checkbox" name="isforen1" value="1" 
                        	onClick="document.myform.submit();
				if(document.myform.isforen1.checked)document.myform.bstate.value='';else document.myform.bstate.value='';"
                         <?php if($isforen1) echo "checked";?>> 
			Checklist untuk tempat lain
                     </label>
                
					</td>
			</tr>
<!--
        <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
		<td id="myborder" style="border-top:none;border-left:none;border-right:none;">Kabupaten/Kota *</td>
		<td><div id="ajxkota"><?php include("../edaftar/ajxkota.php");?></div></td>
	</tr>
-->             
		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
				  <?php echo $lg_sex;?> - <?php echo $lg_religion;?>*
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
                  <td width="70%">
                  	<select name="anakke">
					<?php 
					if($anakke=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$anakke\">$anakke</option>";
					for($i=1;$i<=16;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    </td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Kandung</td>
                  <td width="70%">
                  <select name="jumkandung">
					<?php 
					if($jumkandung=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$jumkandung\">$jumkandung</option>";
					for($i=0;$i<=16;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    </td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Tiri</td>
                  <td width="70%">
                  <select name="jumtiri">
					<?php 
					if($jumtiri=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$jumtiri\">$jumtiri</option>";
					for($i=0;$i<=16;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    </td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Jumlah Saudara Angkat</td>
                  <td width="70%">
                  <select name="jumangkat">
					<?php 
					if($jumangkat=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$jumangkat\">$jumangkat</option>";
					for($i=0;$i<=16;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                    </td>
			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  <td id="myborder" width="30%" style="border-top:none;border-left:none;border-right:none;">Tinggal Bersama</td>
                  <td width="70%"><input name="tinggalbersama" type="text" value="<?php echo $tinggalbersama;?>"></td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">                  
				  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_mobile;?>* (<?php echo $lg_parent;?>)
                  <td><input name="hp" type="text" id="hp" value="<?php echo $hp;?>" size="20" onKeyUp="chkno(this);"><br>
				  (<?php echo $lg_no_space_and_dash;?>)</td>
			</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  
				  <td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_email;?> (<?php echo $lg_parent;?>)
                  <td ><input name="mel" type="text" id="mel" value="<?php echo $mel;?>" size="38"><br>
						Contoh: andi@yahoo.com</td>
			</tr>
		</table>
</td>
<td id="myborder" width="50%" valign="top" >

		<table width="100%" id="mytitle" style="font-size:10px;">
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                  	<td width="30%" id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo $lg_tel_home;?>
				  	<td width="70%"><input name="tel" type="text" id="tel" value="<?php echo $tel;?>" size="20" onKeyUp="chkno(this);">
                   
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
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo "$lg_rt/$lg_rw";?></td>
      				<td ><input type="text" name="rt" value="<?php echo $rt?>" size="5">/<input type="text" name="rw" value="<?php echo $rw?>" size="5"></td>
	 		</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_kelurahan;?></td>
      				<td ><input type="text" name="kelurahan" value="<?php echo $kelurahan?>" size="40"></td>
	 		</tr>
			<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;"><?php echo $lg_kecamatan;?></td>
      				<td ><input type="text" name="kecamatan" value="<?php echo $kecamatan?>" size="40"></td>
	 		</tr>
	 		<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
					<?php echo "$lg_city/$lg_kab";?>*
				  </td>
					<td><input type="text" name="bandar" value="<?php echo $bandar?>"></td>
  			</tr>
            <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
					<td id="myborder" style="border-top:none;border-left:none;border-right:none;">
						<?php echo $lg_postcode;?>*
				  	</td>
					<td>
						<input name="poskod" type="text"  value="<?php echo $poskod;?>" size="10"  onKeyUp="chkno(this);">
						
						<select name="state" id="state" >
						<?php	
							if($state=="")
								echo "<option value=\"\">- $lg_state -</option>";
							else
								echo "<option value=\"$state\">$state</option>";
							//$sql="select * from state where name!='$state' order by name";
                                                        $sql="select distinct(state) from daerah where state!='$state' order by state";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
										$s=$row['state'];
										echo "<option value=\"$s\">$s</option>";
							}
							?>
   					  </select><br>
					  <a href="http://www.posindonesia.co.id/index.php/loginall-comuser-views/pencarian-kodepos" class="fbbig" target="_blank">Cari Kode Pos</a>
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
                        <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Kelainan Jasmani</td>
                        <td><input type="text" name="cacat" value="<?php echo $cacat;?>"></td>
                </tr>
             	<tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Jarak Dari Rumah Ke Sekolah (KM)</td>
                        <td><input type="text" name="jaraksekolah" value="<?php echo $jaraksekolah;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" style="border-top:none;border-left:none;border-right:none;">Kendaraan Ke Sekolah</td>
                        <td><input type="text" name="transport" value="<?php echo $transport;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td style="border-top:none;border-left:none;border-right:none;">Bahasa Di Rumah</td>
                        <td><input type="text" name="bahasa" value="<?php echo $bahasa;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td style="border-top:none;border-left:none;border-right:none;">Anak Yatim/Piatu/Yatim Piatu</td>
                        <td><input type="text" name="yatim" value="<?php echo $yatim;?>"></td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                        <td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Berat Badan</td>
                        <td>
                        <select name="beratmasuk">
					<?php 
					if($beratmasuk=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$beratmasuk\">$beratmasuk</option>";
					for($i=1;$i<=220;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select> (Kg)
                        </td>
                </tr>
                <tr onMouseOver="this.bgColor='#FAFAFA'" onMouseOut="this.bgColor=''">
                		<td id="myborder" width="20%"style="border-top:none;border-left:none;border-right:none;">Tinggi</td>
                       	<td>
                        
                        <select name="tinggimasuk">
					<?php 
					if($tinggimasuk=="")
						echo "<option value=\"\">- $lg_select-</option>";
					else
						echo "<option value=\"$tinggimasuk\">$tinggimasuk</option>";
					for($i=1;$i<=250;$i++) 
							echo "<option value=\"$i\">$i</option>" 
					?>
                    </select>
                     (Cm)
                        </td>
                </tr>
		</table>
</td></tr></table>

<?php 
	include ('reg_parent.php');
?>
<?php 
if($EREG_SHOW_ACADEMIC)
	include ('reg_akademik.php');
?>
<?php 
if($EREG_QUESTIONAIR)
	include ('reg_questionair.php');
?>



<div style="border:1px solid #000">
<table width="100%" style="font-size:12px; color:#03F" bgcolor="#FFFF00">
<tr><td width="50%">

	<label style="cursor:pointer">**<input type="checkbox" name="pengesahan" value="1"><u>PENGESAHAN</u></label><br>
	Saya setuju, bahwa semua informasi yang diberikan adalah benar.<br>
	Sekolah berhak untuk menolak tanpa pemberitahuan sebelumnya. Terima kasih.
</td>
<td width="50%">
<input type="button" name="Submit2" value="<?php echo $lg_send_this_application;?>" 
onClick="return process_form('')"  style="font-weight:bold; color:#0000FF; width:100%; height:60px; font-size:16px">
</td>
</tr>
</table>
</div>
<br>
<br>
</div><!-- main-form-->
</div></div>
</form>	
</body>



</html>
