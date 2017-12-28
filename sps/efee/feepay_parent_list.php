<?php
//BASE ON STUDENT
//22/04/2010 - update view by STUDENT
$vmod="v5.0.0";
$vdate="21/07/2010";
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
		$viewmak=$_REQUEST['viewmak'];
		$viewex=$_REQUEST['viewex'];
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$clslevel=$_REQUEST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year=$year";
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
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$simg=$row['img'];
			$namatahap=$row['clevel'];	
            mysql_free_result($res);					  
		}		
			
		$showgaji=$_REQUEST['showgaji'];
		$showanak=$_REQUEST['showanak'];
		$showanak=1;
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
		$sqlsort="order by acc $order";
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
                alert("120 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx+" CHARACTERS";
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
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
				<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<!-- 
				<a href="#" onClick="showhide('voption','');hide('sms');hide('mel');" id="mymenuitem"><img src="../img/option.png"><br>Option</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="showhide('sms','');hide('mel');hide('voption');" id="mymenuitem"><img src="../img/flash.png"><br>SMS</a>
				<a href="#" onClick="showhide('mel','');hide('sms');hide('voption');" id="mymenuitem"><img src="../img/email.png"><br>Email</a>
-->
	</div>
	<div align="right">
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
      		if($sid==""){
				if($PARENT_CHILD_ALL_SCHOOL)
            		echo "<option value=\"\">- $lg_all $lg_parent -</option>";
				else
					echo "<option value=\"\">- $lg_select $lg_school -</option>";
				
			}
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($PARENT_CHILD_ALL_SCHOOL){
					if($sid>0)
						echo "<option value=\"\">- All Parent -</option>";
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
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from ses_cls where sch_id='$sid' and cls_code!='$clscode' and year=$year order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all -</option>";
			?>
                </select>
				<input name="search" type="text" id="search" size="32"
				onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>"> 
				
               <input type="button" name="bview" value="View" onClick="document.myform.submit()" >
				<br>
				<input type="checkbox" value="1" name="showanak" <?php if($showanak) echo "checked";?> onClick="document.myform.submit();"> Show Child
				<!-- 
				&nbsp;&nbsp;
				<?php if(is_verify('ADMIN|KEUANGAN')){?>
					<input type="checkbox" value="1" name="showgaji" <?php if($showgaji) echo "checked";?> onClick="document.myform.submit();"> Show Income
				<?php } ?>
				 -->
				
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div> <!-- right -->
</div><!-- end mypanel-->
<div id="story">


<div id="mytitlebg"><?php echo strtoupper($lg_fee_payment);?></div>

<table width="100%" style="font-size:10px" cellspacing="0">
		<tr>
				<td id="mytabletitle" class="printhidden" width="1%"><input type=checkbox name=checkall id="checkall" value="0" onClick="checkbox_checkall(1,'cblist')"></td>
				<td id="mytabletitle" width="1%" align="center"><?php echo strtoupper($lg_no);?></td>
				 <?php if($ISPARENT_ACC){?>
						<td id="mytabletitle" width="3%" align="center"><a href="#" onClick="formsort('acc','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_account);?></a></td>
				<?php } ?>
				<td id="mytabletitle" width="15%"><a href="#" onClick="formsort('p1name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_parent);?></a></td>
				<td id="mytabletitle" width="5%"><?php echo strtoupper($lg_ic_number);?></td>
				<td id="mytabletitle" width="5%"><?php echo strtoupper($lg_handphone);?></td>
				<td id="mytabletitle" width="25%"><?php echo strtoupper($lg_address);?></td>
				<td id="mytabletitle" width="20%"><?php echo strtoupper($lg_child_name);?></td>
		</tr>
 <?php
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
		
		$sql="select * from stu where p1ic='$p1ic' and status=6 $sqlsid order by id desc";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$xid=$row['id'];
		$acc=$row['acc'];
		$p1name=strtoupper(stripslashes($row['p1name']));
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
					$namaanak="$noanak."."$xuid:$name";
				else
					$namaanak=$namaanak."<br>$noanak."."$xuid:$name";
		}
		if($noanak==0)
			continue;
		if(($q++%2)==0)
			$bg="$bglyellow";
		else
			$bg="$bglyellow";
 ?>

		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
				<td id=myborder class="printhidden"><input type=checkbox name=cblist[] id="cblist" value="<?php echo "$xid";?>" onClick="checkbox_checkall(0,'cblist')"></td>
				<td id=myborder align="center"><?php echo "$q";?></td>
				<?php if($ISPARENT_ACC){?>
					<td id=myborder align="center"><?php echo "$acc";?></td>
				<?php } ?>
				<td id=myborder><a href="<?php echo "../efee/feepay_parent.php?acc=$acc&year=$year&sid=$sid";?>" target="_blank"><?php echo "$p1name";?></a></td>
				<td id=myborder><?php echo "$p1ic";?></td>
				<td id=myborder><?php echo "$p1hp";?></td>
				<td id=myborder><?php echo "$addr $poskod $bandar";?></td>
				<td id=myborder><?php echo "$namaanak";?></td>
		</tr>
<?php }?>
</table>
<?php include("../inc/paging.php");?>

</div></div>

</form> <!-- end myform -->



</body>
</html>
