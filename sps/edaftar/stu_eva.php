<?php
$vmod="v5.0.0";
$vdate="05/01/11";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
ISACCESS("eregister",1);
$username = $_SESSION['username'];
$p=$_REQUEST['p'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y')+1;
		$sqlyear="and sesyear='$year'";
			
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$status=$_POST['status'];
		if($status=="")
			$sqlstatus="and status>1";
		else
			$sqlstatus="and status=$status";
			
		$sex=$_POST['sex'];
		if($sex!="")
			$sqlsex="and sex=$sex";
			
		$isgaji=$_REQUEST['isgaji'];
		if($isgaji!=""){
			if($isgaji=="1000")
				$sqlisgaji="and totalsal<1000";
			elseif($isgaji=="3000")
				$sqlisgaji="and (totalsal>=1000 and totalsal<3000)";
			elseif($isgaji=="5000")
				$sqlisgaji="and (totalsal>=3000 and totalsal<5000)";
			else
				$sqlisgaji="and totalsal>=5000";
		}
		
		$isnegeri=$_REQUEST['isnegeri'];
			if($isnegeri=="")
				$sqlisnegeri="";
			else
				$sqlisnegeri="and anaknegeri=$isnegeri";
				
		$israyu=$_REQUEST['israyu'];
		if($israyu!=""){
			$sqlisrayu="and israyu=$israyu";
		}
		
		$isyatim=$_POST['isyatim'];
		if($isyatim!=""){
			if($sqlprofile!="")
					$sqlprofile=$sqlprofile." or isyatim=$isyatim";
				else
					$sqlprofile="isyatim=$isyatim";
		}
		$isanak=$_POST['isanak'];
		if($isanak!=""){
				if($sqlprofile!="")
					$sqlprofile=$sqlprofile." or anaknegeri>0";
				else
					$sqlprofile="anaknegeri>0";
		}

		$ispprt=$_POST['ispprt'];
		if($ispprt!=""){
				if($sqlprofile!="")
					$sqlprofile=$sqlprofile." or ispprt=$ispprt";
				else
					$sqlprofile="ispprt=$ispprt";
		}
			
		$isibu=$_POST['isibu'];
		if($isibu!=""){
				if($sqlprofile!="")
					$sqlprofile=$sqlprofile." or isibu=$isibu";
				else
					$sqlprofile="isibu=$isibu";
		}
		if($sqlprofile!="")
			$sqlprofile="and (".$sqlprofile.")";
			
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$schlevel=$row['level'];
		
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['sname'];
			$schlevel=$row['level'];
			
			
			if($schlevel=="Diploma")
				$namaexam="SPM";
			else
				$namaexam="UPSR";
			$sqlsid="and sch_id=$sid";

			$search=$_REQUEST['search'];
			if(strcasecmp($search,"- $lg_name / $lg_ic_number -")==0)
				$search="";
			if($search!=""){
				$search=addslashes($search);
				$sqlsearch = "and (ic='$search' or name like '%$search%')";
				$search=stripslashes($search);
				$status="";
				$sqlstatus="";
			}
			if($sqlsearch!="")
				$sqlyear="";
				
				
		$stu=$_POST['stu'];
		$op=$_POST['op'];
		if($op=="updatestatus"){
			$cs=$_POST['changestatus'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set status='$cs' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
		if($op=="pusat"){
			$pt=$_POST['pusat'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set sch_id='$pt' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}

/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=30;
		$sqlmaxline="limit $curr,$MAXLINE";
	}
	elseif($MAXLINE=="All"){
		$sqlmaxline="";
	}
	else{
		$sqlmaxline="limit $curr,$MAXLINE";
	}
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort, name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
var newwin = "";
function process_letter() 
{ 
	var cflag=false;
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='stuid')){
						if(e.checked==true)
                               cflag=true;
    
                }
    }
	if(document.myform.sid.value==0){
			alert('Please select school');
			document.myform.sid.focus();
			return;
	}
	if(document.myform.status.value==''){
			alert('Please select student status');
			document.myform.status.focus();
			return;
	}
	if(document.myform.letterid.value==''){
			alert('Please select letter template');
			document.myform.letterid.focus();
			return;
	}
	if(!cflag){
			alert('Please checked the item you wish to process');
			return;
	}
	document.myform.target="newwindow";
	document.myform.action='letter.php';
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
	
}


function process_form(process) 
{ 
	var cflag=false;
	if(process=='delete'){
			for (var i=0;i<document.myform.elements.length;i++){
						var e=document.myform.elements[i];
						if ((e.id=='stuid')){
								if(e.checked==true)
									   cflag=true;
			
						}
			}
			if(!cflag){
					alert('Please checked the item you wish to delete');
					return;
			}
			if(confirm('Are you sure you want to delete')){
				document.myform.op.value=process;
				document.myform.submit();	
			}
	}
	else if(process=='updatestatus'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the item to update');
			return;
		}
		if(document.myform.changestatus.value==""){
			alert('Please set new status');
			return;
		}
		ret = confirm("UPDATE STATUS??");
		if (ret == true){
			document.myform.op.value=process;
			document.myform.submit();
		}
		return;
	}
	else{
		ret = confirm("UPDATE STATUS??");
		if (ret == true){
			document.myform.op.value=process;
			document.myform.submit();
		}
	}
}

function excel(page){ 
	document.myform.action=page;
    document.myform.submit();
}
function send_sms(){
	var cflag=false;
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='stuid')){
						if(e.checked==true)
                               cflag=true;
    
                }
    }
	if(!cflag){
			alert('Please checked the receipient');
			return;
	}
	
	if(document.myform.msg.value==""){
    	alert("Please enter the message");
        document.myform.msg.focus();
        return;
    }
	ret = confirm("Send this SMS??");
	if (ret == true){
		document.myform.target="newwindow";
		document.myform.action='sms.php';
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
	}
	return;
}
function send_mel(){
	var cflag=false;
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='stuid')){
						if(e.checked==true)
                               cflag=true;
    
                }
    }
	if(!cflag){
			alert('Please checked the receipient');
			return;
	}
	if(document.myform.melsub.value==""){
    	alert("Please enter the message title");
        document.myform.melsub.focus();
        return;
    }
	ret = confirm("Send this Email??");
	if (ret == true){
		document.myform.target="newwindow";
		document.myform.action='mel.php';
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
	}
	return;
}
function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>=maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("Exceeded Maximum..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx;
        }
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<?php $sql="select * from stureg where sch_id>0 $sqlpt $sqlsid $sqlstatus $sqlsearch $sqlsort";?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
    <input type="hidden" name="op">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
    <div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="showhide('letter','');hide('mel');hide('sms');hide('updatestatus');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/mail22.png"><br><?php echo $lg_letter;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="showhide('sms','');hide('mel');hide('updatestatus');hide('letter');" id="mymenuitem">
        <img src="<?php echo $MYLIB;?>/img/flash.png"><br>SMS</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
    <a href="#" onClick="showhide('mel','');hide('sms');hide('updatestatus');hide('letter');" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/email.png"><br><?php echo $lg_email;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>     
    <a href="#" onClick="showhide('updatestatus','');hide('mel');hide('sms');hide('letter');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/tool.png"><br>Set Status</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
    <a href="eva-rep.php" target="_blank" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/graphbar.png"><br>Placement</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clear_newwindow();document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	</div>
    <div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>	
	</div>
    
</div><!-- end mypanel-->
<div id="story">
<div id="mytitlebg" class="printhidden">

<select name="year" onChange="clear_newwindow();document.myform.submit();">
<?php
	echo "<option value=\"$year\">$lg_intake $year</option>";
	$sql="select * from type where grp='session' and prm!='$year' order by val desc";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$lg_intake $s</option>";
    }		
	  
?>
</select>
	  
			  <select name="sid" id="sid" onChange="clear_newwindow();document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				//$sql="select * from type where grp='edaftarprogram' and sid!='$sid'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							//$s=$row['prm'];
                            //$t=$row['sid'];
							echo "<option value=$t>$s</option>";
				}
			}					  	  
			
?>
              </select>
			 <select name="sex" id="sex" onChange="clear_newwindow();document.myform.submit();">
<?php	
				if($sex=="")
					echo "<option value=\"\">- $lg_sex -</option>";
				else{
					$sexname=$lg_malefemale[$sex];
					echo "<option value=$sex>$sexname</option>";
				}
				$sql="select * from type where grp='sex' order by val";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
							$t=$row['val'];
							echo "<option value=$t>$s</option>";
				}
				if($sex!="")
					echo "<option value=\"\">- $lg_all-</option>";  	  
			
?>
</select>            
<select name="status" onChange="clear_newwindow();document.myform.submit();">
<?php	
      		if($status==""){
            	echo "<option value=\"\">- $lg_status -</option>";
				$sql="select * from type where grp='statusmohon' and val>1 order by val";
			}
			else{
			    $sql="select * from type where grp='statusmohon' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $a=$row['prm'];
				$b=$row['val'];
				mysql_free_result($res);	
                echo "<option value=\"$b\">$a</option>";
				echo "<option value=\"\">- $lg_all -</option>";
				$sql="select * from type where grp='statusmohon' and val>1 and val!=$status order by val";
			}
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
?>
              </select>    
			  <input name="search" type="text" id="search" size="32"
				onMouseDown="clear_newwindow();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>">
				<input type="button" name="Submit" value="VIEW" onClick="clear_newwindow();document.myform.submit();" style="font-size:11px;">      
<br>
<br>
<label style="cursor:pointer"><input type="checkbox" name="isanak" value="1" <?php if($isanak) echo "checked";?>> Warga Terengganu </label>
<label style="cursor:pointer"><input type="checkbox" name="ispprt" value="1" <?php if($ispprt) echo "checked";?>> PPRT </label>
<label style="cursor:pointer"><input type="checkbox" name="isibu" value="1"  <?php if($isibu) echo "checked";?>> Ibu Tunggal </label>
<label style="cursor:pointer"><input type="checkbox" name="isyatim" value="1" <?php if($isyatim) echo "checked";?>> Anak Yatim </label>
<br><br>

<select name="isgaji" onChange="clear_newwindow();document.myform.submit();">
<?php
	if($isgaji!=""){
		if($isgaji=="1000")
				echo "<option value=\"$isgaji\"> Kurang 1000</option>";
			elseif($isgaji=="3000")
				echo "<option value=\"$isgaji\">1000-2999</option>";
			elseif($isgaji=="5000")
				echo "<option value=\"$isgaji\">3000-4999</option>";
			else
				echo "<option value=\"$isgaji\">Lebih 5000</option>";
		
	}
?>
<option value="">Pendapatan Keluarga (Semua)</option>
<option value="1000">Kurang 1000</option>
<option value="3000">1000-2999</option>
<option value="5000">3000-4999</option>
<option value="5001">Lebih 5000</option>
</select>
<select name="isnegeri" onChange="clear_newwindow();document.myform.submit();">
<?php
			if($isnegeri=="")
				echo "<option value=\"$isgaji\"> Semua Negeri</option>";
			elseif($isnegeri=="0")
				echo "<option value=\"0\">Lain-Lain</option>";
			else
				echo "<option value=\"$isnegeri\">$ANAK_NEGERI - $isnegeri</option>";
?>
<option value="1"><?php echo $ANAK_NEGERI;?> - 1</option>
<option value="2"><?php echo $ANAK_NEGERI;?> - 2</option>
<option value="3"><?php echo $ANAK_NEGERI;?> - 3</option>
<option value="0">Lain-Lain</option>
<option value="">Semua Negeri</option>
</select>
<br>
<br>

</div>

<div id="letter" style="display:none">
<div id="mytitlebg">LETTER SETTING</div>
<div id="mytitlebg">
	Tips<br>
	- 1st select school<br>
    - 2st select student status<br>
    - 3rd select letter template<br>
<br>

    
	<select name="letterid">
		<?php
					echo "<option value=\"\">- Select the Letter Template -</option>";
					$sql="select * from letter where type='student_register' and sid=$sid order by id";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$id=$row['id'];
								$name=$row['name'];
								echo "<option value=\"$id\">$name</option>";
					}	  
		?>
		</select>
        	&lt;<a href="../adm/letter_config.php?sid=<?php echo $sid;?>&type=student_register" target="_blank" title="Config Letter">
        	 Edit Template
    </a>&gt;
    <br>
    <label style="cursor:pointer">
    <input type="checkbox" name="ismel" value="1"> 
	Do you want this letter send as an email??
    
    </label>
    <br>
<br>
    <input type="button" value="Generate This Letter" style="width:200px; font-size:18px; color:#00F;" onClick="process_letter();">
<br><br>
</div>



</div>
<div id="sms" style="display:none">
<?php if(!$ON_XGATE){ $disabled="disabled"; ?>
		<div style="font-size:120%; font-weight:bold; color:#FF0000; text-decoration:blink"> 
			<?php echo $lg_sms_activation_note;?>
		</div>
<?php } ?>

		<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('sms');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;SMS COMPOSER</a></div>
		<br>
		<font color="blue">
			<?php if($LG=="BM"){?>
				Langkah 1: Tandakan / Tick pada senarai penerima<br>
				Langkah 2: Taip mesej anda<br>
				Langkah 3: Tekan butang send<br>
			<?php }else{?>
				Step 1: Tick at the recepient name<br>
				Step 2: Type your message<br>
				Step 3: Click send button<br>
			<?php }?>
		</font>
		<textarea name="msg" style="width:50%; font-size:180%; color:#0000FF" rows="5" id="msg" onKeyPress="kira(this,this.form.jum,140);"></textarea>
		<br>

		<input type="text" name="jum" value="140" size="4" style="border:none; font-size:20px; font-weight:bold" disabled>
		<input type="button" value="SEND THIS SMS ..." onClick="send_sms();" <?php echo $disabled;?> >
</div>
<div id="mel" style="display:none ">
		<div id="mytitle2"><a href="#" title="Close" onClick="hide('mel');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;Email Composer</a></div>
		<div id="mytitlebg">
		<font style="font-size:14px; color:#666666; font-weight:bold">Subject : </font>
        
		<input name="melsub" type="text" size="80">
		<input type="button" value="SEND THIS MAIL .." onClick="send_mel();">
		<table width="750px">
		  <tr>
			<td>
				<?php
				$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
				$fck = new FCKeditor('melmsg') ;
				$fck->BasePath = $sBasePath ;
				$fck->Height='300';
				$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
				$fck->Create() ;
				?>
			</td>
		  </tr>
		</table>
		ATTACHMENTS:<br>
		1. <input type="file" name="file1" size="48"><br>
		2. <input type="file" name="file2" size="48"><br>
		3. <input type="file" name="file3" size="48"><br>
        </div>

</div>

<div id="updatestatus" style="display:none;">
  <div id="mytitlebg">SET STATUS - Tips: Tick the student, change to new status and clik save</div>
  		<table width="100%">
        	<tr>
            	<td id="myborder" width="20%">STATUS PERMOHONAN</td>
            	<td width="80%">    		
                    <select name="changestatus">
                    <?php	
                    echo "<option value=\"\">- Set Status Permohonan -</option>";
                    $sql="select * from type where grp='statusmohon' and val>1 order by val";
                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                    while($row=mysql_fetch_assoc($res)){
                                $s=$row['prm'];
                                $v=$row['val'];
                                echo "<option value=\"$v\">$s</option>";
                    }
                    ?>
                    </select>
                    <input type="button" value="SAVE" onClick="clear_newwindow();process_form('updatestatus')">
            </td>
            </tr>
            <tr>
            	<td id="myborder">SET SEKOLAH</td>
            	<td>
                	<select name="pusat">
					<?php	
							echo "<option value=\"\">- Set Sekolah-</option>";
                           $sql="select * from sch where id!='$sid' order by name";
                            $res=mysql_query($sql)or die("query failed:".mysql_error());
                            while($row=mysql_fetch_assoc($res)){
								$a=$row['id'];
								$b=$row['name'];
								echo "<option value=\"$a\">$b</option>";
                            }  
                    ?>
  					</select><input type="button" value="SAVE" onClick="clear_newwindow();process_form('pusat')">
                </td>
            </tr>
            </td>
            </tr>
        </table> 
</div><!--CHANGE updatestatus DIV -->
<div id="mytitlebg">Penilaian Pelajar:</div>
	
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
			<td id="mytabletitle" rowspan="2" width="1%" align="center" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'stuid')"></td>
              <td id="mytabletitle" align="center" rowspan="2" width="1%"><?php echo strtoupper("$lg_no");?></td>
			  <td id="mytabletitle" align="center" rowspan="2" width="5%"><?php echo strtoupper("$lg_intake");?></td>
			  <td id="mytabletitle" align="center" width="1%" rowspan="2">
              <a href="#" onClick="clear_newwindow();formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
			  <?php echo strtoupper("$lg_mf");?></a></td>
              <td id="mytabletitle" align="center" rowspan="2" width="20%">
              <a href="#" onClick="formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
			  <?php echo strtoupper("$lg_student_name");?></a></td>
              <td id="mytabletitle" align="center" rowspan="2" width="7%" align="center"><?php echo strtoupper("$lg_ic_number");?></td>
			  <td id="mytabletitle"  align="center" rowspan="2" width="2%">
              <a href="#" onClick="clear_newwindow();formsort('anaknegeri <?php echo "$nextdirection";?>,test1 desc','<?php echo "$nextdirection";?>')" title="Sort">
			  TGNU</a></td>
              <td id="mytabletitle"  align="center" rowspan="2" width="2%"><a href="#" onClick="clear_newwindow();formsort('ispprt <?php echo "$nextdirection";?>,test1 desc','<?php echo "$nextdirection";?>')" title="Sort">
			  PPRT</a></td>
              <td id="mytabletitle"  align="center" rowspan="2" width="2%"><a href="#" onClick="clear_newwindow();formsort('isibu <?php echo "$nextdirection";?>,test1 desc','<?php echo "$nextdirection";?>')" title="Sort">
			  IBU.T</a></td>
              <td id="mytabletitle"  align="center" rowspan="2" width="2%"><a href="#" onClick="clear_newwindow();formsort('isyatim <?php echo "$nextdirection";?>,test1 desc','<?php echo "$nextdirection";?>')" title="Sort">
			  YATIM</a></td>
              <td id="mytabletitle"  align="center" rowspan="2" width="2%"><a href="#" onClick="clear_newwindow();formsort('totalsal <?php echo "$nextdirection";?>,test1 desc','<?php echo "$nextdirection";?>')" title="Sort">
			  INCOME</a></td>

			  <?php $sql="select * from type where grp='examtemuduga' and code='TEMUDUGA' order by idx,id";
					$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					$numsub=mysql_num_rows($res);
				?>
              <td id="mytabletitle" colspan="<?php echo $numsub;?>" align="center"><?php echo strtoupper("$lg_interview_mark");?></td>
			  <td id="mytabletitle" rowspan="2"  align="center" width="3%">
              	<a href="#" onClick="formsort('test1 <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
				<?php echo strtoupper("$lg_total_mark");?></a></td>
			  <td id="mytabletitle" rowspan="2" align="center" width="3%">
              	<a href="#" onClick="formsort('upsr_result <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
				<?php echo "$namaexam";?></a></td>
              <td id="mytabletitle" rowspan="2"  width="5%">
			  	<a href="#" onClick="formsort('status <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
				<?php echo strtoupper("$lg_status");?></a>
			 </td>

      </tr>
      <tr>
<?php 
	$sql="select * from type where grp='examtemuduga' and code='TEMUDUGA' order by idx,id";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$numsub=mysql_num_rows($res);
	while($row=mysql_fetch_assoc($res)){
		$subname=$row['prm'];
?>
          <td id="mytabletitle" align="center" width="5%"><?php echo $subname;?></td>
<?php }?>
      </tr>
<?php
	$sql="select count(*) from stureg where confirm=1 $sqlyear $sqlpt $sqlsid $sqlstatus $sqlsex $sqlisgaji $sqlisnegeri $sqlprofile $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;
	
	$q=$curr;
	$sql="select * from stureg where confirm=1 $sqlyear $sqlpt $sqlsid $sqlstatus $sqlsex $sqlisgaji $sqlisnegeri $sqlprofile $sqlsearch $sqlsort $sqlmaxline";
	//echo $sql;
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$ic=$row['ic'];
			$name=strtoupper(stripslashes($row['name']));
			$dt=$row['cdate'];
			$dt=strtok($dt," ");
			$hp=$row['hp'];
			$sesyear=$row['sesyear'];
			$sch=$row['sch_id'];
			$transid=$row['transid'];
			$pt=$row['pt'];
			$sta=$row['status'];
			$test1=$row['test1'];
			$ekomark=$row['ekomark'];
			$upsr=$row['upsr_result'];
			$anak=$row['anaknegeri'];
			$pprt=$row['ispprt'];
			$yatim=$row['isyatim'];
			$ibu=$row['isibu'];
			$gaji=$row['totalsal'];
			
			$bstate=$row['bstate'];
			$p1bstate=$row['p1bstate'];
			$p2bstate=$row['p2bstate'];
			$anaknegeri=0;
			if(strcasecmp($bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
			if(strcasecmp($p1bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
			if(strcasecmp($p2bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
				
			$sex=$lg_sexmf[$row['sex']];
			$pschool=ucwords(strtolower(stripslashes($row['pschool'])));
			$sql2="select * from type where grp='statusmohon' and val=$sta";
			$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        	$row2=mysql_fetch_assoc($res2);
        	$statusapply=$row2['prm'];
			
			//surat
				if($sta==3)
					$surat="TEMUDUGAGAGAL";
				if($sta==4)
					$surat="SEMINAR";
					
			if(($q++%2)==0)
				$bg="$bglyellow";
			else
				$bg="$bglyellow";
			
			if($sta==3)
				$bg="$bglred"; //gagal temuduga
			elseif($sta==4)
				$bg="$bglred"; //tidak hadir
			elseif($sta==13)
				$bg="$bglred"; //gagal
			elseif($sta==15)
				$bg="$bglred"; //tolak
			elseif($sta==10)
				$bg="$bglyellow"; //tawaran
			elseif($sta==12)
				$bg="$bglgreen"; //terima
			elseif($sta==11)
				$bg="$bglblue"; //register
			else
				$bg="#FAFAFA";
?>       
    <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$id";?>" onClick="checkbox_checkall(0,'stuid')"></td>
		<td align="center" id="myborder"><?php echo "$q";?></td>
		<td id="myborder" align="center"><?php echo "$sesyear";?></td>
		<td id="myborder" align="center"><?php echo "$sex";?></td>
		<td id="myborder"><a href="../edaftar/stu_info.php?id=<?php echo $id;?>" target="_blank" onClick="return GB_showPage('Profile : <?php echo addslashes($name);?>',this.href)"><img src="../img/profile10.png" class="printhidden">&nbsp;<?php echo "$name";?></a></td>
		<td id="myborder"><?php echo "$ic";?></td>
		<td id="myborder" align="center"><?php echo "$anak";?></td>
        <td id="myborder" align="center"><?php echo $pprt;?></td>
        <td id="myborder" align="center"><?php echo $ibu;?></td>
        <td id="myborder" align="center"><?php echo $yatim;?></td>
        <td id="myborder" align="center"><?php echo round($gaji);?></td>
<?php	 
	$sql="select * from type where grp='examtemuduga' and code='TEMUDUGA' order by idx,id";
	$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$subname=$row2['prm'];
		$sql="select * from stureg_exam where exam='TEMUDUGA' and ic='$ic' and sub='$subname'";
		$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$val=$row3['val'];
		
?>
			<td id="myborder" align="center"><?php echo "$val";?></td>
<?php } ?>     
			<td id="myborder" align="center"><?php echo "$test1";?></td>   
			<td id="myborder" align="center"><?php echo $upsr;?></td>   
			<td id="myborder"><?php echo str_replace(" ","",$statusapply);?></td>

			
        </tr>
<?php  }  ?>
    </table>


	<?php include("../inc/paging.php");?>

	
		
</div></div> 

</form> <!-- end myform -->
</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->