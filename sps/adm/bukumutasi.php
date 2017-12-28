<?php
$vdate="110412";
$vmod="v5.3.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK');
$adm = $_SESSION['username'];
$grp=$_REQUEST['grp'];	
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];

$sid=$_REQUEST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];

if($sid=="0")
		$sid=1;
if($sid!=""){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$sid=$row['id'];
		$sname=stripslashes($row['name']);
		$ssname=stripslashes($row['sname']);
		$simg=$row['img'];
		$namatahap=$row['clevel'];	
		$issemester=$row['issemester'];
		$startsemester=$row['startsemester'];
		$sqlsid="and sid=$sid";	  
}
//check level 1,7,A siswa masuk
$sqlcheck="select * from type where grp='classlevel' and sid='$sid' order by prm asc limit 1";
$resc=mysql_query($sqlcheck) or die(mysql_error());
$rowc=mysql_fetch_assoc($resc);
      $c=$rowc['prm'];
      $sqlcheck="and clslevel!='$c'";


		
$year=$_POST['year'];
if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
		$xx=$year+1;
		$sesyear="$year/$xx";
		$year=$sesyear;
	
}else{
		$sesyear="$year";
}



		
$month=$_POST['month'];
if($month=="")
	$month=date('m');		

	$del=$_POST['del'];
    $prm=addslashes($_POST['prm']);
	$val=$_POST['val'];
	if($val=="")
		$val=0;
	$grp=$_POST['grp'];
	$des=$_POST['des'];
	$etc=$_POST['etc'];
	$idx=$_POST['idx'];
	$code=$_POST['code'];
	$idx=$_POST['idx'];
	if($idx=="")
		$idx=0;
	$lvl=$_POST['lvl'];
	if($lvl=="")
		$lvl=0;

	$listname=$_POST['listname'];
    if($listname=="")
    	$listname="A";

	$sqllistname=" and name like '$listname%'";
	
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
		$sqlsort="order by name $order";
	else
		$sqlsort="order by $sort $order";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;

	ret = confirm("Are you sure want to SAVE??");
	if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
	}
	return;
}

</script>
<title>SPS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body >
<form name=myform method="post" action="">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="grp" type="hidden" value="<?php echo $grp;?>">
	<input name="op" type="hidden" value="">
    <input name="listname" type="hidden" value="<?php echo $listname;?>">
<div id="content">
<div id="mypanel">
        <div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>        
        <a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        </div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
        <a href="#" title="<?php echo $vdate;?>"></a><br><br>
        
        <select name="sid" onchange="document.myform.submit();">
        <?php	
                    if($sid=="")
                        echo "<option value=\"\">- $lg_all -</option>";
                    else
                        echo "<option value=$sid>$sname</option>";
                    if($_SESSION['sid']==0){
                        $sql="select * from sch where id!='$sid' order by name";
                        $res=mysql_query($sql)or die("query failed:".mysql_error());
                        while($row=mysql_fetch_assoc($res)){
                                    $s=stripslashes($row['name']);
                                    $t=$row['id'];
                                    echo "<option value=$t>$s</option>";
                        }
                        echo "<option value=\"\">- $lg_all -</option>";
                    }							  
        ?>
        </select>
        <select name="year" id="year" onchange="document.myform.submit();">
        <?php
                    echo "<option value='$sesyear'>$lg_year $sesyear</option>";
                    $sql="select * from type where grp='session' and prm!='$sesyear' order by val desc";
                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                    while($row=mysql_fetch_assoc($res)){
                                $s=$row['prm'];
                                $v=$row['val'];
                                echo "<option value=\"$s\">$lg_year $s</option>";
                    }		  
        ?>
        </select>
        <select name="month" onChange="document.myform.submit();">
                <?php
							$sql="select * from month where no='$month'";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							$row=mysql_fetch_assoc($res);
							$monthname=$row['name']; 
							$v=$row['no'];
							echo "<option value=$v>$monthname</option>";
						
							$sql="select * from month where no!='$month'";
							$res=mysql_query($sql)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
								$p=$row['name'];
								$v=$row['no'];
								echo "<option value=$v>$p</option>";
							}
				?>
				</select>
</div> <!-- end mypanel-->
<div id="story">

<div id="mytitle2" style="font-size:18px">
<table width="100%">
	<tr><td width="33%">BUKU INDUK MUTASI SISWA</td>
    	<td width="33%">Bulan : <?php echo $monthname;?></td>
        <td width="33%">Tahun Pelajaran : <?php echo $sesyear;?></td>
    </tr>
</table>    
</div>
<div id="mytitle2" style="font-size:14px">SISWA YANG KELUAR</div>
	<table width="100%" cellspacing="0" cellpadding="3" style="font-size:11px">
          <tr>
                <td id="mytabletitle" width="2%" align="center">No. Urut</td>
                <td id="mytabletitle" width="20%">NAMA SISWA</td>
                <td id="mytabletitle" width="5%" align="center">L/P</td>
                <td id="mytabletitle" width="10%" align="center">Nomor Induk</td>
                <td id="mytabletitle" width="10%" align="center">Tangggal Keluar</td>
                <td id="mytabletitle" width="40%">Pindah Ke / Alasan Keluar</td>
          </tr>
<?php
/***************************************siswa keluar************************************************/
	$sql="select stu.*,ses_stu.stu_uid from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id='$sid'  and stu.status!='3' and stu.status!='6' and stu.status!='7' and stu.status!='0' and month(edate)='$month' and ses_stu.year='$year'";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){   
			$name=ucwords(strtolower(stripslashes($row['name'])));
			
			$sex=$lg_sexmf[$row['sex']];
			//$sex=$row['sex'];
			$uid=$row['uid'];
			if($uid=='')
			       $uid='-';
			$xsid=$row['sch_id']; 
			$ic=$row['ic'];
			$enddate=$row['edate'];
			$endreason=$row['reasonleaving']; 
			$endnewschool=$row['endnewschool']; 
		
			if(($q++%2)==0)
				$bg="#FFFFFF";
			else
				$bg="#FFFFFF";
?>       
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                <td id="myborder" align="center"><?php echo $q;?></td>
                <td id="myborder"><a href="../edaftar/bukuinduk.php?<?php echo "sid=$xsid&ic=$ic";?>" target="_blank"> <?php echo $name;?></a></td>
                <td id="myborder" align="center"><?php echo $sex;?></td>
                <td id="myborder" align="center"><?php echo $uid;?></td>                
                <td id="myborder" align="center"><?php echo "$enddate";?></td>
                <td id="myborder"><?php echo "$endnewschool  $endreason";?></td>
          </tr>
<?php } ?> 
	</table>
<div id="mytitle2" style="font-size:14px">SISWA YANG MASUK</div>
	<table width="100%" cellspacing="0" cellpadding="3" style="font-size:11px">
          <tr>
                <td id="mytabletitle" width="2%" align="center">No. Urut</td>
                <td id="mytabletitle" width="20%">NAMA SISWA</td>
                <td id="mytabletitle" width="5%" align="center">L/P</td>
                <td id="mytabletitle" width="10%" align="center">Nomor Induk</td>
                <td id="mytabletitle" width="10%" align="center">Tangggal Masuk</td>
                <td id="mytabletitle" width="40%">Pindah Dari / Alasan Masuk</td>
          </tr>
<?php
/***************************************siswa masuk************************************************/
if($month=='7'){
		
	$sql="select * from stu where sch_id='$sid' and month(rdate)='$month' and intake='$year' $sqlcheck  $sqlsort";
	
}else{
	$sql="select * from stu where sch_id='$sid' and month(rdate)='$month' and intake='$year' $sqlsort";
}
//echo $sql;
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){   
			$name=ucwords(strtolower(stripslashes($row['name'])));
			$sex=$lg_sexmf[$row['sex']];
			$uid=$row['uid']; 
			$xsid=$row['sch_id']; 
			$ic=$row['ic'];
			$rdate=$row['rdate'];
			$endreason=$row['reasonleaving']; 
			$endnewschool=$row['endnewschool'];
			
		//check reason masuk
		$sqlc="select reasonleaving,pschool from stureg where ic='$ic' or uid='$uid'";
		$resc=mysql_query($sqlc)or die(mysql_error());
		$rowc=mysql_fetch_assoc($resc);
		         $reason=stripcslashes($rowc['reasonleaving']);
			 $pcshool=stripcslashes($rowc['pschool']);
		
			if(($q++%2)==0)
				$bg="#FFFFFF";
			else
				$bg="#FFFFFF";
?>       
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                <td id="myborder" align="center"><?php echo $q;?></td>
                <td id="myborder"><a href="../edaftar/bukuinduk.php?<?php echo "sid=$xsid&ic=$ic";?>" target="_blank"> <?php echo $name;?></a></td>
                <td id="myborder" align="center"><?php echo $sex;?></td>
                <td id="myborder" align="center"><?php echo $uid;?></td>                
                <td id="myborder" align="center"><?php echo "$rdate";?></td>
                <td id="myborder"><?php echo "$pcshool   $reason";?></td>
          </tr>
<?php } ?> 
	</table>

</div></div>
</form>
</body>
</html>
