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
		

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
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
			
					$sql="insert into koq_tea(sid,uid,dts,koq_name,koq_type,koq_code,pos,sta,adm,dt)value($sid,'$xuid',now(),'$kname',$ktype,'$kcode','PANITIA',0,'$username',now())";
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
	<input type="hidden" name="p" value="../ekoq/koq_tea_list">
	<input type="hidden" name="op">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div align="right">
			  <select name="sid" id="sid" onchange="document.myform.kelab[0].value='';document.myform.submit();">
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


<div id="mytitle"> KOKURIKULUM GURU - <?php echo strtoupper($sname);?> - <?php echo $f;?></div>
<table width="100%" id="mytable">
  <tr>
			  <td id="mytabletitle" width="3%" align="center">No</td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort">ID</a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort">L/P</a></td>
              <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">Nama Guru</a></td>
			  <td id="mytabletitle" width="40%">Aktiviti</td>
			 <?php if(!$isprint){?>
			  <td id="mytabletitle" width="10%"><input type="button" name="Button" value="Kemaskini" onClick="document.myform.curr.value='<?php echo $curr;?>';process_form('add')" style="width:100% " <?php if($kelab!="") echo "disabled";?> ></td> 
			  <?php } ?>
            </tr>
	<?php
	if($kelab!="")
		$sql="select count(*) from usr INNER JOIN koq_tea ON usr.uid=koq_tea.uid where (usr.sch_id=$sid or usr.sch_id=0) and usr.status=0 and koq_tea.koq_name='$kelab' and koq_tea.sta=0 $sqlsearch";
	else
    	$sql="select count(*) from usr where (usr.sch_id=$sid or usr.sch_id=0) and usr.status=0 $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($kelab!="")
		$sql="select usr.* from usr INNER JOIN koq_tea ON usr.uid=koq_tea.uid where (usr.sch_id=$sid or usr.sch_id=0) and usr.status=0 and koq_tea.koq_name='$kelab' and koq_tea.sta=0 $sqlsearch order by id limit $curr,$MAXLINE";
	else
		$sql="select * from usr where (usr.sch_id=$sid or usr.sch_id=0) and usr.status=0 $sqlsearch $sqlsort limit $curr,$MAXLINE";
	
	$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=$row['name'];
		$sex=$row['sex'];
		$bday=$row['bday'];
		
		$xkelab="";
		if($kelab=="")
			$sql="select * from koq_tea where uid='$uid' and sta=0 and sid=$sid";
		else
			$sql="select * from koq_tea where uid='$uid' and sta=0 and koq_name='$kelab' and sid=$sid";
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
			
			
		if(($q++%2)==0)
			echo "<tr bgcolor=#fafafa>";
		else
			echo "<tr bgcolor=#FFFFFF>";
		
		echo "<td align=center>$q</td>";
	   	echo "<td align=center>$uid</td>";
		echo "<td align=\"center\">";
		if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";
		echo "</td>";
 		echo "<td><a href=\"#\" onClick=\"newwindow('../ekoq/koq_tea_reg.php?uid=$uid&sid=$sid',0)\">$name</a></td>";
		echo "<td >$xkelab</td>";
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