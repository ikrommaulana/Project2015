<?php
//22/04/2010 - update view by tahap
$vmod="v4.1.0";
$vdate="12/05/2010";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

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
		if(strcasecmp($search,"--Nama / Kp Penjaga--")==0)
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
		}else{
			$namatahap="Tahap";
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
		$sqlsort="order by id $order";
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
function clearwin(){
	document.myform.action="";
	document.myform.target="";
}
function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='surat'){
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
			alert('Please checked the user');
			return;
		}
		document.myform.target="newwindow";
		document.myform.action="../parent/letter.php?id=suratwaris";
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
		return;
	}
	if(action=='sms'){
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
			alert('Please checked the user');
			return;
		}
		document.myform.target="newwindow";
		document.myform.action="../parent/sms_send.php?id=feewarn&month="+month+"&year="+year;
		newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
		var a = window.setTimeout("document.myform.submit();",500);
		newwin.focus();
		return;
	}
	if(action=='email'){
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
			alert('Please checked the user');
			return;
		}
		alert('Comming soon.TQ');
		/**
		ret = confirm("Are you sure want to send EMAIL REMINDER??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		**/
		return;
	}
}

</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="../eparent/parentreg.php" id="mymenuitem" target="_blank"><img src="../img/new.png"><br>New</a>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="clearwin();document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_form('surat')" id="mymenuitem"><img src="../img/pages.png"><br>Letter</a>
		<a href="#" onClick="process_form('sms')" id="mymenuitem"><img src="../img/flash.png"><br>SMS</a>
		<a href="#" onClick="process_form('email')" id="mymenuitem"><img src="../img/email.png"><br>Email</a>
	</div>
	<div align="right">
			<select name="year" id="year" onChange="clearwin();document.myform.submit();">
				<?php
					echo "<option value=$year>SESI $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">SESI $s</option>";
					}
					mysql_free_result($res);					  
				?>
          </select>
		  
			  <select name="sid" id="sid" onChange="clearwin();document.myform.clscode[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
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
				mysql_free_result($res);
			}							  
			
?>
              </select>
	<select name="clslevel" onChange="clearwin();document.myform.clscode[0].value=''; document.myform.submit();">
        <?php    
		if($clslevel=="")
            echo "<option value=\"\">- $namatahap -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }
		if($clslevel!="")
            echo "<option value=\"\">- Semua -</option>";

?>
      </select>
			 <select name="clscode" id="clscode" onChange="clearwin();document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- Kelas -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year=$year order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- Semua Kelas -</option>";
			?>
                </select>
				<input name="search" type="text" id="search" size="32"
				onMouseDown="clearwin();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "--Nama / Kp Penjaga--"; else echo "$search";?>"> 
				
               <input type="button" name="bview" value="View" onClick="clearwin();document.myform.submit()" >
				<br>
				<input type="checkbox" value="1" name="showalamat" <?php if($showalamat) echo "checked";?> onClick="clearwin();document.myform.submit();"> Show Address
				&nbsp;&nbsp;
				<input type="checkbox" value="1" name="showanak" <?php if($showanak) echo "checked";?> onClick="clearwin();document.myform.submit();"> Show Child
				&nbsp;&nbsp;
				<input type="checkbox" value="1" name="showgaji" <?php if($showgaji) echo "checked";?> onClick="clearwin();document.myform.submit();"> Show Salary
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div> <!-- right -->
</div><!-- end mypanel-->
<div id="story">
<div id="mytitlebg">MAKLUMAT IBU/BAPA/PENJAGA</div>
<table width="100%" style="font-family:Tahoma " cellspacing="0">
		<tr>
				<td id="mytabletitle" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'checker')"></td>
				<td id="mytabletitle" width="2%" align="center">BIL</td>
				<td id="mytabletitle" width="2%" align="center">AKAUN</td>
				<td id="mytabletitle" width="13%">NAMA BAPA</td>
				<td id="mytabletitle" width="5%">NO KP</td>
				<td id="mytabletitle" width="7%">PEKERJAAN</td>
				<td id="mytabletitle" width="5%">HP BAPA</td>
				<td id="mytabletitle" width="13%">NAMA IBU</td>
				<td id="mytabletitle" width="5%">NO KP</td>
				<td id="mytabletitle" width="7%">PEKERJAAN</td>
				<td id="mytabletitle" width="5%">HP IBU</td>
			<?php if($showalamat){?>
				<td id="mytabletitle" width="15%">ALAMAT</td>
				<td id="mytabletitle" width="5%">TEL. RUMAH</td>
			<?php } if($showanak){?>
				<td id="mytabletitle" width="20%">NAMA ANAK</td>
			<?php } if($showgaji){?>
				<td id="mytabletitle" width="5%" align="center">GAJI BAPA</td>
				<td id="mytabletitle" width="5%" align="center">GAJI IBU</td>
				<td id="mytabletitle" width="5%" align="center">JUMLAH (RM)</td>
			<?php } ?>
		</tr>
 <?php
if(($clscode=="")&&($clslevel==""))
		$sql="select * from parent where (sid=0 or sid=$sid) and status=0 $sqlsearch";
		//$sql="select distinct(p1ic) from stu where stu.sch_id=$sid and status=6 $sqlsearch";
else
		$sql="select distinct(acc) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and stu.status=6 $sqlclscode $sqlclslevel $sqlsearch and year='$year'";
		//$sql="select distinct(p1ic) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and stu.status=6 $sqlclscode $sqlclslevel $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisfakir $sqlishostel $sqlsearch and year='$year'";

$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row2=mysql_fetch_assoc($res2)){
		$acc=$row2['acc'];
		$sql="select * from parent where acc='$acc'";
		$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$rowx=mysql_fetch_assoc($resx);
		$id=$rowx['id'];
		$p1ic=$rowx['p1ic'];
		$p1name=ucwords(strtolower(stripslashes($rowx['p1name'])));
		$p1hp=$rowx['p1hp'];
		$p1job=ucwords(strtolower(stripslashes($rowx['p1job'])));
		$p1com=ucwords(strtolower(stripslashes($rowx['p1com'])));
		$p1sal=$rowx['p1sal'];
		$p1addr=ucwords(strtolower(stripslashes($rowx['p1addr'])));
		$p1bandar=ucwords(strtolower(stripslashes($rowx['p1bandar'])));
		$p1poskod=ucwords(strtolower(stripslashes($rowx['p1poskod'])));
		$p1state=ucwords(strtolower(stripslashes($rowx['p1state'])));
		
		$p2name=ucwords(strtolower(stripslashes($rowx['p2name'])));
		$p2ic=$rowx['p2ic'];
		$p2hp=$rowx['p2hp'];
		$p2job=ucwords(strtolower(stripslashes($rowx['p2job'])));
		$p2com=ucwords(strtolower(stripslashes($rowx['p2com'])));
		$p2sal=$rowx['p2sal'];
		$addr=ucwords(strtolower(stripslashes($rowx['addr'])));
		$tel=$rowx['tel'];
		$hh=0;$ff=0;$names="";
		
		//$sql="select * from stu where p1ic='$p1ic' and status=6 and sch_id=$sid";
		$sql="select * from stu where acc='$acc' and status=6 and sch_id=$sid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$name=ucwords(strtolower(stripslashes($row['name'])));
				$ff++;
				if($ff==1)
					$names="$ff.". $name;
				else
					$names=$names."<br>$ff.".$name;
		 }
		//if($ff==0)
			//continue;
		if(($q++%2)==0)
			$bg="$bglyellow";
		else
			$bg="";
 ?>

		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			    <td id=myborder class="printhidden" ><input id="checker" type=checkbox name=checker[] value="<?php echo "$uid";?>" onClick="checkbox_checkall(0,'checker')" ></td>
				<td id=myborder align="center"><?php echo "$q";?></td>
				<td id=myborder align="center"><?php echo "$acc";?></td>
				<td id=myborder><a href="../eparent/parentreg.php?id=<?php echo $id;?>" target="_blank"><?php echo "$p1name";?></a></td>
				<td id=myborder><?php echo "$p1ic";?></td>
				<td id=myborder><?php echo "$p1job";?></td>
				<td id=myborder><?php echo "$p1hp";?></td>
				<td id=myborder><?php echo "$p2name";?></td>
				<td id=myborder><?php echo "$p2ic";?></td>
				<td id=myborder><?php echo "$p2job";?></td>
				<td id=myborder><?php echo "$p2hp";?></td>
			<?php if($showalamat){?>
				<td id=myborder><?php echo "$p1addr $p1poskod $p1bandar $p1state";?></td>
				<td id=myborder><?php echo "$tel";?></td>
			<?php } if($showanak){?>
				<td id=myborder><?php echo "$names";?></td>
			<?php } if($showgaji){?>
					<td id=myborder align="right"><?php echo number_format($p1sal,2,'.',',');?></td>
					<td id=myborder align="right"><?php echo number_format($p2sal,2,'.',',');?></td>
					<td id=myborder align="right"><?php echo number_format($p1sal+$p2sal,2,'.',',');?></td>
			<?php } ?>
		</tr>
		
<?php } ?>
</table>


</div></div>

</form> <!-- end myform -->



</body>
</html>
