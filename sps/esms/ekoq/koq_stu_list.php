<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

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
		if($year=="")
			$year=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (stu.uid='$search' or stu.ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		
		$namatahap="Tahap";
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
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
			$studentstatus="Semua Pelajar";
			
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
					$sql="select * from type where grp='koq' and prm='$koq' and (sid=0 or sid=$sid)"; 	
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$kname=$row['prm'];
					$kcode=$row['code'];
					$ktype=$row['val'];
			
					$sql="insert into koq_stu(sid,uid,dts,koq_name,koq_type,koq_code,pos,sta,adm,dt)value($sid,'$xuid',now(),'$kname',$ktype,'$kcode','AHLI',0,'$username',now())";
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
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../ekoq/koq_stu_list">
	<input type="hidden" name="op">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div align="right">
			  <select name="sid" id="sid" onchange="document.myform.kelab[0].value='';document.myform.clscode[0].value='';document.myform.tahap[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih $lg_sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
              </select>
			  
			   <select name="tahap" id="tahap" onchange="document.myform.clscode[0].value='';document.myform.kelab[0].value='';document.myform.submit();">
               <?php    
		if($tahap=="")
            	echo "<option value=\"\">- Semua Tahap -</option>";
		else
			echo "<option value=$tahap>$namatahap $tahap</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$tahap' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		if($tahap!="")
        	echo "<option value=\"\">- Semua Tahap -</option>";
?>
			 </select>
			 
			 <select name="clscode" id="clscode" onchange="document.myform.kelab[0].value='';document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- Semua Kelas -</option>";
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
            			echo "<option value=\"\">- Semua Kelas -</option>";

			?>
                </select>
			 
      			<select name="kelab" id="kelab" onchange="document.myform.submit();">
				<?php
					if($kelab=="")
						echo "<option value=\"\">- Semua Aktiviti -</option>";
					else
						echo "<option value=\"$kelab\">$kelab</option>";
					$sql="select * from type where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by prm"; 	
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						echo "<option value=\"$s\">$s</option>";
					}
					if($kelab!="")
            			echo "<option value=\"\">- Semua Aktiviti -</option>";				  
				?>
          </select>
				
				<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="View"  >
				
</div>
</div><!-- end mypanel-->
<div id="story">


<div id="mytitle"> KOKURIKULUM - <?php echo strtoupper($sname);?> - Senarai <?php echo "$studentstatus $year";?> <?php echo $f;?></div>
<table width="100%" id="mytable"  >
  <tr>
			  <td id="mytabletitle" width="3%" align="center">No</td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort">Pelajar</a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort">L/P</a></td>
              <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">Nama</a></td>
			  <td id="mytabletitle" width="10%" align="center">Kelas <?php echo $year;?></td>
			  <td id="mytabletitle" width="40%">Aktiviti</td>
			 <?php 
			 if(is_verify('ADMIN|AKADEMIK|KEWANGAN')){
			 	if(!$isprint){
			?>
			  <td id="mytabletitle" width="10%"><input type="button" name="Button" value="Kemaskini" onClick="document.myform.curr.value='<?php echo $curr;?>';process_form('add')" style="width:100% " <?php if($kelab!="") echo "disabled";?> ></td> 
			  <?php } } ?>
            </tr>
	<?php
	if($kelab!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select count(*) from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab' and koq_stu.sta=0 $sqlstustatus $sqlsearch";
		else
			$sql="select count(*) from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab'  and koq_stu.sta=0 $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and year='$year'";
	}
	else if(($clscode=="")&&($tahap==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and year='$year'";	
    $res=mysql_query($sql,$link)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($kelab!=""){
		if(($clscode=="")&&($tahap==""))
			$sql="select stu.* from stu INNER JOIN koq_stu ON stu.uid=koq_stu.uid where stu.sch_id=$sid and koq_stu.koq_name='$kelab'  and koq_stu.sta=0 $sqlstustatus $sqlsearch order by id limit $curr,$MAXLINE";
		else
			$sql="select stu.* from stu INNER JOIN (ses_stu,koq_stu) ON stu.uid=ses_stu.stu_uid and stu.uid=koq_stu.uid  and koq_stu.sta=0 where stu.sch_id=$sid and koq_stu.koq_name='$kelab' $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and year='$year' order by id limit $curr,$MAXLINE";
	}
	else if(($clscode=="")&&($tahap==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and year='$year' $sqlsort limit $curr,$MAXLINE";

	$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper($row['name']);
		$sex=$row['sex'];
		$bday=$row['bday'];
		$p1ic=$row['p1ic'];
		$p1hp=$row['p1hp'];
		$p1tel=$row['p1tel'];
		$status=$row['status'];
		
		
		$cname="Tiada";
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
		}
		
		$xkelab="";
		if($kelab=="")
			$sql="select * from koq_stu where uid='$uid' and sta=0 and sid=$sid";
		else
			$sql="select * from koq_stu where uid='$uid' and sta=0 and koq_name='$kelab' and sid=$sid";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$b=$row2['koq_name'];
			if($xkelab!="")
				$xkelab=$xkelab.",$b";
			else
				$xkelab=$b;
		}
			
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			
		//check guru kelas for this subject
		$sql="select count(*) from ses_cls where sch_id=$sid and cls_code='$clscode' and usr_uid='$username' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$gurukelas=$row2[0];
			
		if(($q++%2)==0)
			echo "<tr bgcolor=#fafafa>";
		else
			echo "<tr bgcolor=#FFFFFF>";
		
		echo "<td align=center>$q</td>";
	   	echo "<td align=center>$uid</td>";
		echo "<td align=\"center\">";
		if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";
		echo "</td>";
		if(is_verify('ADMIN|AKADEMIK|KEWANGAN'))
 			echo "<td><a href=\"#\" onClick=\"newwindow('../ekoq/koq_stu_reg.php?uid=$uid&sid=$sid',0)\">$name</a></td>";
		else
			echo "<td>$name</td>";
		echo "<td align=center>$cname</td>";
		echo "<td >$xkelab</td>";
		if(is_verify('ADMIN|AKADEMIK|KEWANGAN')){
			if(!$isprint){
		echo "<td >";
		echo "<select name=\"aktiviti[]\" ><option value=\"\">- Pilih -</option>";
			$sql="select * from type where grp='koq' and prm!='$kelab' and (sid=0 or sid=$sid) order by val,idx,prm"; 	
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            while($row2=mysql_fetch_assoc($res2)){
            	$s=$row2['prm'];
				$v=$row2['code'];
                echo "<option value=\"$uid|$s\">$v - $s</option>";
            }
		echo "</select>";
		echo "</td>";
			}
		}
		echo "</tr>";
  }
  mysql_free_result($res);
  ?>
     </table>          
	 
	<?php include("inc/paging.php");?>
</div></div>

	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form> <!-- end myform -->


</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->