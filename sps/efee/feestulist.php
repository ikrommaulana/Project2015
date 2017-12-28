<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
		}


		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$sshort=$row['sname'];
			$simg=$row['img'];
            $issemester=$row['issemester'];
			$startsemester=$row['startsemester'];					  
		}
		
		$year=$_POST['year'];
		if($year==""){
				$year=date('Y');
				if(($issemester)&&(date('n')<$startsemester))
					$year=$year-1;
				$xx=$year+1;
				$sesyear="$year/$xx";	
			
		}else{
				$sesyear="$year";
		}
		$year=$sesyear;

		$searchall=$_REQUEST['searchall'];
		if($searchall==""){
			$stustatus=6;
			$sqlstustatus="and status=$stustatus";
		}
					
	
		$isblock=$_REQUEST['isblock'];
		if($isblock!=""){
			$sqlisblock="and isblock=1";
			$x5="- Blocked";
		}
		
		$view=$_POST['view'];
		
		$checker=$_POST['checker'];
		$op=$_POST['op'];
		if($op=="block"){
			if (count($checker)>0) {
				for ($i=0; $i<count($checker); $i++) {
					$sql="select isblock from stu where uid='$checker[$i]'";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$isb=$row['isblock'];
					if($isb){
						$sql="update stu set isblock=0 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}else{
						$sql="update stu set isblock=1 where uid='$checker[$i]'";
						mysql_query($sql)or die("query failed:".mysql_error());
					}
				}
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
		$sqlsort="order by $sort $order, name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
function process_form(action){
	var ret="";
	var cflag=false;
	
	if(action=='block'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='checker')&&(e.name!='checkall')){
                        if(e.checked==true)
                        	cflag=true;
                        else
                            allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked student to LOCK/UNLOCK');
			return;
		}
		ret = confirm("Are you sure want to LOCK/UNLOCK??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../efee/feestulist">
	<input type="hidden" name="op">
	
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('block')" id="mymenuitem"><img src="../img/lock.png"><br>Blocking</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
	<a href="#" title="<?php echo $vdate;?>"></a><br><br>

	<select name="year" id="year" onChange="document.myform.submit();">
				<?php
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						echo "<option value=\"$s\">$lg_session $s</option>";
					}
					mysql_free_result($res);					  
				?>
          </select>
		  
			  <select name="sid" id="sid" onChange="document.myform.clscode[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sshort</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
              </select>
			 <select name="clscode" id="clscode" onChange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all $lg_class -</option>";
			?>
                </select>
				<input name="search" type="text" id="search" size="30" onMouseDown="document.myform.search.value='';document.myform.search.focus();" 
				value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="VIEW" style="font-size:11px;">
				<br>
				<input type="checkbox" name="isblock" value="1" <?php if($isblock) echo "checked";?>><?php echo "Tampilkan Siswa Yang Di Blok Saja";?> &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="searchall" value="1" <?php if($searchall) echo "checked";?>><?php echo  "Tampilkan Semua / Telah Berhenti";?></td>
	</div>
<div id="story">

<div id="mytitlebg"><?php echo strtoupper($lg_fee_payment);?> : <?php echo strtoupper($sname);?> - <?php echo strtoupper("$year");?> </div>


<table width="100%" cellspacing="0" cellpadding="0">
  <tr >
  			  <td id="mytabletitle" width="1%" class="printhidden"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td id="mytabletitle" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td id="mytabletitle" width="15%"><?php echo strtoupper($lg_class);?> <?php echo $year;?></td>
			  <td id="mytabletitle" width="7%" align="center"><?php echo strtoupper($lg_register);?></td>
			  <td id="mytabletitle" width="7%" align="center"><?php echo strtoupper($lg_end);?></td>
			  <td id="mytabletitle" width="7%" align="center"><?php echo strtoupper($lg_ic_parent);?></td>
			  <td id="mytabletitle" width="9%" align="center"><?php echo strtoupper($lg_advance);?></td> 
			  <td id="mytabletitle" width="9%" align="center"><?php echo strtoupper($lg_status);?></td> 
            </tr>
<?php	
	if($clscode=="")
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlisblock $sqlsearch";
	else if($clscode=="0")
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlclscode $sqlstustatus $sqlisblock $sqlsearch and year!='$year'";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlclscode $sqlstustatus $sqlisblock $sqlsearch and year='$year'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($clsname=="")
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisblock $sqlsearch $sqlsort $sqlmaxline";
	else if($clscode=="0")
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlisblock $sqlclscode $sqlsearch and year!='$year' $sqlsort $sqlmaxline";		
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlisblock  $sqlclscode $sqlsearch and year='$year' $sqlsort $sqlmaxline";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;

  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper($row['name']);
		$name=stripslashes($name);
		$sex=$row['sex'];
		$sexname=$lg_sexmf[$sex];
		$bday=$row['bday'];
		$rdate=$row['rdate'];
		$edate=$row['edate'];
		$p1ic=$row['p1ic'];
		$p1hp=$row['p1hp'];
		$p1tel=$row['p1tel'];
		$status=$row['status'];
		$isblock=$row['isblock'];
		
		$cname="Tiada";
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$cname=$row2['cls_name'];
			
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='advance'";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='TEBUS'";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$stutebus=$row2[0];
		else
			$stutebus=0;
		$advance=$advance+$stutebus; //statebus negative so kena +
			
		$q++;
		$bg=$bglyellow;
			
		if($isblock)
			$bg=$bglred;
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			<td id="myborder" class="printhidden" ><input id="checker" type=checkbox name=checker[] value="<?php echo "$uid";?>" onClick="check(0)" ></td>
			<td id="myborder" align=center><?php echo "$q";?></td>
			<td id="myborder" align=center><?php echo "$uid";?></td>
			<td id="myborder" align="center"><?php echo "$sexname";?></td>
			<td id="myborder"><a href="#" onClick="window.open('<?php echo "../efee/$FN_FEEPAY.php?uid=$uid&year=$year&sid=$sid";?>','_blank');"><?php echo "$name";?></a></td>
			<td id="myborder"><?php echo "$cname";?></td>
			<td id="myborder" align=center><?php echo "$rdate";?></td>
			<td id="myborder" align=center><?php echo "$edate";?></td>
			<td id="myborder" align=center><?php echo "$p1ic";?></td>
			<td id="myborder" align="center"><?php printf("%.02f",$advance);?></td>
			<td id="myborder" align="center"><?php echo "$sta";?></td>
		</tr>
<?php } ?>
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