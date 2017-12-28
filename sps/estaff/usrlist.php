<?php
//110610 - upgrade gui
//120609 - update field
//120805 - update sex 0/1 filed
$vmod="v6.2.0";
$vdate="120805";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
ISACCESS("estaff",1);
$username = $_SESSION['username'];
$isajax=$_REQUEST['isajax'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (uid='$search' or name like '%$search%')";
	
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
				$sqltamat="and status=0";
		else	
				$sqltamat="and status!=0";
		
		$viewlist=$_REQUEST['viewlist'];
			
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
<title><?php echo $lg_staff;?></title>
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

<script language="JavaScript">
var newwin = "";
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
			alert('Please checked the recipient');
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
		document.myform.action='../estaff/sms.php';
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
		document.myform.target="newwindow";
		document.myform.action='../estaff/mel.php';
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
                alert("1000 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx;
        }
}
function set_check(sta){
	for (var i=0;i<document.myform.elements.length;i++){
		var e=document.myform.elements[i];
        if ((e.type=='checkbox')&&(e.id=='viewlist')){
			e.checked=sta;
        }
	}
}
function excel(page){ 
	document.formexcel.action=page;
    document.formexcel.submit();
}
</script>

<script language="JavaScript">
function process_search(op){
		var xmlhttp;
		var msg;
		msg=document.myform.search.value;
		
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("story").innerHTML=xmlhttp.responseText;
				$(".fbbig").fancybox({
					'width'				: '90%',
					'height'			: '100%',					
					'autoScale'			: true,					
					'type'				: 'iframe',					
				});
			}
		}
		xmlhttp.open("GET","usrlist.php?isajax=1&search="+msg,true);
		xmlhttp.send();

}
</script>
</head>

<body >
<?php if(!$isajax){?>
 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
 <input type="hidden" name="p" value="<?php echo $p;?>">
 <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
 <input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="../estaff/usrreg.php" class="fbbig" target="_blank" id="mymenuitem" ><img src="<?php echo $MYLIB;?>/img/new.png"><br><?php echo $lg_new;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="excel('../estaff/excel.php');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/excel.png"><br><?php echo $lg_export;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>

				<?php if(ISACCESS("esms",0)){?>
				<!-- <a href="#" onClick="showhide('sms','');hide('mel');hide('listview');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/flash.png"><br><?php echo $lg_sms;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div> -->
				<?php } ?>
				<a href="#" onClick="showhide('mel','');hide('sms');hide('listview');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/email.png"><br><?php echo $lg_email;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>            
				<a href="#" onClick="showhide('listview','');hide('mel');hide('sms');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/listview22.png"><br><?php echo $lg_option;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="clear_newwindow();document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" >
<select name="sid" id="sid" onChange="clear_newwindow();document.myform.submit();">
                <?php
			if($sid==""){
            	echo "<option value=\"\">- $lg_all $lg_staff -</option>";
				echo "<option value=\"0\">- All Access -</option>";
      		}else if($sid=="0"){
            	echo "<option value=\"0\">- All Access -</option>";
				echo "<option value=\"\">- $lg_all $lg_staff -</option>";
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
					echo "<option value=\"\">- $lg_all $lg_staff -</option>";
					echo "<option value=\"0\">- All Access -</option>";
				}
		}
?>
              </select>
			  <select name="jobdiv" id="jobdiv" onChange="clear_newwindow();document.myform.submit();">
<?php	
      		if($jobdiv=="")
            	echo "<option value=\"\">- $lg_all $lg_division -</option>";
			else
                echo "<option value=\"$jobdiv\">$jobdiv</option>";
			$xjobdiv=addslashes($jobdiv);
			$sql="select * from type where grp='jobdiv' and prm!='$xjobdiv'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
           echo "<option value=\"\">- $lg_all $lg_division -</option>";			  
			
?>
  </select>
  
  <select name="job" id="job" onChange="clear_newwindow();document.myform.submit();">
<?php	
      		if($job=="")
            	echo "<option value=\"\">- $lg_all $lg_position -</option>";
			else
                echo "<option value=\"$job\">$job</option>";
			
			$xjob=addslashes($job);
			$sql="select * from type where grp='job' and prm!='$xjob'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
            echo "<option value=\"\">- $lg_all $lg_position -</option>";
			
?>
  </select>
  <select name="jobsta" id="jobsta" onchange="clear_newwindow();document.myform.submit();">
<?php	
      		if($jobsta=="")
            	echo "<option value=\"\">- $lg_all $lg_status -</option>";
			else
                echo "<option value=\"$jobsta\">$jobsta</option>";
			$sql="select * from type where grp='jobsta' and prm!='$jobsta'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
            echo "<option value=\"\">- $lg_all $lg_status -</option>";				  
			
?>
  </select>
			  	
				 <input name="search" style="font-size:14px" type="text"  id="search" size="22" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
				 onKeyUp="process_search();" value="<?php if($search=="") echo "- $lg_name -"; else echo "$search";?>">                      
				
				<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px;" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				&nbsp;
                <input type="submit" name="Submit" onClick="clear_newwindow();document.myform.submit();" value="<?php echo $lg_view;?> .." style="color:#03F; font-weight:bold;">
				&nbsp;&nbsp;
				<input type="checkbox" name="tamat" value="1" onClick="clear_newwindow();document.myform.submit();" <?php if($tamat) echo "checked";?>>
				<?php echo "Staff Yang Berhenti Saja";?>

</div>
<div id="story">
<?php } //isajax ?>

<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo $lg_search_for;?>... '<?php echo $search;?>'</div><?php }?>

<div id="mytitle2"><?php echo $lg_staff_profile;?>  <?php echo $sname;?></div>


<div id="sms" style="display:none">
<?php if(!$ON_XGATE){ $disabled="disabled"; ?>
		<div style="font-size:120%; font-weight:bold; color:#FF0000; text-decoration:blink"> 
			<?php echo "Sms Belum Diaktifkan. Silahkan Hubungi Pihak Kuantum Untuk Pengaktifan SMS. TQ";?>
		</div>
<?php } ?>

		<div id="mytitle2"><a href="#" title="Close" onClick="hide('sms');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;SMS Composer</a></div>
		<div id="mytitlebg">

            <textarea name="msg" style="width:40%; font-size:180%; color:#0000FF" rows="5" id="msg" onkeypress="kira(this,this.form.jum,1000);"></textarea>
            <br>
            <input type="text" name="jum" value="1000" size="4" style="border:none; font-size:16px" onBlur="kira(this.form.jum,this,1000);" disabled>
            <input type="button" value="SEND THIS SMS .." onClick="send_sms();" <?php echo $disabled;?> >
        </div>
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

<div id="listview"  style="display:none">
<div id="mytitlebg">
	<a href="#" title="Close" onClick="hide('listview');">&nbsp;VIEW OPTION</a> &raquo;
	&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="set_check(1)"> CHECK ALL</a> | <a href="#" onClick="set_check(0)"> CLEAR ALL</a>
	&nbsp;&nbsp;&nbsp; &raquo; &nbsp;&nbsp;&nbsp;
	<a href="#" onClick="document.myform.submit()" title="Click to View">VIEW RECORD</a>
</div>
<table width="100%">
<tr><td id="mytitlebg" valign="top" width="15%">
<table width="100%" border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_personal_information;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="uid|<?php echo $lg_staff_id;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="uid|$lg_staff_id"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_staff_id;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="name|<?php echo $lg_name;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="name|$lg_name"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_name;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="ic|<?php echo $lg_ic_number;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="ic|$lg_ic_number"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_ic_number;?></td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="bday|<?php echo $lg_birth_date;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="bday|$lg_birth_date"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_birth_date;?></td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="sex|<?php echo $lg_sex;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="sex|$lg_sex"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_sex;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="race|<?php echo $lg_race;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="race|$lg_race"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_race;?></td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="religion|<?php echo $lg_religion;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="religion|$lg_religion"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_religion;?></td>
	  </tr>
	   <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="citizen|<?php echo $lg_citizen;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="citizen|$lg_citizen"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_citizen;?></td>
	  </tr>
	 
</table>
</td><td id="mytitlebg" valign="top" width="15%">

<table width="100%" border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_contact_information;?></td>
	  </tr>
	   <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="hp|<?php echo $lg_handphone;?>" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="hp|$lg_handphone"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_handphone;?></td>
	  </tr> 
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="mel|<?php echo $lg_email;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="mel|$lg_email"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_email;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="addr|<?php echo $lg_address;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="addr|$lg_address"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_address;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="state|<?php echo $lg_state;?>" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="state|$lg_state"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_state;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="country|<?php echo $lg_country;?>" 
				<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="country|$lg_country"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_country;?></td>
	  </tr>
</table>

</td><td id="mytitlebg" valign="top">

<table width="100%" border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_job_information;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewlist[] id=viewlist value="job|<?php echo $lg_position;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="job|$lg_position"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_position;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="jobdiv|<?php echo $lg_division;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="jobdiv|$lg_division"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_division;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="jobsta|<?php echo $lg_status;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="jobsta|$lg_status"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_status;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="edulevel|<?php echo $lg_qualification;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="edulevel|$lg_qualification"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_qualification;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="jobstart|<?php echo $lg_start_date;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="jobstart|$lg_start_date"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_start_date;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewlist[] id=viewlist value="jobend|<?php echo $lg_end_date;?>" 
			<?php for($i=0;$i<count($viewlist);$i++){ if($viewlist[$i]=="jobend|$lg_end_date"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_end_date;?></td>
	  </tr>
	  
</table>

</td>


</tr></table>

</div><!-- voption -->


<?php if(count($viewlist)<1){?>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
			<td class="mytableheader" style="border-right:none;" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'cblist')"></td>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" width="8%"  align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_staff_id);?></a></td>
            <td class="mytableheader" style="border-right:none;" width="25%">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="10%">&nbsp;<a href="#" onClick="formsort('jobdiv','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_division);?></a></td>
        	<td class="mytableheader" style="border-right:none;" width="15%">&nbsp;<a href="#" onClick="formsort('job','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_position);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="7%" align="center"><a href="#" onClick="formsort('jobsta','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_status);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="7%" align="center"><?php echo strtoupper($lg_tel_mobile);?></td>
			<td class="mytableheader" style="border-right:none;" width="7%" align="center"><?php echo strtoupper($lg_telephone);?></td>
			<td class="mytableheader" width="25%">&nbsp;<?php echo strtoupper($lg_email);?></td>
	</tr>
	<?php	
			
	$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xid=$row['id'];
			$uid=$row['uid'];
			$name=strtoupper(stripslashes($row['name']));
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$ll=$row['ll'];
			$ll=strtok($ll," ");
			$xjob=ucwords(strtolower(stripslashes($row['job'])));
			$xjobsta=ucwords(strtolower(stripslashes($row['jobsta'])));
			$status=$row['status'];
			$xjobdiv=ucwords(strtolower(stripslashes($row['jobdiv'])));
			$syslevel=$row['syslevel'];
					 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><input type=checkbox name=cblist[] id="cblist" value="<?php echo "$xid";?>" onClick="checkbox_checkall(0,'cblist')"></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;<a href="../estaff/usr_info.php?uid=<?php echo $uid;?>" class="fbbig" target="_blank"><?php echo "$name";?></a></td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;<?php echo "$xjobdiv";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;<?php echo "$xjob";?></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$xjobsta";?></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$hp";?></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$tel";?></td>
					<td class="myborder" style="border-top:none;"><?php echo "$mel";?></td>
            </tr>

<?php }  ?>
 </table>
 
<?php }else{  ?>
<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo $lg_no;?></td>
<?php
		for($i=0;$i<count($viewlist);$i++){
				$dat=explode("|",$viewlist[$i]);
				if($sqlfield!="")
					$sqlfield=$sqlfield.",";
				$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none;" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>
	<?php	
	$q=0;
	$sql="select $sqlfield from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_row($res)){
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
									
<?php 
for($i=0;$i<count($viewlist);$i++){
			
			$str=ucwords(strtolower(stripslashes($row[$i])));
			if($str=="")
				$str="&nbsp;";
			
			//this is to replase 0/1 sex field
			$xfield=explode("|",$viewlist[$i]);
			if($xfield[0]=="sex")
				$str=$lg_malefemale[$str];
?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$str";?></td>
<?php }?>
            </tr>

<?php }  ?>
	</tr>
</table>
<?php }  ?>
<?php if(!$isajax){?>
</div><!-- story -->
</div><!-- content -->
</form>	
<?php } //if!ajax?>
<form name="formexcel" method="post">
 	<input type="hidden" name="sql">
</form>
</body>
</html>
