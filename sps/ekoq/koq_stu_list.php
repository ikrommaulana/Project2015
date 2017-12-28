<?php
//160910 5.0.0 - update gui.. data kementrian
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];


		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$kelab=$_REQUEST['kelab'];
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
		}
		
		$tahap=$_POST['tahap'];
		if($tahap!=""){
			$sqltahap="and level='$tahap'";
			$sqlclslevel="and ses_stu.cls_level='$tahap'";
		}

		$year=$_POST['year'];
		if($year==""){
			$year=date('Y');
			if(($issemester)&&(date('n')<$startsemester))
				$year=$year-1;
		
		}

		$search=$_REQUEST['search'];
		if(strcasecmp($search,"-Matric-IC-Name-")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (stu.uid='$search' or stu.ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		
		$namatahap="Level";
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripcslashes($row['name']);
			$ssname=$row['sname'];
			$namatahap=$row['clevel'];				  
		}
		
		$stustatus=$_REQUEST['stustatus'];
		if($stustatus==""){
			$stustatus = 6;
		}
		if($stustatus!="%"){
			$sqlstustatus="and status=$stustatus";
			$sql="select * from type where grp='stusta' and val='$stustatus'";
			$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$studentstatus=$row['prm'];
		}
		else
			$studentstatus="All Student";
			
		$op=$_REQUEST['op'];
		if($op=="save"){
			$aktiviti=$_REQUEST['aktiviti'];
			if (count($aktiviti)>0) {
				for ($i=0; $i<count($aktiviti); $i++) {
					$data=$aktiviti[$i];
					
					if($data=="")
						continue;
					$xuid=strtok($data,"|");
					if($xuid=="")
						continue;
					$koq=strtok("|");
					if($koq=="")
						continue;
					$sql="select * from koq where grp='koq' and prm='$koq' and (sid=0 or sid=$sid)"; 	
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$kname=$row['prm'];
					$kcode=$row['code'];
					$ktype=$row['val'];
					$etc=$row['etc'];//jika ktype==0... so etc=point
					if($ktype==10)
						$point=$etc;
					else
						$point=0;

					$sql="insert into koq_stu(sid,uid,year,dts,koq_name,koq_type,koq_code,total_val,adm,dt)value($sid,'$xuid','$year',now(),'$kname',$ktype,'$kcode','$point','$username',now())";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
				}
			}
		}
		
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==0)
		$MAXLINE=30;
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
<script language="JavaScript">
function process_form(operation){		
		ret = confirm("Save the configuration??");
		if (ret == true){
			document.myform.op.value='save';
			document.myform.submit();
		}
}
</script>
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
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div>
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div align="right" class="printhidden">
      <select name="year" id="year" onchange="document.myform.submit();">
<?php
		$sql="select * from type where grp='session' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
			$v=$row['val'];
                        
			if($s==$year){$selected="selected";}else{$selected="";}
			echo "<option value=\"$s\" $selected>$lg_session $s</option>";
		
            }
            mysql_free_result($res);					  
?>
		</select>		

	<select name="sid" id="sid" onchange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
	
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$s=stripcslashes($s);
							$t=$row['id'];
							if($t==$sid){$selected="selected";}else{$selected="";}
							echo "<option value=$t $selected>$s</option>";
				}
				mysql_free_result($res);
											  
			
?>
        </select>  
<select name="tahap" id="tahap" onChange="document.myform.clscode[0].value='';document.myform.kelab[0].value='';document.myform.submit();">
               <?php    
		if($tahap=="")
            	echo "<option value=\"\">- $lg_all $lg_level -</option>";
		else
			echo "<option value=$tahap>$namatahap $tahap</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$tahap' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		if($tahap!="")
        	echo "<option value=\"\">- $lg_all $lg_level -</option>";
?>
			 </select>
			 
			 <select name="clscode" id="clscode" onChange="document.myform.kelab[0].value='';document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' $sqltahap order by level";
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
			 
      			<select name="kelab" id="kelab" onChange="document.myform.submit();">
				<?php
					if($kelab=="")
						echo "<option value=\"\">- Semua Aktifitas -</option>";
					else
						echo "<option value=\"$kelab\">$kelab</option>";
					$sql="select * from koq where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by val,des,prm"; 	
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$e=$row['des'];
						echo "<option value=\"$s\">$e-$s</option>";
					}
					if($kelab!="")
            			echo "<option value=\"\">- $lg_all $lg_activity -</option>";				  
				?>
          	</select>
			<input name="search" type="text" size="30" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "-Matric-IC-Name-"; else echo "$search";?>"> 
			<input type="submit" name="Submit" value="View">
</div>
<div id="story">


<div id="mytitlebg"> 
	<?php echo strtoupper("$lg_cocurriculum");?> - <?php echo strtoupper($sname);?> - <?php echo strtoupper("$studentstatus");?> <?php echo $f;?>
</div>
<table width="100%" cellspacing="0">
  <tr>
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_no");?></td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_matric");?></a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper("$lg_mf");?></a></td>
              <td id="mytabletitle" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_name");?></a></td>
			  <td id="mytabletitle" width="15%"><?php echo strtoupper("$lg_class");?> <?php echo $year;?></td>
			  <td id="mytabletitle" width="40%"><?php echo strtoupper("aktifitas");?></td>
			  <td id="mytabletitle" width="10%"><input type="button" name="Button" value="<?php echo strtoupper("$lg_update");?>" onClick="document.myform.curr.value='<?php echo $curr;?>';process_form('add')" style="width:100% " <?php if($kelab!="") echo "disabled";?> ></td> 
            </tr>
	<?php
	if($kelab!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab' and koq_stu.sta=0 $sqlstustatus $sqlsearch";
		else
			$sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab' and koq_stu.sta=0 $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year'";
	}
	else if(($clscode=="")&&($tahap==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year'";	
    $res=mysql_query($sql,$link)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($kelab!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select stu.* from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab'  and koq_stu.sta=0 $sqlstustatus $sqlsearch $sqlsort limit $curr,$MAXLINE";
		else
			$sql="select stu.* from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid  and koq_stu.sta=0 where stu.sch_id=$sid and koq_stu.koq_name='$kelab' $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year' $sqlsort limit $curr,$MAXLINE";
	}
	else if(($clscode=="")&&($tahap==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year' $sqlsort limit $curr,$MAXLINE";

	$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		$status=$row['status'];
		
		
		$cname=$lg_none;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
		}
		
		$xkelab="";
		if($kelab=="")
			$sql="select * from koq_stu where uid='$uid' and sta=0 and sid=$sid and year='$year'";
		else
			$sql="select * from koq_stu where uid='$uid' and sta=0 and koq_name='$kelab' and sid=$sid and year='$year'";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$b=$row2['koq_name'];
			$v=$row2['koq_type'];
			if($xkelab!="")
				$xkelab=$xkelab.",$b";
			else
				$xkelab=$b;

		}
			
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id=myborder align=center><?php echo "$q";?></td>
	   	<td id=myborder align=center><?php echo "$uid";?></td>
		<td id=myborder align=center><?php echo $lg_sexmf[$sex];?></td>
		<td id=myborder><a href="../ekoq/koq_stu_reg.php?<?php echo "uid=$uid&sid=$sid&year=$year";?>" target="_blank" title="EXTRA KURIKULER <?php echo addslashes($name);?>" onClick="return GB_showPage('Extra Kurikuler: <?php echo addslashes($name);?>',this.href)"><?php echo "$name";?></a></td>
		<td id=myborder><?php echo "$cname";?></td>
		<td id=myborder><?php echo "$xkelab";?></td>
		<td id=myborder>
		<select name="aktiviti[]" >
			<option value="">- <?php echo $lg_select;?> -</option>
			<?php
			$sql="select * from koq where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by val,des,prm"; 	
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            while($row2=mysql_fetch_assoc($res2)){
            	$s=$row2['prm'];
				$v=$row2['code'];
				$d=$row2['des'];
                echo "<option value=\"$uid|$s\">$d - $s</option>";
            }
			?>
		</select>
		</td>
		</tr>
<?php }  ?>
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