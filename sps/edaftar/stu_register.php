<?php
//STANDARD EDITION
//120117 - ADD REG_FILE
$vmod="v6.0.1";
$vdate="120117";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");

ISACCESS("eregister",1);

		$adm = $_SESSION['username'];
		$p=$_REQUEST['p'];
		$sid=$_REQUEST['sid'];
		$pass='123abc';
		$password=md5($pass);
		if($sid=="")
			$sid=$_SESSION['sid'];
		if($sid>0)
			 $sqlsid="and sch_id=$sid";
			
		$adminregonly=$_POST['adminregonly'];
		if($adminregonly)
			$sqladminregonly=" and isadminreg=1";
		$stu=$_POST['stu'];
		$op=$_POST['op'];
		if($op=="delete"){
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set isdel=1, delby='$adm', delts=now() where id=$id";
					$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
					//$sql="delete from stureg_akademik where xid=$id";
					//$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
			}
		}
		if($op=="changestatus"){
			$cs=$_POST['changestatus'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					if($cs==11){
						$sql="select ic,uid,sch_id from stureg where id='$id'";
						$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
						$row=mysql_fetch_assoc($res);
						$ic=$row['ic'];
						$uid=$row['uid'];
						$xsid=$row['sch_id'];
						$sql="select ic from stu where uid='$uid' and sch_id='$xsid'";
						$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
						$num=mysql_num_rows($res);
						if($num>0){
								$sql="update stureg set sch_id=$sid,status='100',adm='$username',ts=now() where id=$id";
								$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
								//$f="<font color=blue><b>&lt;SUCCESSFULLY UPDATE STATUS. THIS IS EXISTING STUDENT&gt;</b></font>";
						}else{
							if($uid!=""){
								$sql="insert into stu(cdate,sch_id,name,uid,ic,sex,race,religion,bday,ill,tel,hp,mel,addr,state,
								 q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,
								 p1name,p1ic,p1job,p1sal,p1com,p1addr,p1tel,p1tel2,p1fax,p1hp,p1mel,
								 p2name,p2ic,p2job,p2sal,p2com,p2addr,p2tel,p2tel2,p2fax,p2hp,p2mel,
								 isislah,ishostel,rdate,edate,
								 transport,isstaff,isyatim,iskawasan,isfeenew,isfeeonanak,
								 isfeefree,feehutang,poskod,bandar,bstate,intake,
								 adm,ts,status,password) 
								 select now(),sch_id,name,uid,ic,sex,race,religion,bday,ill,tel,hp,mel,addr,state,
								 q0,q1,q2,q3,q4,q5,q6,q7,q8,q9,
								 p1name,p1ic,p1job,p1sal,p1com,p1addr,p1tel,p1tel2,p1fax,p1hp,p1mel,
								 p2name,p2ic,p2job,p2sal,p2com,p2addr,p2tel,p2tel2,p2fax,p2hp,p2mel,
								 isislah,ishostel,now(),edate,
								 transport,isstaff,isyatim,iskawasan,isfeenew,isfeeonanak,
								 isfeefree,feehutang,poskod,bandar,bstate,sesyear,
								 '$username',now(),'6','$pass'
								 from stureg where id=$id";
		
								mysql_query($sql)or die("$sql query failed:".mysql_error());
								$xid=mysql_insert_id();
							
								/**
										if($si_student_global_id)
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
								**/
								
								
								$sql1="update stureg set sch_id=$sid,pt='$pt',status='$cs',clssession='$clssession',adm='$username',ts=now() where id=$id";
								$res1=mysql_query($sql1,$link)or die("$sql1 query failed:".mysql_error());
								$f="<font color=blue><b>&lt;SUCCESSFULLY UPDATE REGISTRATION&gt;</b></font>";
							}
							else {
								$f="<font color=red><b>DATA GAGAL DIEDIT. MASUKKAN NIS SISWA TERLEBIH DAHULU</b></font>";
							}
						}
					}//cs==11
					else {
						$sql="update stureg set status='$cs' where id='$id'";
						mysql_query($sql)or die("$sql failed:".mysql_error());
						$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
					}
			}
		}
		if($op=="setintake"){
			$cs=$_POST['setintake'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set sesyear='$cs' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
if($op=="tarikhtemuduga"){
			$tt=$_POST['tarikhtemuduga'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set tarikhtemuduga='$tt' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
		if($op=="pusat"){
			$pt=$_POST['pusat'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set pt='$pt' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
		if($op=="setsession"){
			$ss=$_POST['setsession'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set clssession='$ss' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
		if($op=="setsurat"){
			$ss=$_POST['setsurat'];
			for ($i=0; $i<count($stu); $i++) {
					$id=$stu[$i];
					$sql="update stureg set letter='$ss' where id='$id'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;Successfully Updated&gt;</font>";
			}
		}
		
		$status=$_POST['status'];
		if($status!="")
			$sqlstatus="and status=$status";
			
			
		$clssession=$_POST['clssession'];
		if($clssession!="")
			$sqlclssession="and clssession='$clssession'";
			
		$clslevel=$_POST['clslevel'];
		if($clslevel!="")
			$sqlclslevel="and cls_level=$clslevel";
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['sname']);
			$schlevel=$row['level'];
			$namatahap=$row['clevel'];
		}
		else
			$namatahap=$lg_level;


		$year=$_POST['year'];
		if($year!=""){
			$sqlyear="and sesyear='$year'";
		}
		
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
		$sqlsort="order by $sort";
		//$sqlsort="order by $sort $order, name asc";


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
	else if(process=='changestatus'){
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
	if(document.myform.father.checked==false && document.myform.mother.checked==false){
		alert('Please checked both or either one receipient mother/father');
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
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"  enctype="multipart/form-data">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
	<?php $sql="select * from stureg where id>0 and isdel=0 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch $sqlsort";?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="<?php echo $REG_FILE;?>" title="New Registration" target="_blank"  id="mymenuitem">
    <img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php if(is_verify('ADMIN|AKADEMIK|HEP')){?>
	<a href="#" onClick="clear_newwindow();process_form('delete');" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	
	<a href="#" onClick="excel('excel.php')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/excel.png"><br>Excel</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="showhide('letter','');hide('mel');hide('sms');hide('changestatus');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/mail22.png"><br><?php echo $lg_letter;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<!-- <a href="#" onClick="if(document.myform.sid.value==''){
    	alert ('Please select school');return false;};showhide('sms','');hide('mel');hide('changestatus');hide('letter');" id="mymenuitem">
        <img src="<?php echo $MYLIB;?>/img/flash.png"><br>SMS</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div> 
    <a href="#" onClick="showhide('mel','');hide('sms');hide('changestatus');hide('letter');" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/email.png"><br><?php echo $lg_email;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>  -->   
    <a href="#" onClick="showhide('changestatus','');hide('mel');hide('sms');hide('letter');" id="mymenuitem"><img src="../img/tool.png"><br>Set Status</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
    <a href="#" onClick="clear_newwindow();document.myform.submit()" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
   	<div align="right">
    	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		
		
	</div>
    
</div><!-- end mypanel-->
<div id="story">
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px">

<select name="year" onChange="clear_newwindow();document.myform.submit();document.myform.clslevel.value='';">
<?php
	if($year=="")
		echo "<option value=\"\">Pilih Tahun Ajaran</option>";
	else
		echo "<option value=\"$year\">$year</option>";
	$sql="select * from type where grp='session' and prm!='$year' order by val desc";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
    }
	if($year!=""){
		echo "<option value=\"\">$lg_all </option>";
	}  
?>
      </select>
						
	<select name="sid" id="sid" onChange="clear_newwindow();document.myform.submit();document.myform.clslevel.value='';">
<?php
		
      		if($sid=="0")
            	echo "<option value=\"\">-Pilih $lg_school -</option>";
			else{
                echo "<option value=$sid>$sname</option>";
				echo "<option value=\"\">- $lg_all $lg_school -</option>";
			}
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				//$sql="select * from type where grp='edaftarprogram' and sid!='$sid'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
                            $t=$row['id'];
							//$s=$row['prm'];
                            //$t=$row['sid'];
							echo "<option value=$t>$s</option>";
				}
			}					  	  
			
?>
              </select>
			  <select name="status" id="status" onChange="clear_newwindow();document.myform.clslevel.value='';document.myform.submit();">
<?php	
      		if($status==""){
            	echo "<option value=\"\">- $lg_all $lg_status -</option>";
				$sql="select * from type where grp='statusmohon' order by val";
			}
			else{
			    $sql="select * from type where grp='statusmohon' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $statusname=$row['prm'];
				$b=$row['val'];
				mysql_free_result($res);	
                echo "<option value=\"$b\">$statusname</option>";
				echo "<option value=\"\">- $lg_all $lg_status -</option>";
				$sql="select * from type where grp='statusmohon' and val!=$status order by val";
			}
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$s</option>";
            }
            mysql_free_result($res);					  

?>
              </select>
			<select name="clslevel" onChange="clear_newwindow();document.myform.submit();">
<?php	
      		if($clslevel=="")
            	echo "<option value=\"\">- Pilih $lg_level -</option>";
			else
				echo "<option value=\"$clslevel\">$lg_level $clslevel</option>";

			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
					$s=$row['prm'];
					echo "<option value=$s>$lg_level $s</option>";
			}
			if($clslevel!="")
            	echo "<option value=\"\">- $lg_all -</option>";
?>
              </select>
              <!--
              <select name="clssession" onChange="clear_newwindow();document.myform.submit();">
						<?php
						if($clssession==$lg_morning){
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
						}elseif($clssession==$lg_afternoon){
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
						}else{
							echo "<option value=\"\">- $lg_session -</option>";
							echo "<option value=\"$lg_morning\">$lg_morning</option>";
							echo "<option value=\"$lg_afternoon\">$lg_afternoon</option>";
						}
						?>
						</select>
                 -->
			  <input type="hidden" name="letter" value="<?php echo $statusname;?>">
			  <input name="search" type="text" id="search" size="32"
				onMouseDown="clear_newwindow();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>">
				<input type="button" name="Submit" value="<?php echo $lg_look_for; ?>" onClick="clear_newwindow();document.myform.submit();" style="font-size:11px;"> 
				
				<input type="checkbox"  name="adminregonly" value="1" <?php if($adminregonly) echo "checked";?> onClick="clear_newwindow();document.myform.submit();">View Admin Register Only
		

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
					$sql="select * from letter where type='student_register' order by id";
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
			<?php echo "SMS BELUM DIAKTIFKAN. SILAHKAN HUBUNGI PIHAK KUANTUM UNTUK PENGAKTIFAN SMS. TQ";?>
		</div>
<?php } ?>

		<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('sms');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;SMS COMPOSER</a></div>
		<br>
		<font color="blue">
				Langkah 1: Tandakan / Tick pada senarai penerima<br>
				Langkah 2: Taip mesej anda<br>
				Langkah 3: Tekan butang send<br>

		</font>
		<textarea name="msg" style="width:50%; font-size:180%; color:#0000FF" rows="5" id="msg" onKeyPress="kira(this,this.form.jum,140);"></textarea>
		<br>
		<input type="checkbox" name="father" value="p1hp"><?php echo "Send To $lg_father";?>&nbsp;<input type="checkbox" name="mother" value="p2hp"><?php echo "Send To $lg_mother";?><br>
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

<div id="changestatus" style="display:none">
  <div id="mytitlebg">SET STATUS - Tips: Tick the student, change to new status and clik save</div>
  
  		<table width="100%" style="font-size:12px">
        	<tr>
            	<td id="myborder" width="20%">STATUS PERMOHONAN </td>
            	<td width="80%">    		
                    <select name="changestatus">
                    <?php	
                    echo "<option value=\"\">- Set Status Permohonan -</option>";
                    $sql="select * from type where grp='statusmohon' order by val";
                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                    while($row=mysql_fetch_assoc($res)){
                                $s=$row['prm'];
                                $v=$row['val'];
                                echo "<option value=\"$v\">$s</option>";
                    }
                    ?>
                    </select>
                    <input type="button" value="SAVE" onClick="clear_newwindow();process_form('changestatus')">
            </td>
            </tr>
			<!--
            <tr>
            	<td id="myborder">Lokasi Wawancara</td>
            	<td>
                	<select name="pusat">
					<?php	
							echo "<option value=\"\">- Pilih Lokasi Wawancara-</option>";
                            $sql="select * from type where grp='pusattemuduga'";
                            $res=mysql_query($sql)or die("query failed:".mysql_error());
                            while($row=mysql_fetch_assoc($res)){
								$a=$row['prm'];
								echo "<option value=\"$a\">$a</option>";
                            }  
                    ?>
  					</select><input type="button" value="SAVE" onClick="clear_newwindow();process_form('pusat')">
                </td>
            </tr>
            <tr>
            	<td id="myborder">Tarikh Wawancara</td>
            	<td>
					<input type="text" name="tarikhtemuduga" size="48" value="<?php echo $tarikhtemuduga;?>"> 
					<input type="button" value="SAVE" onClick="clear_newwindow();process_form('tarikhtemuduga')">
                </td>
            </tr>
            <tr>
            	<td id="myborder">Sesi</td>
                <td>
                	<select name="setsession">
<?php	
      		echo "<option value=\"\">- $lg_set -</option>";
			$sql="select * from type where grp='clssession' and (sid='$sid' or sid='0') order by prm";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
					$s=$row['prm'];
					echo "<option value=\"$s\">$s</option>";
			}
?>
			</select><input type="button" value="SAVE" onClick="clear_newwindow();process_form('setsession')">
            </td>
            </tr>
			-->
			
			<tr>
            	<td id="myborder">SET TAHUN ANGKATAN </td>
                <td>
                	<select name="setintake">
<?php	
			echo "<option value=\"\">- Set Tahun Angkatan -</option>";
			$sql="select * from type where grp='session' order by val desc";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=\"$s\">$s</option>";
			}
?>
			</select><input type="button" value="SAVE" onClick="clear_newwindow();process_form('setintake')">
            </td>
            </tr>
			
			<tr>
            	<td id="myborder">Set Surat</td>
            	<td>
                	<select name="setsurat">
					<?php
					echo "<option value=\"\">- $lg_set -</option>";
					$sql="select * from letter where type='student_register' order by id";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$id=$row['id'];
								$xn=$row['name'];
								echo "<option value=\"$xn\">$xn</option>";
					}	 
                    ?>
  					</select><input type="button" value="SAVE" onClick="clear_newwindow();process_form('setsurat')">
                </td>
            </tr>
        </table> 
</div><!--CHANGE STATUS DIV -->

<div id="mytitlebg"><?php echo strtoupper("$lg_registration_process");?></div>
	
<table width="100%"  cellspacing="0" cellpadding=0>
	<tr>
			  <td class="mytableheader" style="border-right:none;" width="1%" align="center" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'stuid')"></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="1%"><?php echo strtoupper("$lg_no");?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="clear_newwindow();formsort('id <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_date");?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="1%"><a href="#" onClick="clear_newwindow();formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_mf");?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="18%"><a href="#" onClick="clear_newwindow();formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_student_name");?></a></td>
              <td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper("no akte kelahiran/passport siswa");?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="../edaftar/addnis.php?sid=<?php echo $sid ?>&year=<?php echo $year ?>" style="text-decoration:none" target="_blank">NIS</a></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center">NISN</td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper("$lg_intake");?></td>
			  <td class="mytableheader" style="border-right:none;"  width="18%" align="center"><a href="#" onClick="clear_newwindow();formsort('tarikhtemuduga <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">TANGGAL LAHIR</a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper("$lg_handphone");?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="formsort('status <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_status");?></a></td>	 
			   
			   <td class="mytableheader" style="border-right:none;" align="center" width="2%"><?php echo strtoupper("$lg_level");?></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="1%"><a href="#" title="New(1) or Existing(0) student">NEW</a></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="1%">ADM</td>
               <td class="mytableheader" style="border-right:none;" style="border-left:none;" align="center" width="1%">SLIP</td>
			   <td class="mytableheader" style="border-right:none;" style="border-left:none;" align="center" width="1%"><?php echo $lg_letter; ?></td>
      </tr>

<?php
		//$sql="select count(*) from stureg where id>0 $sqlsid $sqlstatus  $sqlsearch and confirm=1";
		$sql="select count(*) from stureg where id>0 and isdel=0 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
		
		$q=$curr;
		$sql="select * from stureg where id>0 and isdel=0 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch $sqlsort $sqlmaxline";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$ic=$row['ic'];
			$uid=$row['uid'];
			$addr=$row['addr'];
			$bday=$row['bday'];
			$name=ucwords(strtolower(stripslashes($row['name'])));
			$hp=$row['hp'];
			$fname=$row['p1name'];
			$mname=$row['p2name'];
			$p1sal=$row['p1sal'];
			$p2sal=$row['p2sal'];
			$dt=strtok($row['cdate']," ");
			$sch=$row['sch_id'];
			$clsses=$row['clssession'];
			$pt=$row['pt'];
			$pschool=ucwords(strtolower(stripslashes($row['pschool'])));
			$upsr=$row['upsr_result'];
			$sta=$row['status'];
			$anaknegeri=$row['anaknegeri'];
			$statusborang=$row['statusborang'];
			$tarikhtemuduga=$row['tarikhtemuduga'];
			$pt=$row['pt'];
			$isadminreg=$row['isadminreg'];
			$istransport=$row['istransport'];
			$sesyear=$row['sesyear'];
			$isnew=$row['isnew'];
			$tahap=$row['cls_level'];
			$transid=$row['transid'];
			$bstate=$row['bstate'];
			$letter=$row['letter'];
			$p1bstate=$row['p1bstate'];
			$p2bstate=$row['p2bstate'];
			$nisn=$row['nisn'];
			$anaknegeri=0;
			if(strcasecmp($bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
			if(strcasecmp($p1bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
			if(strcasecmp($p1bstate,$ANAK_NEGERI)==0)
				$anaknegeri++;
				
			if($anaknegeri>0)
				$anaknegeristatus="Ya";
			else
				$anaknegeristatus="Tidak";
				
			$sex=$lg_sexmf[$row['sex']];
				
			$sql="select * from sch where id='$sch'";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $ssname=stripslashes($row2['sname']);
			
				$sql2="select * from type where grp='statusmohon' and val=$sta";
				$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        		$row2=mysql_fetch_assoc($res2);
        		$statusapply=$row2['prm'];
					
				if(($q++%2)==0)
					$bg="$bglyellow";
				else
					$bg="$bglyellow";

				if($sta==1)
					$bg="$bglred"; //merah
				elseif($sta==3)
					$bg="$bglgreen"; //merah
				elseif($sta==11)
					$bg="$bglgreen"; //green
				else
					$bg="$bglyellow";
				
?>
	<tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td class="myborder" style="border-right:none; border-top:none;" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$id";?>" onClick="checkbox_checkall(0,'stuid')"></td>
		<td align="center" class="myborder" style="border-right:none; border-top:none;"><?php echo "$q";?></td>
		<td align="center" class="myborder" style="border-right:none; border-top:none;"><?php echo "$dt";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$sex";?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><a href="../edaftar/stu_info.php?id=<?php echo $id;?>" style="text-decoration:none" target="_blank" title="Profile"><?php echo "$name";?></a></td>
    	<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$ic";?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$uid";?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$nisn";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $sesyear;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $bday;?></td>		
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$hp";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="left" ><?php echo str_replace(" ","",$statusapply);?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $tahap;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $isnew;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $isadminreg;?></td>
        <td class="myborder" style="border-right:none; border-top:none;" align="center">
<?php if($sta>9){?><a href="#" title="Cetak Surat" onClick="newwindow('../edaftar/letter.php?uid=<?php echo "$id&lid=confirm";?>',0)">
<img src="<?php echo $MYLIB;?>/img/printer12.png"></a>
<?php }?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $letter;?></td>
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