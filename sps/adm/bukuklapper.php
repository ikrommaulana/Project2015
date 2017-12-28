<?php
$vdate="110412";
$vmod="v5.3.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
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

		
$year=$_POST['year'];
if($year==""){
		 $sql="select * from type where grp='session' order by prm";
         $res=mysql_query($sql)or die("query failed:".mysql_error());
         $row=mysql_fetch_assoc($res);
         $year=$row['prm'];	
		 $sesyear=$row['prm'];	
	
}else{
		$sesyear="$year";
}
$year=$sesyear;
	  
				

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

	$sqllistname=" and stu.name like '$listname%'";
	
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
		$sqlsort="order by stu.name $order";
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
        <a href="#" title="<?php echo $vdate;?>"><br><br>
        
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
        </div>

<div id="story">

	<div id="mytitle2" style="font-size:18px">BUKU KLAPPER</div>
<table width="100%" cellspacing="0" style="font-size:16px">
          <tr>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='A';document.myform.submit();">A</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='B';document.myform.submit();">B</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='C';document.myform.submit();">C</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='D';document.myform.submit();">D</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='E';document.myform.submit();">E</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='F';document.myform.submit();">F</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='G';document.myform.submit();">G</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='H';document.myform.submit();">H</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='I';document.myform.submit();">I</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='J';document.myform.submit();">J</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='K';document.myform.submit();">K</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='L';document.myform.submit();">L</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='M';document.myform.submit();">M</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='N';document.myform.submit();">N</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='O';document.myform.submit();">O</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='P';document.myform.submit();">P</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='Q';document.myform.submit();">Q</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='R';document.myform.submit();">R</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='S';document.myform.submit();">S</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='T';document.myform.submit();">T</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='U';document.myform.submit();">U</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='V';document.myform.submit();">V</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='W';document.myform.submit();">W</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='X';document.myform.submit();">X</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='Y';document.myform.submit();">Y</a></td>
                <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.listname.value='Z';document.myform.submit();">Z</a></td>
                
          </tr>
</table>    
	<table width="100%" cellspacing="0" cellpadding="3" style="font-size:11px">
          <tr>
                <td id="mytabletitle" width="2%" align="center">BIL</td>
                <td id="mytabletitle" width="20%"><a href="#" onClick="formsort('stu.name','<?php echo "$nextdirection";?>')" title="Sort">NAMA SISWA</a></td>
                <td id="mytabletitle" width="5%" align="center">L/P</td>
                <td id="mytabletitle" width="10%" align="center">No Induk</td>
				<?php
				$sqlkelas="select count(*) as billevel from type where grp='classlevel' and sid='$sid'";
		$reskelas=mysql_query($sqlkelas)or die("$sqlkelas query failed:".mysql_error());
		$rowkelas=mysql_fetch_assoc($reskelas);
		$billevel=$rowkelas['billevel'];

		$kk=explode("/",$sesyear);
		$yy=$kk[0];
		$sqlses="and sesyear='$sesyear'";
 		for($jj=1;$jj<=$billevel;$jj++){
		?>
                <td id="mytabletitle" width="10%" align="center">Tahun Pelajaran</td>
		<?php }?>
                <td id="mytabletitle" width="10%" align="center">Keterangan</td>
          </tr>
        
          <tr>
                <td id="mytabletitle" align="center"></td>
                <td id="mytabletitle"><?php// echo $ses;?></td>
                <td id="mytabletitle" align="center"></td>
                <td id="mytabletitle" align="center"></td>

<?php          
/*$sql="select * from type where grp='session' and prm>='$sesyear' order by prm asc";
$resx=mysql_query($sql)or die("query failed:".mysql_error());
while($rowx=mysql_fetch_assoc($resx)){
			$ses=$rowx['prm'];  
			$sqlses="and sesyear='$ses'";
			*/
?>			
<?php
		$sqlkelas="select count(*) as billevel from type where grp='classlevel' and sid='$sid'";
		$reskelas=mysql_query($sqlkelas)or die("$sqlkelas query failed:".mysql_error());
		$rowkelas=mysql_fetch_assoc($reskelas);
		$billevel=$rowkelas['billevel'];

		$kk=explode("/",$sesyear);
		$yy=$kk[0];
		$sqlses="and stureg.sesyear='$sesyear'";
 		for($jj=1;$jj<=$billevel;$jj++){
			$zz=$yy+1;
			$s="$yy/$zz";
			$yy=$zz;
 ?>                
                <td id="mytabletitle" width="10%" align="center"><?php echo $s;?></td>
<?php //} 
			}?>                
                <td id="mytabletitle" width="10%" align="center"></td>
          </tr>
<?php          

     
	//$sql="select * from stureg where sch_id=$sid $sqllistname $sqlses $sqlsearch $sqlsort";
	$sql4="select * from stu left join stureg on stu.ic=stureg.ic where stu.sch_id=$sid $sqllistname $sqlses $sqlsearch $sqlsort";

	$res4=mysql_query($sql4)or die("$sql4:query failed:".mysql_error());
	$q=$curr;
  	while($row4=mysql_fetch_assoc($res4)){
		$stuuid=$row4['uid'];
		$name=ucwords(strtolower(stripslashes($row4['name'])));
		$sex=$lg_sexmf[$row4['sex']];
		$nobukuinduk=$row4['nobukuinduk']; 
		$xsid=$row4['sch_id']; 
		$ic=$row4['ic'];
		$enddate=$row4['enddate'];
		$status=$row4['studentstatus']; 
		
		$sql2="select * from type where grp='studentstatus' and val='$status'";
		$res2=mysql_query($sql2)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$statustudent=$row2['prm'];
			
	
		if(($q++%2)==0)
			$bg="#FFFFFF";
		else
			$bg="#FFFFFF";
?>       
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                <td id="myborder" align="center"><?php echo $q;?></td>
                <td id="myborder"><a href="../edaftar/bukuinduk.php?<?php echo "sid=$xsid&ic=$ic";?>" target="_blank"> <?php echo $name;?></a></td>
                <td id="myborder" align="center"><?php echo $sex;?></td>
                <td id="myborder" align="center"><?php echo $nobukuinduk;?></td>
<?php
		//$kk=explode("/",$ses);
		$yy=$kk[0];
 		for($jj=1;$jj<=$billevel;$jj++){
			$zz=$yy+1;
			$s="$yy/$zz";
			$yy=$zz;
			//$sql2="select * from stu_summary where type='EXAM' and year='$s' and ic='$ic' and sid='$xsid'";
			$sql3="select * from ses_stu where (stu_ic='$ic' or stu_uid='$stuuid') and sch_id='$xsid' and year='$s'";
			//echo "<br>";
			$res3=mysql_query($sql3)or die("$sql3:query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$cls=$row3['cls_name'];
			$ccls=$row3['cls_code'];
			if($cls=="")
				$cls="-";
			if($ccls==""){
				$ccls="";
			}else{
				$ccls="($ccls)";
			}
 ?>  
				<td id="myborder" align="center"><?php echo $cls. " $ccls";?></td>
<?php } ?>                 
                <td id="myborder"><?php echo "$statustudent $enddate";?></td>
          </tr>
<?php } ?> 
<?php //} ?>          
	</table>

</div></div>
<?php include("../inc/paging_sort_only.php");?>
</form>
</body>
</html>
