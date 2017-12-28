<?php
//06/08/2010 - sms/email
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include("$MYOBJ/fckeditor/fckeditor.php");
verify('ADMIN|AKADEMIK|KEWANGAN|HR');
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (uid='$search' or ic='$search' or name like '$search%')";
	
		$jobdiv=stripslashes($_REQUEST['jobdiv']);
		if($jobdiv!="")
			$sqljobdiv="and jobdiv='".addslashes($jobdiv)."'";
		
		$job=stripslashes($_REQUEST['job']);
		if($job!="")
			$sqljob="and job='".addslashes($job)."'";
		
			
		$jobsta=stripslashes($_REQUEST['jobsta']);
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$job=$_REQUEST['job'];
		if($div!="")
			$sqljob="and job='$job'";
			
		$jobsta=$_REQUEST['jobsta'];
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$tamat=$_REQUEST['tamat'];
		if($tamat=="")
			$tamat=0;
		$sqltamat="and status=$tamat";
		
			
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
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
		$sqlsort="order by $sort $order, id desc";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

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

<script language="JavaScript">
function cbview(sta)
{
	cells = document.getElementsByName('tdcb');
	for(j = 0; j < cells.length; j++){
		if(sta)
			cells[j].style.display = 'block';
		else
			cells[j].style.display = 'none';
	}
}


function send_sms(){
	var cflag=false;
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='cblist')){
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
		document.myform.p.value='../estaff/sms';
		document.myform.submit();
	}
	return;
}
function send_mel(){
	var cflag=false;
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='cblist')){
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
		document.myform.p.value='../estaff/mel';
		document.myform.submit();
	}
	return;
}
function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>=maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("120 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx+" CHARACTERS";
        }
}
</script>

</head>

<body >

 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 <input type="hidden" name="p" value="<?php echo $p;?>">
 <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
 <input name="order" type="hidden" id="order" value="<?php echo $order;?>">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="../estaff/usrreg.php" title="Daftar Staff" rel="gb_page_center[1000, 480]" target="_blank" id="mymenuitem" ><img src="../img/new.png"><br>New</a>
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refesh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="show('sms');hide('mel');cbview(1);" id="mymenuitem"><img src="../img/flash.png"><br>SMS</a>
				<a href="#" onClick="show('mel');hide('sms');cbview(1);" id="mymenuitem"><img src="../img/email.png"><br>Email</a>
		</div> <!-- end mymenu -->

		<div id="viewpanel" align="right"  >
			   <select name="sid" id="sid" onChange="document.myform.submit();">
                <?php
			if($sid==""){
            	echo "<option value=\"\">- Semua Staff -</option>";
				echo "<option value=\"0\">- Staff All Access -</option>";
      		}else if($sid=="0"){
            	echo "<option value=\"0\">- Staff All Access -</option>";
				echo "<option value=\"\">- Semua Staff -</option>";
			}else{
                echo "<option value=$sid>$ssname</option>";
			}
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid>0){
					echo "<option value=\"\">- Semua Staff -</option>";
					echo "<option value=\"0\">- Staff All Access -</option>";
				}
		}
?>
              </select>
			  <select name="jobdiv" id="jobdiv" onChange="document.myform.submit();">
<?php	
      		if($jobdiv=="")
            	echo "<option value=\"\">- Semua Bahagian -</option>";
			else
                echo "<option value=\"$jobdiv\">$jobdiv</option>";
			$xjobdiv=addslashes($jobdiv);
			$sql="select * from type where grp='jobdiv' and prm!='$xjobdiv'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
           echo "<option value=\"\">- Semua Bahagian -</option>";			  
			
?>
  </select>
  
  <select name="job" id="job" onChange="document.myform.submit();">
<?php	
      		if($job=="")
            	echo "<option value=\"\">- Semua Jawatan -</option>";
			else
                echo "<option value=\"$job\">$job</option>";
			
			$xjob=addslashes($job);
			$sql="select * from type where grp='job' and prm!='$xjob'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
            echo "<option value=\"\">- Semua Jawatan -</option>";
			
?>
  </select>
  <select name="jobsta" id="jobsta" onchange="document.myform.submit();">
<?php	
      		if($jobsta=="")
            	echo "<option value=\"\">- Semua Status -</option>";
			else
                echo "<option value=\"$jobsta\">$jobsta</option>";
			$sql="select * from type where grp='jobsta' and prm!='$jobsta'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
            echo "<option value=\"\">- Semua Status -</option>";				  
			
?>
  </select>
			  	<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>">                
                <input type="submit" name="Submit" value="View" >
				<br>
				<input type="checkbox" name="tamat" value="1" onClick="document.myform.submit();" <?php if($tamat) echo "checked";?>><font size="-2">Staff Tamat/Berhenti</font>
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>

</div> <!-- end viewpanel -->


</div> <!-- end mypanel -->
<div id="story">
<div id="sms" style="display:none">
<?php if(!$ON_XGATE){ $disabled="disabled"; ?>
		<div style="font-size:120%; font-weight:bold; color:#FF0000; text-decoration:blink"> E-SMS BELUM DIAKTIFKAN. SILA HUBUNGI PIHAK AWFATECH UNTUK PENGAKTIFAN SMS. TQ</div>
<?php } ?>

		<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('sms');cbview(0);"><img src="../img/close16.png">&nbsp;SMS COMPOSER</a></div>
		<br>
		<font color="blue">
		Langkah 1: Tandakan / Tick pada senarai penerima<br>
		Langkah 2: Taip mesej anda<br>
		Langkah 3: Tekan butang send<br>
		</font>
		<textarea name="msg" style="width:50%; font-size:180%; color:#0000FF" rows="5" id="msg" onkeypress="kira(this,this.form.jum,120);"></textarea>
		<br>
		<input type="text" name="jum" value="120 CHARACTERS" size="10" onBlur="kira(this.form.jum,this,120);" disabled>
		<input type="button" value="SEND THIS SMS ..." onClick="send_sms();" <?php echo $disabled;?> >
</div>
<div id="mel" style="display:none ">
		<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('mel');cbview(0);"><img src="../img/close16.png">&nbsp;EMAIL COMPOSER</a></div>
		<br>
		<font style="font-size:14px; color:#666666; font-weight:bold">Subject : </font>
		<input name="melsub" type="text" size="116">

		<table width="800px">
  <tr>
    <td>
		<?php
		//$sBasePath = $_SERVER['PHP_SELF'] ;
		//$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$sBasePath="$MYOBJ/fckeditor/";//hardcode path set by apai
		$fck = new FCKeditor('melmsg') ;
		$fck->BasePath = $sBasePath ;
		$fck->Height='300';
		//if ( isset($_GET['Toolbar']) )
			//$oFCKeditor->ToolbarSet = preg_replace("/[^a-z]/i", "", $_GET['Toolbar']);
		$fck->ToolbarSet = preg_replace("/[^a-z]/i", "", "Custom");//null = Default
		
		//$fck->Value = $raw;
		$fck->Create() ;
		?>
    </td>
  </tr>
</table>
<input type="button" value="SEND THIS MAIL ..." onClick="send_mel();" >
</div>


<div id="mytitlebg">PROFILE STAFF : <?php echo strtoupper($sname);?></div>

<table width="100%" cellspacing="0">
	<tr>
			<td id="tdcb" name="tdcb" width="1%" class="printhidden" style="display:none " ><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'cblist')"></td>
         	<td id="mytabletitle" width="2%"  align="center">BIL</td>
			<td id="mytabletitle" width="8%"  align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort">STAFFID</a></td>
            <td id="mytabletitle" width="25%" >&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">NAMA</a></td>
			<td id="mytabletitle" width="10%">&nbsp;<a href="#" onClick="formsort('jobdiv','<?php echo "$nextdirection";?>')" title="Sort">BAHAGIAN</a></td>
        	<td id="mytabletitle" width="20%">&nbsp;<a href="#" onClick="formsort('job','<?php echo "$nextdirection";?>')" title="Sort">JAWATAN</a></td>
			<td id="mytabletitle" width="8%">&nbsp;<a href="#" onClick="formsort('jobsta','<?php echo "$nextdirection";?>')" title="Sort">STATUS</a></td>
			<td id="mytabletitle" width="10%" align="center">HANDPHONE</td>
			<td id="mytabletitle" width="8%" align="center">TELEFON</td>
			<td id="mytabletitle" width="25%" align="center">EMAIL</td>
	</tr>
	<?php	
			
	$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xid=$row['id'];
			$uid=$row['uid'];
			$name=ucwords(strtolower(stripslashes($row['name'])));
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$ll=$row['ll'];
			$ll=strtok($ll," ");
			$xjob=ucwords(strtolower(stripslashes($row['job'])));
			$xjobsta=ucwords(strtolower(stripslashes($row['jobsta'])));
			$status=$row['status'];
			if($status==0)
				$status="Aktif";
			else
				$status="Tamat";
			$xjobdiv=ucwords(strtolower(stripslashes($row['jobdiv'])));
			$syslevel=$row['syslevel'];
					 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td id="tdcb" name="tdcb" align="center" class="printhidden" style="display:none "><input type=checkbox name=cblist[] id="cblist" value="<?php echo "$xid";?>" onClick="checkbox_checkall(0,'cblist')"></td>
					<td id="myborder" align="center"><?php echo "$q";?></td>
					<td id="myborder" align="center"><?php echo "$uid";?></td>
					<td id="myborder"><a href="../estaff/usr_info.php?uid=<?php echo $uid;?>" title="Profile Staff" rel="gb_page_center[800, 480]" target="_blank"><?php echo "$name";?></a></td>
					<td id="myborder"><?php echo "$xjobdiv";?></td>
					<td id="myborder"><?php echo "$xjob";?></td>
					<td id="myborder"><?php echo "$xjobsta";?></td>
					<td id="myborder" align="center"><?php echo "$hp";?></td>
					<td id="myborder"align="center"><?php echo "$tel";?></td>
					<td id="myborder"><?php echo "$mel";?></td>
            </tr>

<?php }  ?>
 </table>
 

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
