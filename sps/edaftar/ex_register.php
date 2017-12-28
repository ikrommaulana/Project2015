<?php
//STANDARD EDITION
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("eregister",1);

		$username = $_SESSION['username'];
		$p=$_REQUEST['p'];
		$sid=$_REQUEST['sid'];
			 		
		$adminregonly=$_POST['adminregonly'];
		if($adminregonly)
			$sqladminregonly=" and isadminreg=1";
		
		$status=$_POST['status'];
		if($status!="")
			$sqlstatus="and status=$status";
			
			
		$clssession=$_POST['clssession'];
		if($clssession!="")
			$sqlclssession="and clssession='$clssession'";
			
		$clslevel=$_POST['clslevel'];
		if($clslevel!="")
			$sqlclslevel="and cls_level=$clslevel";
		
		if($sid!=""){
			$sql="select * from type where grp='sps_external' and prm='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sid=$row['val'];
			$sname=$row['prm'];
			$dbname=$row['etc'];
		}
		if($sid==""){
			$sid="0";
			$namatahap=$lg_level;
		}
			
		$sqlsid="and sch_id=$sid";

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
function newwindowww(op) 
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
	document.myform.op.value=op;
	document.myform.target="newwindow";
	document.myform.action='letter.php';
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
	
}
function process_form(process) 
{ 
	var cflag=false;
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
		document.myform.process.value=process;
		document.myform.submit();	
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
	<input type="hidden" name="process">
	<input type="hidden" name="op">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
	<?php $sql="select * from stureg where id>0 and confirm=1 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch $sqlsort";?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clear_newwindow();document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
   	<div align="right">
    	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		
	</div>
    
</div><!-- end mypanel-->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px">

<select name="year" onChange="clear_newwindow();document.myform.submit();document.myform.clslevel.value='';">
<?php
	if($year==""){
		echo "<option value=\"\">$lg_all $lg_intake</option>";
		$year=date('Y')+1;
		echo "<option value=\"$year\">$lg_intake $year</option>";
	}
	else
		echo "<option value=\"$year\">$lg_intake $year</option>";
	$sql="select * from type where grp='session' and prm!='$year' order by val desc";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$lg_intake $s</option>";
    }
	if($year!=""){
		echo "<option value=\"\">$lg_all $lg_intake</option>";
	}  
?>
      </select>
						
	<select name="sid" id="sid" onChange="clear_newwindow();document.myform.submit();document.myform.clslevel.value='';">
<?php	
      		if($sid=="0")
            	echo "<option value=\"\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=\"$sname\">$sname</option>";
			
				$sql="select * from type where grp='sps_external' and prm!='$sname' order by idx";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['prm'];
                            $t=$row['val'];
							echo "<option value=\"$s\">$s</option>";
				}
?>
              </select>
			  <select name="status" onChange="clear_newwindow();document.myform.clslevel.value='';document.myform.submit();">
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
            	echo "<option value=\"\">- $lg_level -</option>";
			else
				echo "<option value=\"$clslevel\">$namatahap $clslevel</option>";

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
                        
			  <input type="hidden" name="letter" value="<?php echo $statusname;?>">
			  <input name="search" type="text" id="search" size="32"
				onMouseDown="clear_newwindow();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>">
				<input type="button" name="Submit" value="VIEW" onClick="clear_newwindow();document.myform.submit();" style="font-size:11px;"> 
				
				<input type="checkbox"  name="adminregonly" value="1" <?php if($adminregonly) echo "checked";?> onClick="clear_newwindow();document.myform.submit();">View Admin Register Only
		

</div>
<div id="story">

<div id="mytitlebg"><?php echo strtoupper("$lg_registration_process");?></div>
	
<table width="100%" cellspacing="0" cellpadding=0 style="font-size:10px;">
	<tr>
			  <td class="mytableheader" style="border-right:none;" width="1%" align="center"><input type=checkbox name=checkall value="0" onClick="checkbox_checkall(1,'stuid')"></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="1%"><?php echo strtoupper("$lg_no");?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="clear_newwindow();formsort('id <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_date");?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="1%"><a href="#" onClick="clear_newwindow();formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_mf");?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="18%"><a href="#" onClick="clear_newwindow();formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_student_name");?></a></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper("$lg_ic_number");?></td>
			  <?php if($SHOW_ANAK_NEGERI){?>
			  <td class="mytableheader" style="border-right:none;" align="center" width="1%"><a href="#" onClick="formsort('anaknegeri desc, upsr_result desc, id desc','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($ANAK_NEGERI);?></a></td>
			  <?php } ?>
			  <?php if($EREG_MULTI_SESSION){?>
			  <td class="mytableheader" style="border-right:none;" align="center" width="2%"><a href="#" onClick="clear_newwindow();formsort('clssession <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_session");?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="13%"><?php echo strtoupper("$lg_school");?></td>
			  <td class="mytableheader" style="border-right:none;" width="2%">TRANSPORTER</td>
			  <?php }else{ ?>
			  <td class="mytableheader" style="border-right:none;" align="center" width="2%"><a href="#" onClick="clear_newwindow();formsort('upsr_result <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $namaexam;?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="18%"><?php echo strtoupper("$lg_previous_school");?></td>
			  <?php } ?>
			  <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper("$lg_handphone");?></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="5%"><a href="#" onClick="formsort('status <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_status");?></a></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="5%"><?php echo strtoupper("$lg_intake");?></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="2%"><?php echo strtoupper("$lg_level");?></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="1%"><a href="#" title="New(1) or Existing(0) student">NEW</a></td>
			   <td class="mytableheader" style="border-right:none;" align="center" width="1%">ADM</td>
      </tr>

<?php
		$db=mysql_select_db($dbname,$link) or die("setting:".mysql_error());
		$sql="select count(*) from stureg where id>0 and confirm=1 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch";
        $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
		
		$q=$curr;
		$sql="select * from stureg where id>0 and confirm=1 $sqlyear $sqlsid $sqladminregonly $sqlstatus $sqlclssession $sqlclslevel $sqlsearch $sqlsort $sqlmaxline";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$ic=$row['ic'];
			$addr=$row['addr'];
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
			$p1bstate=$row['p1bstate'];
			$p2bstate=$row['p2bstate'];
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
            $ssname=$row2['sname'];
			
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
					$bg="$bglred"; //merah
				elseif($sta==11)
					$bg="$bglgreen"; //green
				else
					$bg="$bglyellow";
				
?>
	<tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td class="myborder" style="border-right:none; border-top:none;" align="center" class="printhidden">
        	<input type=checkbox name=stu[] id="stuid" value="<?php echo "$id";?>" onClick="checkbox_checkall(0,'stuid')"></td>
		<td align="center" class="myborder" style="border-right:none; border-top:none;"><?php echo "$q";?></td>
		<td align="center" class="myborder" style="border-right:none; border-top:none;"><?php echo "$dt";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$sex";?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
    	<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$ic";?></td>
		<?php if($SHOW_ANAK_NEGERI){?>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$anaknegeri";?></td>
		<?php } ?>
		<?php if($EREG_MULTI_SESSION){?>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $clsses;?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo $ssname;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $istransport;?></td>
		<?php } else{?>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $upsr;?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo $pschool;?></td>
		<?php } ?>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$hp";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="left" ><?php echo str_replace(" ","",$statusapply);?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $sesyear;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $tahap;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $isnew;?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $isadminreg;?></td>
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