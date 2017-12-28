<?php
$vmod="v5.0.0";
$vdate="05/01/11";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("eregister",1);
$username = $_SESSION['username'];
$p=$_REQUEST['p'];

		$year=$_POST['year'];
		if($year!="")
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
			
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$schlevel=$row['level'];
		
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['sname']);
			$schlevel=$row['level'];
			
			/**
			$sql="select * from type where grp='edaftarprogram' and sid='$sid'";
			$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sname=$row['prm']; //school name
			$sch_lvl=$row['code'];
			**/
			
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
function newwindowww(page) 
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
			alert('Please checked the item you wish to process');
			return;
	}

	document.myform.target="newwindow";
	document.myform.action=page;
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
	
}
function excel(page) 
{ 
	document.myform.action=page;
    document.myform.submit();
	
}
function email() 
{ 
	alert('Will be coming soon!');
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<?php $sql="select * from stureg where sch_id>0  and isdel=0 $sqlpt $sqlsid $sqlstatus $sqlsearch $sqlsort";?>
	<input type="hidden" name="sql" value="<?php echo $sql;?>">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
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
	if($year=="")
		echo "<option value=\"\">$lg_all</option>";
	else
		echo "<option value=\"$year\">$year</option>";
	$sql="select * from type where grp='session' and prm!='$year' order by val desc";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=\"$s\">$s</option>";
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
							$s=stripslashes($row['sname']);
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
			  <select name="status" id="status" onChange="clear_newwindow();document.myform.submit();">
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
            mysql_free_result($res);					  

?>
              </select>    
			  <input name="search" type="text" id="search" size="32"
				onMouseDown="clear_newwindow();document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name / $lg_ic_number -"; else echo "$search";?>">
				<input type="button" name="Submit" value="<?php echo $lg_view; ?>" onClick="clear_newwindow();document.myform.submit();" style="font-size:11px;">      
<br>
<br>

</div>
<div id="mytitlebg"><?php echo strtoupper("$lg_interview_process");?></div>
	
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
			<td id="mytabletitle" rowspan="2" width="1%" align="center" class="printhidden"><input type=checkbox name=checkall value="0" onClick="checkbox_checkout(1,'stuid')"></td>
              <td id="mytabletitle" align="center" rowspan="2" width="1%"><?php echo strtoupper("$lg_no");?></td>
			  <td id="mytabletitle" align="center" rowspan="2" width="5%"><?php echo strtoupper("$lg_year_session");?></td>
			  <td id="mytabletitle" align="center" rowspan="2" width="5%">NO REGISTER</td>
			  <td id="mytabletitle" align="center" width="1%" rowspan="2"><a href="#" onClick="clear_newwindow();formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_mf");?></a></td>
              <td id="mytabletitle" align="center" rowspan="2" width="20%"><a href="#" onClick="formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_student_name");?></a></td>
              <td id="mytabletitle" align="center" rowspan="2" width="7%" align="center"><?php echo strtoupper("$lg_ic_number");?></td>
			  <td id="mytabletitle" align="center" rowspan="2" width="7%" align="center">No. <?php echo strtoupper("$lg_handphone");?></td>
			  <td id="mytabletitle"  align="center" rowspan="2" width="18%"><?php echo strtoupper("$lg_previous_school");?></td>
			  <?php if(is_verify('ADMIN|AKADEMIK|HEP')){?>
			  <td id="mytabletitle" rowspan="2" width="1%" align="center">Isi Nilai</td>
				<?php } ?>
			  <?php $sql="select * from type where grp='examtemuduga' and code='TEMUDUGA' order by idx,id";
					$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					$numsub=mysql_num_rows($res);
				?>
              <td id="mytabletitle" colspan="<?php echo $numsub;?>" align="center"><?php echo strtoupper("$lg_interview_mark");?></td>
			  <td id="mytabletitle" rowspan="2"  align="center" width="3%">
              	<a href="#" onClick="formsort('test1 <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
				<?php echo strtoupper("$lg_total_mark");?></a></td>
              <?php if($EREG_SHOW_UPSR){?>
			  <td id="mytabletitle" rowspan="2" align="center" width="3%">
              	<a href="#" onClick="formsort('upsr_result <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort">
				<?php echo "$namaexam";?></a></td>
              <?php } ?>
              <td id="mytabletitle" rowspan="2"  width="5%" align="center">
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
	$sql="select count(*) from stureg where isdel=0 $sqlyear $sqlpt $sqlsid $sqlstatus  $sqlsex $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
         $last=$curr+$MAXLINE;
    else
    	$last=$total;
	
	$q=$curr;
	$sql="select * from stureg where isdel=0 $sqlyear $sqlpt $sqlsid $sqlstatus $sqlsex $sqlsearch $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$ic=$row['ic'];
			$name=strtoupper(stripslashes($row['name']));
			$dt=$row['cdate'];
			$dt=strtok($dt," ");
			$hp=$row['hp'];
			$sesyear=$row['sesyear'];
			$transid=$row['transid'];
			$sch=$row['sch_id'];
			$transid=$row['transid'];
			$pt=$row['pt'];
			$sta=$row['status'];
			$tsal=$row['totalsal'];
			$test1=$row['test1'];
			$ekomark=$row['ekomark'];
			$upsr=$row['upsr_result'];
			$anaknegeri=$row['anaknegeri'];
			$sex=$lg_sexmf[$row['sex']];
			$pschool=ucwords(strtolower(stripslashes($row['pschool'])));
			$sql2="select * from type where grp='statusmohon' and val=$sta";
			$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        	$row2=mysql_fetch_assoc($res2);
        	$statusapply=$row2['prm'];
			
	
					
			if(($q++%2)==0)
				$bg="$bglyellow";
			else
				$bg="$bglyellow";
			
			if($sta==3)
				$bg=""; //
			elseif($sta==4)
				$bg="$bglgreen"; //success go to seminar
?>       
    <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$id|$surat";?>" onClick="checkbox_checkout(0,'stuid')"></td>
		<td align="center" id="myborder"><?php echo "$q";?></td>
		<td id="myborder" align="center"><?php echo "$sesyear";?></td>
		<td id="myborder" align="center"><?php echo "$transid";?></td>
		<td id="myborder" align="center"><?php echo "$sex";?></td>
		<td id="myborder"><a href="../edaftar/stu_info.php?id=<?php echo $id;?>" target="_blank" onClick="return GB_showPage('Profile : <?php echo addslashes($name);?>',this.href)"><img src="../img/profile10.png" class="printhidden">&nbsp;<?php echo "$name";?></a></td>
		<td id="myborder"><?php echo "$ic";?></td>
		<td id="myborder"><?php echo "$hp";?></td>
		<td id="myborder"><?php echo $pschool;?></td>
		<?php if(is_verify('ADMIN|AKADEMIK|HEP')){?>
			<td id="myborder"><a href="../edaftar/stu_test.php?<?php echo "id=$id&exam=TEMUDUGA";?>" target="_blank" onClick="return GB_showPage('Interview : <?php echo addslashes($name);?>',this.href)"><img src="../img/edit12.png"  class="printhidden"></a></td>
<?php } ?>
		<!-- 
		<td id="myborder" align="center"><?php echo "$pt";?></td>
		 -->
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
<?php if($EREG_SHOW_UPSR){?>              
			<td id="myborder"><?php echo $upsr;?></td>  
<?php } ?>               
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