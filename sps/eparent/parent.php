<?php
//BASE ON STUDENT
//22/04/2010 - update view by STUDENT
$vmod="v6.0.0";
$vdate="110921";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];


		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
	
		if($sid==0){
			if(!$PARENT_CHILD_ALL_SCHOOL)
				$sqlsid=" and stu.sch_id=$sid";
		}else{
			$sqlsid=" and stu.sch_id=$sid";
		}

		$viewdll=$_REQUEST['viewdll'];
		$viewpak=$_REQUEST['viewpak'];
		$viewwali=$_REQUEST['viewwali'];
		$viewmak=$_REQUEST['viewmak'];
		$viewex=$_REQUEST['viewex'];
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel!=""){
			$sqlclslevel=" and level='$clslevel'";
		}
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year='$year'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
			$clslevel=$row['cls_level'];
		}
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqlsortcls=",cls_name asc";
		}
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name / $lg_ic_number -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (p1ic='$search' or p2ic='$search' or p1name like '%$search%' or p2name like '%$search%')";
			$search=stripslashes($search);
		}

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$simg=$row['img'];
			$namatahap=$row['clevel'];	
            mysql_free_result($res);					  
		}		
			
		$showgaji=$_REQUEST['showgaji'];
		$showanak=$_REQUEST['showanak'];
		$showalamat=$_REQUEST['showalamat'];
		

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
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, name asc";



?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">
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
		document.myform.p.value='../eparent/sms';
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
		document.myform.p.value='../eparent/mel';
		document.myform.submit();
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
</script>
<SCRIPT LANGUAGE="JavaScript">
function set_check(sta){
	for (var i=0;i<document.myform.elements.length;i++){
		var e=document.myform.elements[i];
        if ((e.type=='checkbox')&&(e.id=='viewpak')){
			e.checked=sta;
        }
		if ((e.type=='checkbox')&&(e.id=='viewmak')){
			e.checked=sta;
        }
		if ((e.type=='checkbox')&&(e.id=='viewdll')){
			e.checked=sta;
        }
	}
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
	<input type="hidden" name="p" value="<?php echo $p;?>">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>

				<!-- <a href="#" onClick="if(document.myform.sid.value==''){alert ('Please select school');return false;}showhide('sms','');hide('mel');hide('listview');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/flash.png"><br><?php echo $lg_sms;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div> -->
				<a href="#" onClick="showhide('mel','');hide('sms');hide('listview');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/email.png"><br><?php echo $lg_email;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="showhide('listview','');hide('sms');hide('mel');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/listview22.png"><br><?php echo $lg_option;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div> <!-- right -->
</div><!-- end mypanel-->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

			<select name="year" onChange="document.myform.submit();">
				<?php
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$lg_session $s</option>";
					}
					mysql_free_result($res);					  
				?>
          </select>
		  
			  <select name="sid" id="sid" onChange="document.myform.clscode[0].value='';document.myform.submit();">
                <?php	
      		if($sid==0){
				if($PARENT_CHILD_ALL_SCHOOL)
            		echo "<option value=\"\">- $lg_all $lg_school $lg_parent -</option>";
				else
					echo "<option value=\"\">- $lg_select $lg_school -</option>";
			}
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($PARENT_CHILD_ALL_SCHOOL){
					if($sid>0)
						echo "<option value=\"\">- $lg_all $lg_school $lg_parent -</option>";
				}
			}							  
			
?>
              </select>
	<select name="clslevel" onChange="document.myform.clscode[0].value=''; document.myform.submit();">
        <?php    
		if($clslevel=="")
            echo "<option value=\"\">- $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }
		if($clslevel!="")
            echo "<option value=\"\">- $lg_all -</option>";

?>
      </select>
			 <select name="clscode" id="clscode" onChange="document.myform.submit();">
                  <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_class -</option>";
					}
					//$sql="select * from ses_cls where sch_id='$sid' and cls_code!='$clscode' and year=$year order by cls_level";
					$sql="select * from ses_cls where sch_id='$sid' and year='$year' $sqlclslevel order by cls_level";

            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
						if($clscode==$a){$selected="selected";}else{$selected="";}
                        echo "<option value=\"$a\" $selected>$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all -</option>";
			?>
                </select>
				<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>"> 
				
                				<div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
                
               <input type="submit" name="Submit" value="<?php echo $lg_view;?>">

				<input type="checkbox" value="1" name="showanak" <?php if($showanak) echo "checked";?> onClick="document.myform.submit();"> Show Child
				<!-- 
				&nbsp;&nbsp;
				<?php if(is_verify('ADMIN|KEUANGAN')){?>
					<input type="checkbox" value="1" name="showgaji" <?php if($showgaji) echo "checked";?> onClick="document.myform.submit();"> Show Income
				<?php } ?>
				 -->

</div>
<div id="story">

<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo $lg_search_for;?>... '<?php echo $search;?>'</div><?php }?>

<div id="mytitle2"><?php echo $lg_parent_information;?></div>

<div id="listview" style="display:none ">

<div id="mytitlebg">
	<a href="#" title="Close" onClick="hide('listview');">&nbsp;VIEW OPTION</a> &raquo;
	&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="set_check(1)"> CHECK ALL</a> | <a href="#" onClick="set_check(0)"> CLEAR ALL</a>
	&nbsp;&nbsp;&nbsp; &raquo; &nbsp;&nbsp;&nbsp;
	<a href="#" onClick="document.myform.submit()" title="Click to View">VIEW RECORD</a>
</div>
<table width="100%"><tr><td id="mytitlebg" valign="top" width="15%">
<table width="100%" border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_father_information;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewpak[] id=viewpak value="p1name|<?php echo $lg_name;?>" 
			<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1name|$lg_name"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_name;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1ic|<?php echo $lg_ic_number;?>" 
			<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1ic|$lg_ic_number"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_ic_number;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1hp|<?php echo $lg_handphone;?>" 
				<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1hp|$lg_handphone"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_handphone;?></td>
	  </tr> 
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1mel|<?php echo $lg_email;?>" 
			<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1mel|$lg_email"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_email;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1job|<?php echo $lg_job;?>" 
			<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1job|$lg_job"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_job;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1com|<?php echo $lg_employer;?>" 
				<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1com|$lg_employer"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_employer;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewpak[] id=viewpak value="p1sal|<?php echo $lg_salary;?>" 
				<?php for($i=0;$i<count($viewpak);$i++){ if($viewpak[$i]=="p1sal|$lg_salary"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_salary;?></td>
	  </tr>
</table>
</td><td id="mytitlebg" valign="top" width="15%">

<table width="100%" border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_mother_information;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewmak[] id=viewmak value="p2name|<?php echo $lg_name;?>" 
			<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2name|$lg_name"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_name;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2ic|<?php echo $lg_ic_number;?>" 
			<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2ic|$lg_ic_number"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_ic_number;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2hp|<?php echo $lg_handphone;?>" 
				<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2hp|$lg_handphone"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_handphone;?></td>
	  </tr> 
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2mel|<?php echo $lg_email;?>" 
			<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2mel|$lg_email"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_email;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2job|<?php echo $lg_job;?>" 
			<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2job|$lg_job"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_job;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2com|<?php echo $lg_employer;?>" 
				<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2com|$lg_employer"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_employer;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewmak[] id=viewmak value="p2sal|<?php echo $lg_salary;?>" 
				<?php for($i=0;$i<count($viewmak);$i++){ if($viewmak[$i]=="p2sal|$lg_salary"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_salary;?></td>
	  </tr>
</table>



</td>
<td valign="top" id="mytitlebg">

<table  border="0" >
	  <tr>
			<td colspan="2"><?php echo "Data Wali";?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewwali[] id=viewwali value="p3name|<?php echo $lg_name;?>" 
			<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3name|$lg_name"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_name;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewwali[] id=viewwali value="p3ic|<?php echo $lg_ic_number;?>" 
			<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3ic|$lg_ic_number"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_ic_number;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewwali[] id=viewwali value="p3hp|<?php echo $lg_handphone;?>" 
				<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3hp|$lg_handphone"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_handphone;?></td>
	  </tr> 
	  <tr>
	  		<td><input type="checkbox" name=viewwali[] id=viewwali value="p3mel|<?php echo $lg_email;?>" 
			<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3mel|$lg_email"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_email;?></td>
	  </tr>

	  <tr>
	  		<td><input type="checkbox" name=viewwali[] id=viewwali value="p2rel|<?php echo "Hubungan";?>" 
				<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3rel|Hubungan"){ echo "checked"; break;}}?>>
			<td><?php echo "Hubungan";?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewwali[] id=viewwali value="p2sal|<?php echo "Telepon";?>" 
				<?php for($i=0;$i<count($viewwali);$i++){ if($viewwali[$i]=="p3tel|Telepon"){ echo "checked"; break;}}?>>
			<td><?php echo "Telepon";?></td>
	  </tr>
</table>

</td>			
			
			<td valign="top" id="mytitlebg">

<table border="0" >
	  <tr>
			<td colspan="2"><?php echo $lg_other_information;?></td>
	  </tr>
	  <tr>
	  		<td width="1%"><input type="checkbox" name=viewdll[] id=viewdll value="addr|<?php echo $lg_address;?>" 
			<?php for($i=0;$i<count($viewdll);$i++){ if($viewdll[$i]=="addr|$lg_address"){ echo "checked"; break;}}?>>
			<td><?php echo $lg_address;?></td>
	  </tr>
	  <tr>
	  		<td><input type="checkbox" name=viewdll[] id=viewdll value="tel|<?php echo $lg_telephone;?>" 
			<?php for($i=0;$i<count($viewdll);$i++){ if($viewdll[$i]=="tel|$lg_telephone"){ echo "checked"; break;}}?>>
	  		<td><?php echo $lg_telephone;?></td>
	  </tr>
	  
</table>

</td>



</tr></table>

</div><!-- listview -->
<div id="sms" style="display:none">
		<?php if(!$ON_XGATE){ $disabled="disabled"; ?>
				<div style="font-size:120%; font-weight:bold; color:#FF0000; text-decoration:blink"><?php echo "SMS BELUM DIAKTIFKAN. SILAHKAN HUBUNGI PIHAK KUANTUM UNTUK PENGAKTIFAN SMS. TQ";?></div>
		<?php } ?>
		
				<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('sms');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;SMS Composer</a></div>
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
				<textarea name="msg" style="width:50%; font-size:180%; color:#0000FF" rows="5" id="msg" onKeyPress="kira(this,this.form.jum,1000);"></textarea>
				<br>
				
				<input type="text" name="jum" value="1000" size="4"  style="border:none; background-color:#FFFFFF; "onBlur="kira(this.form.jum,this,1000);" disabled>
				<input type="button" value="SEND THIS SMS ..." onClick="send_sms();" <?php echo $disabled;?> >
                <br>
<br>

</div>
<div id="mel" style="display:none ">
		<div id="mytitlebg" style="font-size:14px "><a href="#" title="Close" onClick="hide('mel');"><img src="<?php echo $MYLIB;?>/img/close16.png">&nbsp;Email Composer</a></div>
		<br>
		<font style="font-size:14px; color:#666666; font-weight:bold">Subject : </font>
		<input name="melsub" type="text" size="116">

		<table width="800px">
		  <tr><td>
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
			</td></tr>
		</table>
<input type="button" value="SEND THIS MAIL ..." onClick="send_mel();" ><br>
		ADD ATTACHMENTS:<br>
		1. <input type="file" name="file1" size="48"><br>
		2. <input type="file" name="file2" size="48"><br>
		3. <input type="file" name="file3" size="48"><br>
        <br>
<br>

</div>



<?php  if((count($viewpak)==0)&&(count($viewmak)==0)&&(count($viewdll)==0)&&(count($viewwali)==0)){?>
<table width="100%" cellspacing="0">
		<tr>
				<td class="mytableheader" style="border-right:none; padding:2px">&nbsp;</td>
				<td class="mytableheader" style="border-right:none; padding:2px">&nbsp;</td>
				 <?php if($ISPARENT_ACC){?>
						<td class="mytableheader" style="border-right:none; padding:2px">&nbsp;</td>
				<?php } ?>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="3"><?php echo $lg_father_information;?></td>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="1"><?php echo $lg_other_information;?></td>
				<?php if($showanak){?>
						<td class="mytableheader" style="border-right:none; padding:2px"><?php echo $lg_child_information;?></td>
				<?php } ?>
		</tr>
		<tr>
				<td class="mytableheader" style="border-right:none; border-top:none" class="printhidden" width="1%"><input type=checkbox name=checkall id="checkall" value="0" onClick="checkbox_checkall(1,'cblist')"></td>
				<td class="mytableheader" style="border-right:none; border-top:none" width="1%" align="center"><?php echo $lg_no;?></td>
				 <?php if($ISPARENT_ACC){?>
						<td class="mytableheader" style="border-right:none; border-top:none" width="3%" align="center"><?php echo $lg_account;?></td>
				<?php } ?>
				<td class="mytableheader" style="border-right:none; border-top:none" width="15%"><?php echo $lg_name;?></td>
				<td class="mytableheader" style="border-right:none; border-top:none" width="5%"><?php echo $lg_ic_number;?></td>
				<td class="mytableheader" style="border-right:none; border-top:none" width="5%"><?php echo $lg_handphone;?></td>
				<!-- 
				<td class="mytableheader" style="border-right:none;" width="15%"><?php echo $lg_mother;?></td>
				<td class="mytableheader" style="border-right:none;" width="5%"><?php echo $lg_ic_number;?></td>
				<td class="mytableheader" style="border-right:none;" width="5%"><?php echo $lg_handphone;?></td>
				 -->
				<td class="mytableheader" style="border-right:none; border-top:none" width="25%"><?php echo $lg_address;?></td>
			<?php if($showanak){?>
				<td class="mytableheader" style="border-right:none; border-top:none" width="20%"><?php echo $lg_child;?></td>
			<?php } ?>
		</tr>
 <?php
if(($clscode=="")&&($clslevel=="")){
		$sql="select distinct(p1ic) from stu where status=6 $sqlsid $sqlsearch $sqlsort $sqlmaxline";
		$sql2="select count(distinct(p1ic)) from stu where status=6 $sqlsid $sqlsearch";
}else{
		$sql="select distinct(p1ic) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.status=6 $sqlsid $sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch and ses_stu.year='$year' $sqlsort $sqlmaxline";
		$sql2="select count(distinct(p1ic)) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.status=6 $sqlsid $sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch and ses_stu.year='$year'";
}
	//echo $sql;
	$res=mysql_query($sql2,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
	$q=$curr;
	
$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($rowx=mysql_fetch_assoc($resx)){
		$p1ic=$rowx['p1ic'];
		
		$sql="select * from stu where p1ic='$p1ic' and status=6 order by id desc";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$xid=$row['id'];
		$acc=$row['acc'];
		$p1name=ucwords(strtolower(stripslashes($row['p1name'])));
		$p1hp=$row['p1hp'];
		$p1mel=$row['p1mel'];
		$p2mel=$row['p2mel'];
		$p1job=ucwords(strtolower(stripslashes($row['p1job'])));
		$p1com=ucwords(strtolower(stripslashes($row['p1com'])));
		$p1sal=$row['p1sal'];
		$p2name=ucwords(strtolower(stripslashes($row['p2name'])));
		$p2ic=$row['p2ic'];
		$p2hp=$row['p2hp'];
		$p2job=ucwords(strtolower(stripslashes($row['p2job'])));
		$p2com=ucwords(strtolower(stripslashes($row['p2com'])));
		$p2sal=$row['p2sal'];
		
		$p3name=ucwords(strtolower(stripslashes($row['p2name'])));
		$p3ic=$row['p3ic'];
		$p3hp=$row['p3hp'];
		$p3tel=$row['p3tel'];
		
		$p3rel=$row['p2rel'];
		
		$addr=ucwords(strtolower(stripslashes($row['addr'])));
		$poskod=ucwords(strtolower(stripslashes($row['poskod'])));
		$bandar=ucwords(strtolower(stripslashes($row['bandar'])));
		
		$tel=$row['tel'];
		$hh=0;$noanak=0;$namaanak="";
		
		$sql="select * from stu where p1ic='$p1ic' and status=6 $sqlsid order by id desc";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$name=ucwords(strtolower(stripslashes($row['name'])));
				$xuid=$row['uid'];
				$noanak++;
				if($noanak==1)
					$namaanak="$noanak."."<a href=\"../adm/stu_profile.php?uid=$xuid\" target=_blank>$xuid-$name</a>";
				else
					$namaanak=$namaanak."<br>$noanak."."<a href=\"../adm/stu_profile.php?uid=$xuid\" target=_blank>$xuid-$name</a>";
		}
		if($noanak==0)
			continue;
		if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td class="myborder" style="border-right:none; border-top:none;" class="printhidden"><input type=checkbox name=cblist[] id="cblist" value="<?php echo "$xid";?>" onClick="checkbox_checkall(0,'cblist')"></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
				<?php if($ISPARENT_ACC){?>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$acc";?></td>
				<?php } ?>
				<td class="myborder" style="border-right:none; border-top:none;"><a href="../eparent/parentreg.php?id=<?php echo $xid;?>" target="_blank"><?php echo "$p1name";?></a></td>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$p1ic";?></td>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$p1hp";?></td>
				<!-- 
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$p2name";?></td>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$p2ic";?></td>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$p2hp";?></td>
				 -->
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$addr $poskod $bandar";?></td>
			<?php if($showanak){?>
					<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$namaanak";?></td>
			<?php } ?>
		</tr>
		
<?php } ?>
</table>
<?php include("../inc/paging.php");?>
<?php }else { ?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
				<td class="mytableheader" style="border-right:none; padding:2px">&nbsp;</td>
				<td class="mytableheader" style="border-right:none; padding:2px">&nbsp;</td>
		<?php if(count($viewpak)>0){?>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="<?php echo count($viewpak);?>"><?php echo $lg_father_information;?></td>
		<?php }if(count($viewmak)>0){?>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="<?php echo count($viewmak);?>"><?php echo $lg_mother_information;?></td>
		<?php }if(count($viewwali)>0){?>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="<?php echo count($viewmak);?>"><?php echo "Data Wali";?></td>	
		<?php }if(count($viewdll)>0){?>
				<td class="mytableheader" style="border-right:none; padding:2px" colspan="<?php echo count($viewdll);?>"><?php echo $lg_other_information;?></td>
		<?php } ?>
				<?php if($showanak){?>
						<td class="mytableheader" style="border-right:none; padding:2px"><?php echo $lg_child_information;?></td>
			<?php } ?>
	</tr>
	<tr >
			  <td class="mytableheader" style="border-right:none; border-top:none;" class="printhidden" width="1%"><input type=checkbox name=checkall id="checkall" value="0" onClick="checkbox_checkall(1,'cblist')"></td>
			  <td class="mytableheader" style="border-right:none; border-top:none" width="1%" align="center">Bil</td>
<?php for($i=0;$i<count($viewpak);$i++){
			$dat=explode("|",$viewpak[$i]);
			if($sqlfield!="")
				$sqlfield=$sqlfield.",";
			$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none; border-top:none" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>
<?php for($i=0;$i<count($viewmak);$i++){
			$dat=explode("|",$viewmak[$i]);
			if($sqlfield!="")
				$sqlfield=$sqlfield.",";
			$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none; border-top:none" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>

<?php for($i=0;$i<count($viewwali);$i++){
			$dat=explode("|",$viewwali[$i]);
			if($sqlfield!="")
				$sqlfield=$sqlfield.",";
			$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none; border-top:none" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>

<?php for($i=0;$i<count($viewdll);$i++){
			$dat=explode("|",$viewdll[$i]);
			if($sqlfield!="")
				$sqlfield=$sqlfield.",";
			$sqlfield=$sqlfield.$dat[0];
?>
			  <td class="mytableheader" style="border-right:none; border-top:none" width="5%"><a href="#" onClick="formsort('<?php echo $dat[0];?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $dat[1];?></a></td>
<?php } ?>
				<?php if($showanak){?>
				<td class="mytableheader" style="border-right:none; border-top:none" width="20%"><?php echo $lg_child;?></td>
			<?php } ?>
	</tr>
	
 <?php
 $q=0;
if(($clscode=="")&&($clslevel=="")){
		$sql="select distinct(p1ic) from stu where status=6 $sqlsid $sqlsearch $sqlsort $sqlmaxline";
		$sql2="select count(distinct(p1ic)) from stu where status=6 $sqlsid $sqlsearch";
}else{
		$sql="select distinct(p1ic) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.status=6 $sqlsid $sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch and ses_stu.year='$year' $sqlsort $sqlmaxline";
		$sql2="select count(distinct(p1ic)) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.status=6 $sqlsid $sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch and ses_stu.year='$year'";
}

	$res=mysql_query($sql2,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
	$q=$curr;
	
$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($rowx=mysql_fetch_assoc($resx)){
		$p1ic=$rowx['p1ic'];
		
		$sql="select $sqlfield,id,p1sal,p2sal from stu where p1ic='$p1ic' and status=6";
		$resxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$rowxx=mysql_fetch_row($resxx);
		$xid=$rowxx[count($view)];
		$p1sal=$rowxx[count($view)+1];
		$p2sal=$rowxx[count($view)+2];
		$hh=0;$noanak=0;$namaanak="";
		$sql="select * from stu where p1ic='$p1ic' and status=6 $sqlsid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$name=ucwords(strtolower(stripslashes($row['name'])));
				$xuid=$row['uid'];
				$noanak++;
				if($noanak==1)
					$namaanak="$noanak. $xuid-$name";
				else
					$namaanak=$namaanak."<br>$noanak. $xuid-$name";
		 }
		if($noanak==0)
			continue;
		if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td class="myborder" style="border-right:none; border-top:none;" class="printhidden"><input type=checkbox name=cblist[] id="cblist" value="<?php echo "$xid";?>" onClick="checkbox_checkall(0,'cblist')"></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
<?php for($i=0;$i<count($viewpak);$i++){?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo ucwords(strtolower(stripslashes($rowxx[$i])));?></td>
<?php }?>
<?php for(;$i<count($viewpak)+count($viewmak);$i++){?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo ucwords(strtolower(stripslashes($rowxx[$i])));?></td>
<?php }?>
<?php for(;$i<count($viewpak)+count($viewmak)+count($viewdll);$i++){?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo ucwords(strtolower(stripslashes($rowxx[$i])));?></td>
<?php }?>

<?php for(;$i<count($viewpak)+count($viewmak)+count($viewwali)+count($viewdll);$i++){?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo ucwords(strtolower(stripslashes($rowxx[$i])));?></td>
<?php }?>
			<?php if($showanak){?>
				<td class="myborder" style="border-right:none; border-top:none;"><?php echo $namaanak;?></td>
			<?php } ?>
		</tr>
		
<?php } ?>
</table>

<?php include("../inc/paging.php");?>

<?php } ?>
</div></div>

</form> <!-- end myform -->



</body>
</html>
