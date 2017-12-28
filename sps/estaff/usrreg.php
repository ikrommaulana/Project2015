<?PHP
$vmod="v7.0.0";
$vdate="120808";


include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
error_reporting(0);

ISACCESS("estaff",1);

$uid = $_REQUEST['uid'];
$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];

//resetpassword
	
$pasreset=isset($_POST['reset']);

if($pasreset){
	$pass="123456";
	$pas2=md5($pass);
	
	$sql="update usr set pass='$pas2' where uid='$uid'";
	$res=mysql_query($sql)or die(mysql_error);
	$q="<font color=blue>&lt; PASSWORD SUCCESSFULY RESET</font>";
	
}

if($uid!="")
{
    $sql="select * from usr where uid='$uid'";
    $res=mysql_query($sql)or die(mysql_error);
    $row=mysql_fetch_assoc($res);
    
    //sistem information
        $uidx       = $row['uid'];
        $syslevel   = $row['syslevel'];
        $status     = $row['status'];
        $sid        = $row['sch_id'];
	
	
			$sqlsch="select * from sch where id='$sid'";
			$ressch=mysql_query($sqlsch)or die("$sqlsch failed:".mysql_error());
			$rowsch=mysql_fetch_assoc($ressch);
			$sname=stripcslashes($rowsch['name']);
			
        $indukno    = $row['indukno'];
		$nuptk=$row['nuptk'];
        
    //personal info
    $name       = $row['name'];
    $bplace     = $row['bplace'];
    $religion   = $row['religion'];
    $address    = $row['addr'];
    $hstatus    = $row['hstatus'];
    $sdt        = $row['jobstart'];
    $edt        = $row['jobend'];
    $position   = $row['job'];
    $mel	= $row['mel'];
    $hp 	= $row['hp'];
    $file=$row['file'];
    
        //pendidikan
        $pen1=explode("|",$row['pen1']);
	$pen2=explode("|",$row['pen2']);
        $pen3=explode("|",$row['pen3']);
        $pen4=explode("|",$row['pen4']);
        $pen5=explode("|",$row['pen5']);
        $pen6=explode("|",$row['pen6']);
       
    //kursus
    $k1=explode("|",$row['k1']);
    $k2=explode("|",$row['k2']);
    $k3=explode("|",$row['k3']);
    $k4=explode("|",$row['k4']);
    $k5=explode("|",$row['k5']);
    
            //bidang studi
            $b1=explode("|",$row['b1']);
            $b2=explode("|",$row['b2']);
            $b3=explode("|",$row['b3']);
            
    //jabatan non formal
    $p1=explode("|",$row['p1']);
    $p2=explode("|",$row['p2']);
    $p3=explode("|",$row['p3']);
    
    
            //jabatan formal
            
            $f1=explode("|",$row['f1']);
            $f2=explode("|",$row['f2']);
            $f3=explode("|",$row['f3']);
            $f4=explode("|",$row['f4']);
            $f5=explode("|",$row['f5']);
            $f6=explode("|",$row['f6']);
            $f7=explode("|",$row['f7']);
            $f8=explode("|",$row['f8']);
            $f9=explode("|",$row['f9']);
            $f10=explode("|",$row['f10']);
            
            
    $stajabatan = $row['stajabatan'];
    $nip        = $row['ic'];
    $karpeg     = $row['karpeg'];
    $place1     = $row['place1'];
    $place2     = $row['place2'];
    
            //keterangan fisik
            $height     = $row['height'];
            $weight     = $row['weight'];
            $face       = $row['face'];
            $hair       = $row['hair'];
            $bloodtype  = $row['bloodtype'];
            $sick1      = $row['sick1'];
            $sick2      = $row['sick2'];
            $differ     = $row['differ'];
        
        $sibtotal   = $row['sibtotal'];
        $sibboy     = $row['sibboy'];
        $sibgirl    = $row['sibgirl'];
        $sibno      = $row['sibno'];
        $staffstatus= $row['staffstatus'];
        
        
                //susunan keluarga
               $s1=explode("|",$row['s1']);
               $s2=explode("|",$row['s2']);
               $s3=explode("|",$row['s3']);
               $s4=explode("|",$row['s4']);
               $s5=explode("|",$row['s5']);
               $s6=explode("|",$row['s6']);
               $s7=explode("|",$row['s7']);
               $s8=explode("|",$row['s8']);
               $s9=explode("|",$row['s9']);
               
                
        //organisasi
        $o1=explode("|",$row['o1']);
        $o2=explode("|",$row['o2']);
        $o3=explode("|",$row['o3']);
        $o4=explode("|",$row['o4']);
        $o5=explode("|",$row['o5']);
        
                //hobby
                $h1=explode("|",$row['h1']);
                $h2=explode("|",$row['h2']);
                $h3=explode("|",$row['h3']);
                $h4=explode("|",$row['h4']);
                $h5=explode("|",$row['h5']);
                
                
    $langua     = $row['langua'];
    $piagam1    = $row['piagam1'];
    $piagam2    = $row['piagam2'];
    
                //kesan kepala sek
                $effect      = $row['effect'];
                
                $xplain1     = $row['xplain1'];
                $xplain2     = $row['xplain2'];
                $xplain3     = $row['xplain3'];
                $xplain4     = $row['xplain4'];
                $xplain5     = $row['xplain5'];
                
                $reason      = $row['reason'];
}





$username 	= $_SESSION['username'];
$operation      =$_REQUEST['operation'];
$f=$_REQUEST['f'];
if($operation!=""){
	include_once('usrsave.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <?php include('inc/jquerytab/function.php');?>
    
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $lg_staff;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript">
function process_form(operation){

	if(operation=='insert'){
    
		if(document.myform.syslevel.value==""){
			alert("Please select Access Level");
			document.myform.syslevel.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Silahkan Pilih Sistem Akses");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.indukno.value==""){
			alert("Silahkan Isi No Induk");
			document.myform.indukno.focus();
			return;
		}
		if(document.myform.uidx.value==""){
			alert("Please enter the user ID");
			document.myform.uidx.focus();
			return;
		}
                
		stringToTrim = document.myform.uidx.value;
		str=stringToTrim.replace(/^\s+|\s+$/g,"");
		if(str==""){
			document.myform.uidx.value='';
			alert("Please enter the user ID");
			document.myform.uidx.focus();
			return;
		}
		if(document.myform.name.value==""){
			alert("Please enter fullname");
			document.myform.name.focus();
			return;
		}
		
		if(document.myform.file.value!=""){
			fn=document.myform.file.value;
			len=fn.length;
			ext=fn.substr(len-3,3);
			if((ext.toLowerCase()!="gif")&&(ext.toLowerCase()!="jpg")){
				alert("Invalid file. Only GIF or JPG allowed");
				document.form1.uploadedfile.focus();
				return;
			}
		}
		ret = confirm("<?php echo "Simpan Data ?";?>");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
		return;

	}
        if(operation=='update'){
            ret = confirm("<?php echo "Simpan Data ?";?>");
		if (ret == true){
			document.myform.operation.value=operation;
			document.myform.submit();
		}
        }
    
}



</script>



</head>
<body >
<!------------------------------------------form start------------------------------->    
    <form action="" method="post" enctype="multipart/form-data" name="myform">
            <input type="hidden" name="uid" value="<?php echo $uid;?>">
            <input type="hidden" name="p" value="usrreg">
            <input type="hidden" name="operation" >
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                
        <div id="content">
            <div id="mypanel"  class="printhidden">
		<div id="mymenu" align="center">
				<?php if($uid!=""){?>
                                <a href="usr_info.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goback.png"><br><?php echo 'Kembali';?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                                <a href="#" onClick="process_form('update')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo "Simpan";?></a>
				<? }else {?>
				<a href="#" onClick="process_form('insert')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
				<?php }?>
                                		<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                                <a href="usrreg.php?uid=<?php echo $uid;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
		
		</div> <!-- end mymenu -->
		<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div> <!-- end mypanel -->      

    <div id='jqxWidget'>
        
        <div id='jqxTabs'>
            <ul>
                <li title="informasi sistem,peribadi" style="margin-left: 30px;">Langkah 1</li>
                <li title="Pendidikan,kursus,bidang studi">Langkah 2</li>
                <li title="pengalam kerja">Langkah 3</li>
                <li title="keterangan fisik,susunan keluarga">Langkah 4</li>
                <li title="organisasi,hobby">Langkah 5</li>
                <li title="kesan,keterangan, sebab berhenti">Langkah 6</li>
                
                

            </ul>
            
            <!---------------form1------------->
            
            <div>
            	<div id="mytabletitle">INFORMASI SISTEM <?php echo $f; ?><?php echo $q; ?></div>
            	<table width="100%" cellpadding="2px" cellspacing="2px">
                	<tr>
                    	<td width="20%">ID PEKERJA</td>
                        <td width="2%">:</td>
                        <td><?php if($uid!=""){ echo $uid; }else{?><input type="text" name="uidx" value="<?php echo $uidx;?>"> <?php }?></td>
                    </tr>
                       
                    <tr>
                        
                    	<td>PASSWORD</td>
                        <td>:</td>
                        <td><?php if($uid==""){ ?><span style="font-size: 9px; color: blue;">password di set ke default(123456) </span><?php }else{?>
                        <input type="submit" name="reset" value="Reset Password" style="background-color: #00FF00;"><font color="blue">(Click to Reset Password)</font><?php } ?>
                    </tr>
                    <tr>
                    	<td>LEVEL SISTEM</td>
                        <td>:</td>
                        <td><select name="syslevel" id="syslevel" >
					<?php
                                        
                                        if ($uid!="")
                                        {
                                            echo "<option value=\"$syslevel\">$syslevel</option>";
                                        }
					if(is_verify('ADMIN')) {
						if($syslevel==""){
							$sql="select * from type where grp='syslevel' order by val";
						}
						else{
							echo "<option value=\"$syslevel\">$syslevel</option>";
							$sql="select * from type where grp='syslevel' and prm!='$syslevel' order by val";
						}
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=\"$s\">$s</option>";
						}
						mysql_free_result($res);					  
					}
					else{
						if($syslevel==""){
							$sql="select * from type where grp='syslevel' and prm!='ADMIN' order by val";
						}
						else{
							echo "<option value=\"$syslevel\">$syslevel</option>";
							$sql="select * from type where grp='syslevel' and prm!='ADMIN' and prm!='$syslevel' order by val";
						}
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=\"$s\">$s</option>";
						}
						mysql_free_result($res);	
					}
?>
                  </select></td>
                    </tr>
                    <tr>
                    	<td>STATUS</td>
                        <td>:</td>
                        <td><select name="status">
                        <?php if($uid!="")
                            {
                                if($status=="0"){$sta="ACTIVE";}else if($status=="1"){$sta="TERMINATE";}else{$sta="RESIGN";}
                                echo "<option value=\"$status\">$sta</option>";
                            }else{ ?>
                                
                                <option value="">[ PILIH ]</option>
                        
                            <?php
                            }
                                $sql="select * from type where grp='usrstatus' order by val";
                                $res=mysql_query($sql)or die("query failed:".mysql_error());
                                while($row=mysql_fetch_assoc($res)){
                                            $s=strtoupper($row['prm']);
                                               $v=$row['val'];
                                            echo "<option value=\"$v\">$s</option>";
                                }
                            ?>
                        </select>
                        
                         <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=usrstatus',0)">
                         <?php } ?>
                        </td>
                    </tr>
                     <tr>
                    	<td>SISTEM AKSES</td>
                        <td>:</td>
                        <td>
				 <select name="sid" id="sid" >
					
<?php

				if($sid=="0")
					echo "<option value=\"0\">- $lg_all -</option>";
				else
					echo "<option value=$sid>$sname</option>";
				
				if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripcslashes($row['name']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid!="0")
					echo "<option value=\"0\">- $lg_all -</option>";
			}					
?>
                    </select>
				
				
			</td>
                    </tr>
                     <tr>
                    	<td>NO INDUK</td>
                        <td>:</td>
                        <td><input type="text" name="indukno" value="<?php echo $indukno;?>"></td>
                    </tr>
                </table>
            
                <div id="mytabletitle">INFORMASI PERIBADI</div>
            	<table width="100%" cellpadding="2px" cellspacing="2px">
                	<tr>
                    	<td width="20%">NAMA</td>
                        <td width="2%">:</td>
                        <td><input type="text" size="45" name="name" value="<?php echo $name;?>"</td>
                        <td rowspan="6" width="18%" valign="top" align="center">
        
                			<table width="150" height="150" id="myborder" bgcolor="#F1F1F1">
                    
                    			<tr>
            
                         			<td  bgcolor="#ccccff"><?php if($file!=""){?>
                                    <img name="picture" alt="Picture" src="<?php if($file!="") echo "$dir_image_user$file"; ?>"
                                    width="150" height="150" ><?php } ?></td>
							
                   				</tr>
                 			</table>
                        			<input type="file" name="file" size="8"></td>
                    </tr>
			<tr>
                    	<td>NIP NO</td>
                        <td>:</td>
                        <td><input type="text" name="nip" value="<?php echo $nip;?>"></td>
                    </tr>
					<tr>
                    	<td>NUPTK NO</td>
                        <td>:</td>
                        <td><input type="text" name="nuptk" value="<?php echo $nuptk;?>"></td>
                    </tr>
                    <tr>
                    	<td>TEMPAT/TGL.LAHIR</td>
                        <td>:</td>
                        <td><input type="text" name="bplace" value="<?php echo $bplace;?>"></td>
                    </tr>
                    <tr>
                    	<td>AGAMA</td>
                        <td>:</td>
                        <td><input type="text" name="religion" value="<?php echo $religion;?>"></td>
                    </tr>
                    <tr>
                    	<td>ALAMAT</td>
                        <td>:</td>
                        <td><textarea cols='35' rows='3' name="address"><?php echo $address;?></textarea></td>
                    </tr>
                    <tr>
                    	<td >STATUS RUMAH</td>
                        <td>:</td>
                        <td><select name="hstatus" value="<?php echo $hstatus;?>">
                        <?php
                        if($uid!="")
                            {
            
                                echo "<option value=\"$hstatus\">$hstatus</option>";
                            }else{ ?>
                        <option value="">[ PILIH ]</option>
                            <?php
                            }
                                $sql="select * from type where grp='rumahstatus' order by val";
                                $res=mysql_query($sql)or die("query failed:".mysql_error());
                                while($row=mysql_fetch_assoc($res)){
                                            $s=strtoupper($row['prm']);
                                        
                                            echo "<option value=\"$s\">$s</option>";
                                }
                            ?>
                        
                        </select>
                        
                        <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=rumahstatus',0)">
                         <?php } ?>
                        
                        </td>
                    </tr>
                    <tr>
                    	<td width="15%">BERTUGAS TANGGAL</td>
                        <td width="2%">:</td>
                        <td><input name="sdt" type="text" id="sdt" value="<?php echo "$sdt";?>" readonly size="10" onClick="displayDatePicker('sdt');" onKeyDown="displayDatePicker('sdt');"></td>
                    </tr>
                    <tr>
                    	<td width="15%">S/D TANGGAL</td>
                        <td width="2%">:</td>
                        <td><input name="edt" type="text" id="edt" value="<?php echo "$edt";?>" readonly size="10" onClick="displayDatePicker('edt');" onKeyDown="displayDatePicker('edt');"></td>
                    </tr>
                    <tr>
                    	<td width="15%">MENGAJAR BIDANG STUDI/TUGAS</td>
                        <td width="2%">:</td>
                        <td><input type="text" size="45" name="position" value="<?php echo $position;?>"></td>
                    </tr>
                <tr>
                    	<td width="15%">EMAIL </td>
                        <td width="2%">:</td>
                        <td><input type="text" size="45" name="mel" value="<?php echo $mel;?>"></td>
                    </tr>
		<tr>
                    	<td width="15%">Tel. HP</td>
                        <td width="2%">:</td>
			<td><input type="text" size="45" name="hp" value="<?php echo $hp;?>"></td>
                        
                    </tr>
		
		</table>
                
                <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                
                
            </div>
            <!---------------form2------------->
            <div>
                <div id="mytabletitle">PENDIDIKAN</div>
                	<table width="80%">
                    	<tr>
                            <td  align="center">NO.</td>
                            <td>TINGKAT</td>
                            
                            <td>JURUSAN</td>
                            <td>IJAZAH TH.</td>
                            
                            <td>TEMPAT</td>
                            
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="15%" name="pen1[]" type="text" value="<?php echo "SR/SD";?>" readonly></td>
                            <td><input size="35%" name="pen1[]" type="text" value="<?php echo "$pen1[1]";?>"></td>
                            
                            <td><input size="15%" name="pen1[]" type="text" value="<?php echo "$pen1[2]";?>"></td>
                            <td><input size="35%" name="pen1[]" type="text" value="<?php echo "$pen1[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="15%" name="pen2[]" type="text" value="<?php echo "SMP/SLTP";?>"></td>
                            <td><input size="35%" name="pen2[]" type="text" value="<?php echo "$pen2[1]";?>"></td>
                          
                            <td><input size="15%" name="pen2[]" type="text" value="<?php echo "$pen2[2]";?>"></td>
                            <td><input size="35%" name="pen2[]" type="text" value="<?php echo "$pen2[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="15%" name="pen3[]" type="text" value="<?php echo "SMA/SMU";?>"></td>
                            <td><input size="35%" name="pen3[]" type="text" value="<?php echo "$pen3[1]";?>"></td>
                         
                            <td><input size="15%" name="pen3[]" type="text" value="<?php echo "$pen3[2]";?>"></td>
                            <td><input size="35%" name="pen3[]" type="text" value="<?php echo "$pen3[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>4.</td>
                            <td><input size="15%" name="pen4[]" type="text" value="<?php echo "$pen4[0]";?>"></td>
                            <td><input size="35%" name="pen4[]" type="text" value="<?php echo "$pen4[1]";?>"></td>
                          
                            <td><input size="15%" name="pen4[]" type="text" value="<?php echo "$pen4[2]";?>"></td>
                            <td><input size="35%" name="pen4[]" type="text" value="<?php echo "$pen4[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>5.</td>
                            <td><input size="15%" name="pen5[]" type="text" value="<?php echo "$pen5[0]";?>"></td>
                            <td><input size="35%" name="pen5[]" type="text" value="<?php echo "$pen5[1]";?>"></td>
                         
                            <td><input size="15%" name="pen5[]" type="text" value="<?php echo "$pen5[2]";?>"></td>
                            <td><input size="35%" name="pen5[]" type="text" value="<?php echo "$pen5[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>6.</td>
                            <td><input size="15%" name="pen6[]" type="text" value="<?php echo "$pen6[0]";?>"></td>
                            <td><input size="35%" name="pen6[]" type="text" value="<?php echo "$pen6[1]";?>"></td>
                         
                            <td><input size="15%" name="pen6[]" type="text" value="<?php echo "$pen6[2]";?>"></td>
                            <td><input size="35%" name="pen6[]" type="text" value="<?php echo "$pen6[3]";?>"></td>
                         
                        </tr>
                    </table>
                    
               <div id="mytabletitle">KURSUS/PENATARAN/LATIHAN DI DALAM DAN DI LUAR NEGERI</div>
                	<table width="80%">
                    	<tr>
                            <td align="center">NO.</td>
                            <td>JENIS</td>
                            
                            <td>TEMPAT</td>
                            <td>LAMANYA</td>
                            
                            <td>TINGKAT</td>
                            
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="15%" name="k1[]" type="text" value="<?php echo "$k1[0]";?>"></td>
                            <td><input size="35%" name="k1[]" type="text" value="<?php echo "$k1[1]";?>"></td>
                            
                            <td><input size="15%" name="k1[]" type="text" value="<?php echo "$k1[2]";?>"></td>
                            <td><input size="35%" name="k1[]" type="text" value="<?php echo "$k1[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="15%" name="k2[]" type="text" value="<?php echo "$k2[0]";?>"></td>
                            <td><input size="35%" name="k2[]" type="text" value="<?php echo "$k2[1]";?>"></td>
                          
                            <td><input size="15%" name="k2[]" type="text" value="<?php echo "$k2[2]";?>"></td>
                            <td><input size="35%" name="k2[]" type="text" value="<?php echo "$k2[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="15%" name="k3[]" type="text" value="<?php echo "$k3[0]";?>"></td>
                            <td><input size="35%" name="k3[]" type="text" value="<?php echo "$k3[1]";?>"></td>
                         
                            <td><input size="15%" name="k3[]" type="text" value="<?php echo "$k3[2]";?>"></td>
                            <td><input size="35%" name="k3[]" type="text" value="<?php echo "$k3[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>4.</td>
                            <td><input size="15%" name="k4[]" type="text" value="<?php echo "$k4[0]";?>"></td>
                            <td><input size="35%" name="k4[]" type="text" value="<?php echo "$k4[1]";?>"></td>
                          
                            <td><input size="15%" name="k4[]" type="text" value="<?php echo "$k4[2]";?>"></td>
                            <td><input size="35%" name="k4[]" type="text" value="<?php echo "$k4[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>5.</td>
                            <td><input size="15%" name="k5[]" type="text" value="<?php echo "$k5[0]";?>"></td>
                            <td><input size="35%" name="k5[]" type="text" value="<?php echo "$k5[1]";?>"></td>
                         
                            <td><input size="15%" name="k5[]" type="text" value="<?php echo "$k5[2]";?>"></td>
                            <td><input size="35%" name="k5[]" type="text" value="<?php echo "$k5[3]";?>"></td>
                         
                        </tr>
                        
                    </table>
                    
                     <div id="mytabletitle">BIDANG STUDI PERNAH DIAJAR</div>
                	<table width="80%">
                    	<tr>
                            <td  align="center">NO.</td>
                            <td>MATA PELAJARAN</td>
                            
                            <td>JENIS SEKOLAH</td>
                            <td>KELAS</td>
                            
                            <td>TAHUN</td>
                            
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="15%" name="b1[]" type="text" value="<?php echo "$b1[0]";?>"></td>
                            <td><input size="35%" name="b1[]" type="text" value="<?php echo "$b1[1]";?>"></td>
                            
                            <td><input size="15%" name="b1[]" type="text" value="<?php echo "$b1[2]";?>"></td>
                            <td><input size="35%" name="b1[]" type="text" value="<?php echo "$b1[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="15%" name="b2[]" type="text" value="<?php echo "$b2[0]";?>"></td>
                            <td><input size="35%" name="b2[]" type="text" value="<?php echo "$b2[1]";?>"></td>
                         
                            <td><input size="15%" name="b2[]" type="text" value="<?php echo "$b2[2]";?>"></td>
                            <td><input size="35%" name="b2[]" type="text" value="<?php echo "$b2[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="15%" name="b3[]" type="text" value="<?php echo "$b3[0]";?>"></td>
                            <td><input size="35%" name="b3[]" type="text" value="<?php echo "$b3[1]";?>"></td>
                         
                            <td><input size="15%" name="b3[]" type="text" value="<?php echo "$b3[2]";?>"></td>
                            <td><input size="35%" name="b3[]" type="text" value="<?php echo "$b3[3]";?>"></td>
                         
                        </tr>
                   
                    </table>
                        
                        <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                
                    
                 
            </div>
            <!---------------form3------------->
            <div>
                <div id="mytabletitle">PENGALAMAN KERJA NON-FORMAL</div>
                	<table width="50%">
                    	<tr>
                            <td  align="center">NO.</td>
                            <td>TEMPAT</td>
                            
                            <td>DARI TH. S/D TH.</td>
                            <td>TUGAS</td>
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="15%" name="p1[]" type="text" value="<?php echo "$p1[0]";?>"></td>
                            <td><input size="35%" name="p1[]" type="text" value="<?php echo "$p1[1]";?>"></td>
                            
                            <td><input size="15%" name="p1[]" type="text" value="<?php echo "$p1[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="15%" name="p2[]" type="text" value="<?php echo "$p2[0]";?>"></td>
                            <td><input size="35%" name="p2[]" type="text" value="<?php echo "$p2[1]";?>"></td>
                         
                            <td><input size="15%" name="p2[]" type="text" value="<?php echo "$p2[2]";?>"></td>
                            
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="15%" name="p3[]" type="text" value="<?php echo "$p3[0]";?>"></td>
                            <td><input size="35%" name="p3[]" type="text" value="<?php echo "$p3[1]";?>"></td>
                         
                            <td><input size="15%" name="p3[]" type="text" value="<?php echo "$p3[2]";?>"></td>
                            
                         
                        </tr>
                   
                    </table>
                 
                 <div id="mytabletitle">JABATAN FORMAL</div>
                	<table width="100%" cellspacing="2" cellpadding="0">
                    	<tr>
                            <td align="center">NO.</td>
                            <td align="center">SURAT KEPUTUSAN<br>(Dari Tgl Nomor)</td>
                            <td align="center">GAJI POKOK</td>
                            <td align="center">TERHITUNG<br>(Mulai Sampai)</td>
                            <td align="center">MASA KERJA <br> (Th Bl)</td>          
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[0]";?>">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[1]";?>">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[4]";?>">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[6]";?>">
                            <input size="8%" name="f1[]" type="text" value="<?php echo "$f1[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[0]";?>">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[1]";?>">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[4]";?>">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[6]";?>">
                            <input size="8%" name="f2[]" type="text" value="<?php echo "$f2[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[0]";?>">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[1]";?>">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[4]";?>">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[6]";?>">
                            <input size="8%" name="f3[]" type="text" value="<?php echo "$f3[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>4.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[0]";?>">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[1]";?>">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[4]";?>">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[6]";?>">
                            <input size="8%" name="f4[]" type="text" value="<?php echo "$f4[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>5.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[0]";?>">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[1]";?>">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[4]";?>">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[6]";?>">
                            <input size="8%" name="f5[]" type="text" value="<?php echo "$f5[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>6.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[0]";?>">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[1]";?>">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[4]";?>">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[6]";?>">
                            <input size="8%" name="f6[]" type="text" value="<?php echo "$f6[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>7.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[0]";?>">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[1]";?>">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[4]";?>">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[6]";?>">
                            <input size="8%" name="f7[]" type="text" value="<?php echo "$f7[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>8.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[0]";?>">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[1]";?>">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[4]";?>">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[6]";?>">
                            <input size="8%" name="f8[]" type="text" value="<?php echo "$f8[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>9.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[0]";?>">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[1]";?>">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;"align="center">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[4]";?>">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;"align="center">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[6]";?>">
                            <input size="8%" name="f9[]" type="text" value="<?php echo "$f9[7]";?>">
                            </td>
                         
                        </tr>
                        <tr>
                        	<td>10.</td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[0]";?>">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[1]";?>">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[2]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[3]";?>">
                            </td>
                            
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[4]";?>">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[5]";?>">
                            </td>
                            <td style="background-color:#f4f4f4;" align="center">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[6]";?>">
                            <input size="8%" name="f10[]" type="text" value="<?php echo "$f10[7]";?>">
                            </td>
                         
                        </tr>
                       
                    </table>
                    <br>

                    <table width="100%" cellpadding="2px" cellspacing="2px">
                	<tr>
                    	<td width="20%">STATUS JABATAN</td>
                        <td width="2%">:</td>
                        <td><select name="stajabatan">
                        <?php
                        if($uid!="")
                            {
                                echo "<option value=\"$stajabatan\">$stajabatan</option>";
                            }else{
                        ?>
                        <option value="">[ PILIH ]</option>
                             <?php
                             }
                                $sql="select * from type where grp='statusjabatan' order by val";
                                $res=mysql_query($sql)or die("query failed:".mysql_error());
                                while($row=mysql_fetch_assoc($res)){
                                            $s=strtoupper($row['prm']);
                                        
                                            echo "<option value=\"$s\">$s</option>";
                                }
                            ?>
                        
                        </select>
                        
                        <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=statusjabatan',0)">
                         <?php } ?>
                        
                        
                        </select>
                   <!-- <tr>
                    	<td>NIP NO</td>
                        <td>:</td>
                        <td><input type="text" name="nip" value="<?php echo $nip;?>"></td>
                    </tr>-->
                    <tr>
                    	<td>KARPEG NO</td>
                        <td>:</td>
                        <td><input type="text" name="karpeg" value="<?php echo $karpeg;?>"></td>
                    </tr>
                    <tr>
                    	<td valign="top">BEKERJA DITEMPAT LAIN</td>
                        <td valign="top">:</td>
                        <td><input size="35" type="text" name="place1" <?php if($place1==""){?>onClick="document.myform.place1.value='';"<?php } ?> value="<?php if($place1=="") echo "1."; else echo "$place1";?>"><br>
                        	<input size="35" type="text" name="place2" <?php if($place2==""){?>onClick="document.myform.place2.value='';"<?php } ?> value="<?php if($place2=="") echo "2."; else echo "$place2";?>">
                        </td>
                    </tr>
                    </table>
                    
                    <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                
            </div>
            <!---------------form4------------->
            <div>
                <div id="mytabletitle">KETERANGAN FISIK</div>
                 <table width="100%" cellpadding="2px" cellspacing="2px">
                	<tr>
                    	<td width="20%">TINGGI</td>
                        <td width="2%">:</td>
                        <td><input size="10" type="text" name="height" value="<?php echo $height; ?>">CM</td>
                    </tr>
                    <tr>
                    	<td>BERAT</td>
                        <td>:</td>
                        <td><input size="10" type="text" name="weight" value="<?php echo $weight; ?>">KG</td>
                    </tr>
                    <tr>
                    	<td>MUKA</td>
                        <td>:</td>
                        <td><input type="text" name="face" value="<?php echo $face; ?>"></td>
                    </tr>
                    <tr>
                    	<td>RAMBUT</td>
                        <td>:</td>
                        <td><input type="text" name="hair" value="<?php echo $hair; ?>"></td>
                    </tr>
                    <tr>
                    	<td>GOLONGAN DARAH</td>
                        <td>:</td>
                        <td><input type="text" name="bloodtype" value="<?php echo $bloodtype; ?>"></td>
                    </tr>
                     <tr>
                    	<td valign="top">SAKIT BERAT PERNAH DIDERITAI</td>
                        <td valign="top">:</td>
                        <td><input size="35" type="text" name="sick1" <?php if($sick1==""){?>onClick="document.myform.sick1.value='';"<?php } ?> value="<?php if($sick1=="") echo "1."; else echo "$sick1";?>"><br>
                        	<input size="35" type="text" name="sick2" <?php if($sick2==""){?>onClick="document.myform.sick2.value='';"<?php } ?> value="<?php if($sick2=="") echo "2."; else echo "$sick2";?>">
                        </td>
                    </tr>
                     <tr>
                    	<td>KELAINAN</td>
                        <td>:</td>
                        <td><input size="35" type="text" name="differ" value="<?php echo $differ; ?>"></td>
                    </tr>
                     <tr>
                    	<td valign="top">JUMLAH BERSAUDARA</td>
                        <td valign="top">:</td>
                        <td><input size="5" type="text" name="sibtotal" value="<?php echo $sibtotal; ?>">ORANG<br>
                        <input size="5" type="text" name="sibboy" value="<?php echo $sibboy; ?>">PRIA<br>
                        <input size="5" type="text" name="sibgirl" value="<?php echo $sibgirl; ?>">WANITA
                        </td>
                    </tr>
                    <tr>
                    	<td>YANG BERSANGKUTAN ANAK KE</td>
                        <td width="2%">:</td>
                        <td><input size="5" type="text" name="sibno" value="<?php echo $sibno; ?>"></td>
                    </tr>
                    <tr>
                    	<td width="15%">STATUS</td>
                        <td width="2%">:</td>
                        <td><select name="staffstatus">
                        <?php
                        if($uid!="")
                            {
                                echo "<option value=\"$staffstatus\">$staffstatus</option>";
                            }else{
                        ?>
                        <option value="">[ PILIH ]</option>
                         <?php
                            }
                                $sql="select * from type where grp='stastaff' order by val";
                                $res=mysql_query($sql)or die("query failed:".mysql_error());
                                while($row=mysql_fetch_assoc($res)){
                                            $s=strtoupper($row['prm']);
                                        
                                            echo "<option value=\"$s\">$s</option>";
                                }
                            ?>
                        
                        </select>
                        
                        <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=stastaff',0)">
                         <?php } ?>
                        </td>
                    </tr>
                    
                </table>
                
                <div id="mytabletitle">SUSUNAN KELUARGA</div>
                
                <table width="100%">
                    	<tr>
                            <td  align="center">NO.</td>
                            <td>NAMA</td>
                            
                            <td>TEMPAT/TGL.LAHIR</td>
                            <td>PEKERJAAN</td>
                            
                        </tr>
                    	<tr>
                        	<td>1. Ayah</td>
                            <td><input size="35%" name="s1[]" type="text" value="<?php echo "$s1[0]";?>"></td>
                            <td><input size="25%" name="s1[]" type="text" value="<?php echo "$s1[1]";?>"></td>
                            
                            <td><input size="35%" name="s1[]" type="text" value="<?php echo "$s1[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>2. Ibu </td>
                            <td><input size="35%" name="s2[]" type="text" value="<?php echo "$s2[0]";?>"></td>
                            <td><input size="25%" name="s2[]" type="text" value="<?php echo "$s2[1]";?>"></td>
                         
                            <td><input size="35%" name="s2[]" type="text" value="<?php echo "$s2[2]";?>"></td>
                            
                         
                        </tr>
                        <tr>
                        	<td>3. Suami </td>
                            <td><input size="35%" name="s3[]" type="text" value="<?php echo "$s3[0]";?>"></td>
                            <td><input size="25%" name="s3[]" type="text" value="<?php echo "$s3[1]";?>"></td>
                         
                            <td><input size="35%" name="s3[]" type="text" value="<?php echo "$s3[2]";?>"></td>
                            
                         
                        </tr>
                        <tr>
                        	<td>4. Istri </td>
                            <td><input size="35%" name="s4[]" type="text" value="<?php echo "$s4[0]";?>"></td>
                            <td><input size="25%" name="s4[]" type="text" value="<?php echo "$s4[1]";?>"></td>
                            
                            <td><input size="35%" name="s4[]" type="text" value="<?php echo "$s4[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>5. Anak 1</td>
                            <td><input size="35%" name="s5[]" type="text" value="<?php echo "$s5[0]";?>"></td>
                            <td><input size="25%" name="s5[]" type="text" value="<?php echo "$s5[1]";?>"></td>
                            
                            <td><input size="35%" name="s5[]" type="text" value="<?php echo "$s5[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>6. Anak 2</td>
                            <td><input size="35%" name="s6[]" type="text" value="<?php echo "$s6[0]";?>"></td>
                            <td><input size="25%" name="s6[]" type="text" value="<?php echo "$s6[1]";?>"></td>
                            
                            <td><input size="35%" name="s6[]" type="text" value="<?php echo "$s6[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>7. Anak 3</td>
                            <td><input size="35%" name="s7[]" type="text" value="<?php echo "$s7[0]";?>"></td>
                            <td><input size="25%" name="s7[]" type="text" value="<?php echo "$s7[1]";?>"></td>
                            
                            <td><input size="35%" name="s7[]" type="text" value="<?php echo "$s7[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>8. Anak 4</td>
                            <td><input size="35%" name="s8[]" type="text" value="<?php echo "$s8[0]";?>"></td>
                            <td><input size="25%" name="s8[]" type="text" value="<?php echo "$s8[1]";?>"></td>
                            
                            <td><input size="35%" name="s8[]" type="text" value="<?php echo "$s8[2]";?>"></td>
                           
                         
                        </tr>
                        <tr>
                        	<td>9. Anak 5</td>
                            <td><input size="35%" name="s9[]" type="text" value="<?php echo "$s9[0]";?>"></td>
                            <td><input size="25%" name="s9[]" type="text" value="<?php echo "$s9[1]";?>"></td>
                            
                            <td><input size="35%" name="s9[]" type="text" value="<?php echo "$s9[2]";?>"></td>
                           
                         
                        </tr>
                    
                    </table>
                
                <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                
            </div>
            <!---------------form5------------->
            <div>
                <div id="mytabletitle">ORGANISASI</div>
                <table width="80%">
                    	<tr>
                            <td  align="center">NO.</td>
                            <td>NAMA ORGANISASI</td>
                            
                            <td>JABATAN</td>
                            <td>TH. S/D TH</td>
                            
                            <td>TEMPAT</td>
                            
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="25%" name="o1[]" type="text" value="<?php echo "$o1[0]";?>"></td>
                            <td><input size="25%" name="o1[]" type="text" value="<?php echo "$o1[1]";?>"></td>
                            
                            <td><input size="10%" name="o1[]" type="text" value="<?php echo "$o1[2]";?>"></td>
                            <td><input size="20%" name="o1[]" type="text" value="<?php echo "$o1[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="25%" name="o2[]" type="text" value="<?php echo "$o2[0]";?>"></td>
                            <td><input size="25%" name="o2[]" type="text" value="<?php echo "$o2[1]";?>"></td>
                         
                            <td><input size="10%" name="o2[]" type="text" value="<?php echo "$o2[2]";?>"></td>
                            <td><input size="20%" name="o2[]" type="text" value="<?php echo "$o2[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="25%" name="o3[]" type="text" value="<?php echo "$o3[0]";?>"></td>
                            <td><input size="25%" name="o3[]" type="text" value="<?php echo "$o3[1]";?>"></td>
                         
                            <td><input size="10%" name="o3[]" type="text" value="<?php echo "$o3[2]";?>"></td>
                            <td><input size="20%" name="o3[]" type="text" value="<?php echo "$o3[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>4.</td>
                            <td><input size="25%" name="o4[]" type="text" value="<?php echo "$o4[0]";?>"></td>
                            <td><input size="25%" name="o4[]" type="text" value="<?php echo "$o4[1]";?>"></td>
                         
                            <td><input size="10%" name="o4[]" type="text" value="<?php echo "$o4[2]";?>"></td>
                            <td><input size="20%" name="o4[]" type="text" value="<?php echo "$o4[3]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>5.</td>
                            <td><input size="25%" name="o5[]" type="text" value="<?php echo "$o5[0]";?>"></td>
                            <td><input size="25%" name="o5[]" type="text" value="<?php echo "$o5[1]";?>"></td>
                        
                            <td><input size="10%" name="o5[]" type="text" value="<?php echo "$o5[2]";?>"></td>
                            <td><input size="20%" name="o5[]" type="text" value="<?php echo "$o5[3]";?>"></td>
                         
                        </tr>
                   
                    </table>
                    
                     <div id="mytabletitle">HOBBY/KESENANGAN</div>
                	<table width="80%">
                    	<tr>
                            <td>NO.</td>
                            <td>JENIS HOBBY</td>
                            
                            <td>PROFESIONAL<br>AMATIR</td>
                            <td>TAHUN</td>
                            
                            <td>PERNAH JUARA <br>TINGKAT</td>
                            <td>TEMPAT</td>
                            
                            
                        </tr>
                    	<tr>
                        	<td>1.</td>
                            <td><input size="25%" name="h1[]" type="text" value="<?php echo "$h1[0]";?>"></td>
                            <td><input size="25%" name="h1[]" type="text" value="<?php echo "$h1[1]";?>"></td>
                            
                            <td><input size="5%" name="h1[]" type="text" value="<?php echo "$h1[2]";?>"></td>
                            <td><input size="15%" name="h1[]" type="text" value="<?php echo "$h1[3]";?>"></td>
                            <td><input size="20%" name="h1[]" type="text" value="<?php echo "$h1[4]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>2.</td>
                            <td><input size="25%" name="h2[]" type="text" value="<?php echo "$h2[0]";?>"></td>
                            <td><input size="25%" name="h2[]" type="text" value="<?php echo "$h2[1]";?>"></td>
                         
                            <td><input size="5%" name="h2[]" type="text" value="<?php echo "$h2[2]";?>"></td>
                            <td><input size="15%" name="h2[]" type="text" value="<?php echo "$h2[3]";?>"></td>
                            <td><input size="20%" name="h2[]" type="text" value="<?php echo "$h2[4]";?>"></td>
                        
                        </tr>
                        <tr>
                        	<td>3.</td>
                            <td><input size="25%" name="h3[]" type="text" value="<?php echo "$h3[0]";?>"></td>
                            <td><input size="25%" name="h3[]" type="text" value="<?php echo "$h3[1]";?>"></td>
                        
                            <td><input size="5%" name="h3[]" type="text" value="<?php echo "$h3[2]";?>"></td>
                            <td><input size="15%" name="h3[]" type="text" value="<?php echo "$h3[3]";?>"></td>
                            <td><input size="20%" name="h3[]" type="text" value="<?php echo "$h3[4]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>4.</td>
                            <td><input size="25%" name="h4[]" type="text" value="<?php echo "$h4[0]";?>"></td>
                            <td><input size="25%" name="h4[]" type="text" value="<?php echo "$h4[1]";?>"></td>
                        
                            <td><input size="5%" name="h4[]" type="text" value="<?php echo "$h4[2]";?>"></td>
                            <td><input size="15%" name="h4[]" type="text" value="<?php echo "$h4[3]";?>"></td>
                            <td><input size="20%" name="h4[]" type="text" value="<?php echo "$h[4]";?>"></td>
                         
                        </tr>
                        <tr>
                        	<td>5.</td>
                            <td><input size="25%" name="h5[]" type="text" value="<?php echo "$h3[0]";?>"></td>
                            <td><input size="25%" name="h5[]" type="text" value="<?php echo "$h3[1]";?>"></td>
                        
                            <td><input size="5%" name="h5[]" type="text" value="<?php echo "$h5[2]";?>"></td>
                            <td><input size="15%" name="h5[]" type="text" value="<?php echo "$h5[3]";?>"></td>
                            <td><input size="20%" name="h5[]" type="text" value="<?php echo "$h5[4]";?>"></td>
                         
                        </tr>
                   
                    </table>
                    <table width="100%">
                    	<tr>
                        	<td><p style="font-weight:bold; font-size:11px; color:#666666;">Reaksi,pencinta alam, memancing, menulis buku, dan lain-lain bisa dimasukkan kesenangan walaupun tak pernah ada perlombaan kejuaraan.</p></td>
                        </tr>
                    </table>
                    <table width="100%">
                    	<tr>
                        	<td width="25%">BAHASA YANG DIKUASAI</td>
                            <td width="2%">:</td>
                            <td><input size="35" type="text" name="langua" value="<?php echo $langua; ?>"></td>
                            
                        </tr>
                        <tr>
                        	<td valign="top">PIAGAM YANG DIPEROLEH</td>
                            <td valign="top">:</td>
                            <td><input size="35" type="text" name="piagam1" <?php if($piagam1==""){?>onClick="document.myform.piagam1.value='';"<?php } ?> value="<?php if($piagam1=="") echo "1."; else echo "$piagam1";?>"><br>
                            <input size="35" type="text" name="piagam2" <?php if($piagam2==""){?>onClick="document.myform.piagam2.value='';"<?php } ?> value="<?php if($piagam2=="") echo "2."; else echo "$piagam2";?>">
                            </td>
                            
                        </tr>
                    </table>
                    
                 <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                   
                 
            </div>
            <!---------------------------form6------------------------>
            <div>
            	<div id="mytabletitle">KESAN KEPALA SEKOLAH TENTANG </div>
                <p style="font-weight:bold; font-size:11px; color:#666666;">Kepribadian,Watak, Kecerdasan, Keistimewaan, hobby menonjol, Kelemahan .<br>
				 Umpamanya: Keberanian ambil risiko, Aktivitas, optimis/pesimis, Percaya Diri, Kejujuran, Kerajinan, Disiplin, Terbuka, Inisiatif, Kreasi/dinamis,kebersihan, Kerapihan, Kemampuan myakinkan, Keramahan, Kecepatan berpikir/berbuat , Keuletan, Partisipasi kemampuan merencanakan, Kerjasama, Organisasi, Tanggungjawab kerja dan lain-lain.</p>
                 <br>
				
                 <p><textarea rows="10" cols="80" name="effect"><?php echo $effect; ?></textarea></p>
                 
           <table width="100%">
           	<tr>
           		<td width="25%" valign="top">KETERANGAN LAIN-LAIN</td>
                <td width="2%" valign="top">:</td>
                <td><input size="35" type="text" name="xplain1" <?php if($xplain1==""){?>onClick="document.myform.xplain1.value='';"<?php } ?> value="<?php if($xplain1=="") {echo "1."; }else { echo "$xplain1";}?>"><br>
                <input size="35" type="text" name="xplain2" <?php if($xplain2==""){?>onClick="document.myform.xplain2.value='';"<?php } ?> value="<?php if($xplain2==""){ echo "2.";} else { echo "$xplain2";}?>"><br>
                <input size="35" type="text" name="xplain3" <?php if($xplain3==""){?>onClick="document.myform.xplain3.value='';"<?php } ?> value="<?php if($xplain3==""){ echo "3."; }else { echo "$xplain3";}?>"><br>
                <input size="35" type="text" name="xplain4" <?php if($xplain4==""){?>onClick="document.myform.xplain4.value='';"<?php } ?> value="<?php if($xplain4==""){ echo "4."; }else { echo "$xplain4";}?>"><br>
    			<input size="35" type="text" name="xplain5" <?php if($xplain5==""){?>onClick="document.myform.xplain5.value='';"<?php } ?> value="<?php if($xplain5==""){ echo "5."; }else{ echo "$xplain5";}?>">

                </td>
             </tr>
             <tr>
             	<td>SEBAB BERHENTI/PINDAH</td>
                <td>:</td>
                <td><select name="reason">
                <?php
                if($uid!="")
                            {
                                echo "<option value=\"$reason\">$reason</option>";
                            }else{
                ?>
                <option value="">[ PILIH ]</option>
                 <?php
                            }
                                $sql="select * from type where grp='resigncos' order by val";
                                $res=mysql_query($sql)or die("query failed:".mysql_error());
                                while($row=mysql_fetch_assoc($res)){
                                            $s=strtoupper($row['prm']);
                                        
                                            echo "<option value=\"$s\">$s</option>";
                                }
                            ?>
                        
                        </select>
                        
                        <?php if(is_verify('ADMIN')){?>
        			<input type="button" value="-" onClick="newwindow('../adm/prm.php?grp=resigncos',0)">
                         <?php } ?>
                        </td>
             </tr>
           </table>

           <br>
                    <br>
                
                <div style="background-color: #F5F5F5; color: red;"><marquee behavior="scroll" direction="left" scrollamount="5">:: Teruskan Mengisi Dilangkah Lain Atau Tekan Simpan Untuk Menyimpan Data ::</marquee></div>
                
           
            </div>
            
            
            
                  
        </div ><!--endjqxtabs-->    
        
        
    </div><!--endjjqxtabs-->
     </div>  
  </form>
</body>
</html>