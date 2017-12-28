<?php
//STANDARD EDITION
//120117 - ADD REG_FILE
$vmod="v6.0.1";
$vdate="120117";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");

ISACCESS("eregister",1);
$sid=$_GET['sid'];
	if($sid==0){
		$sqlsid="";		
	} else {
		$sqlsid="and a.sch_id='$sid'";
	}
	
	$year=$_GET['year'];
	if($year!="") {
		$sqlyear="and b.year='$year'";
	} else{
		$sqlyear="";	
	}
	
        $clslevel=$_GET['clslevel'];
	if($clslevel!="") {
		$sqlclslevel="and a.clslevel='$clslevel'";
	} else{
		$sqlclslevel="";	
	}
	
	$clscode=$_GET['clscode'];
	if($clscode!="") {
		$sqlclscode="and b.cls_code='$clscode'";
	} else{
		$sqlclscode="";	
	}
	
	$op=$_REQUEST['op'];
	if($op=='save'){
		$jum=$_POST['jum'];
		for($i=1; $i<=$jum; $i++)
		{
			$uid=$_POST['uid'.$i];
			$nisn=$_POST['nisn'.$i];
		
			$simpan=mysql_query("UPDATE stu SET nisn='$nisn' WHERE uid='$uid'") or die(mysql_error());
			$update=mysql_query("UPDATE stureg SET nisn='$nisn' WHERE uid='$uid'") or die (mysql_error());
			if($simpan){
			$a= "Data telah tersimpan";
			header ("location:../stu/student.php");}		
			else {
			$a= "Data gagal disimpan"; }
		}
	}		
	
	$order=$_POST['order'];	
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	//echo "$sort";
	if($sort=="")
		$sqlsort="order by a.id desc";
	else
		$sqlsort="order by $sort";
		//$sqlsort="order by $sort $order, name asc";

	$sch=mysql_fetch_array(mysql_query("SELECT * FROM sch WHERE id='$sid'")) or die (mysql_error());
	$sekolah=$sch['name'];

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
function process_form(op)
{
	if (op=='save')
	{
		var ret="";
		var cflag=false;
		
		ret = confirm("Are you sure want to save??");
		if (ret == true)
		{
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}
</script>
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"  enctype="multipart/form-data">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
	<input type="hidden" name="xcurr" value="<?php echo $curr;?>">
<div id="content">
	<div id="mypanel">

		<div id="mymenu" align="center">
			<a href="#" onClick="process_form('save');"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
	   	<div align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
    
	</div><!-- end mypanel-->
<?php echo "<h2> <left><font color=#ebaf17> $a </font></left></h2></br>"; ?>
<div id="mytitlebg"><h2>PROSES PEMBERIAN NISN</h2><br>
TAHUN AJARAN <?php echo "$year $sekolah"; ?>
</div>
<br>
<table width="75%" id="mytable" cellspacing="0" cellpadding=0>
	<tr>
		<td width="5%" valign="top" align="center" class="mytableheader" style="border-right:none;" align="center" width="1%"><?php echo strtoupper("$lg_no");?></td>
		<td width="20%" id="mytabletitle" valign="top" align="center">NIS</td>
		<td width="25%" valign="top" align="center" class="mytableheader" style="border-right:none;" align="center" width="18%"><a href="#" onClick="clear_newwindow();formsort('name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_student_name");?></a></td>
		<td width="20%" id="mytabletitle" valign="top" align="center">NAMA KELAS</td>
		<td width="15%" id="mytabletitle" valign="top" align="center">NISN</td> 
	</tr>

<?php
	$i=0;
    $select = mysql_query("SELECT a.name, a.uid, a.nisn, b.cls_name, b.year FROM stu a LEFT JOIN ses_stu b ON (a.sch_id=b.sch_id AND a.clslevel=b.cls_level AND a.uid=b.stu_uid AND a.name=b.stu_name) WHERE a.status=6 $sqlsid $sqlyear $sqlclslevel $sqlclscode $sqlsort") or die (mysql_error());
    while($read=mysql_fetch_array($select)){
        $nama=$read['name'];
        $nisn=$read['nisn'];
	$cls=$read['cls_name'];
	$uid=$read['uid'];
	$tahun=$read['year'];
	$sch_id=$read['sch_id']; 
	//echo "$sch_name";
     $i++;

echo "<tr bgcolor=\"$bg\" style=\"cursor:default\" onMouseOver=\"this.bgColor=#CCCCFF\" onMouseOut=\"this.bgColor=$bg\">
  	<td id=\"myborder\" align=\"center\">$i</td>
	<td id=\"myborder\">$uid</td>
	<td id=\"myborder\"><input type=\"hidden\" name=\"uid$i\" value=\"$uid\"> $nama</td>
	<td id=\"myborder\">$cls</td>
        <td id=\"myborder\" align=\"center\"><input type=\"text\" name=\"nisn$i\" value=\"$nisn \"></td>
        
  </tr>";
}?>
<input type="hidden" name="jum" value="<?php echo $i; ?>">
</table>

</div></div> 

</form> <!-- end myform -->
</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->