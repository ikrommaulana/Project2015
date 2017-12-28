<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
	

$search=$_POST['search'];
if(strcasecmp($search,"- Tel, Name -")==0)
	$search="";
if($search!="")
	$sqlsearch="and (tel='$search' or name like '%$search%')";

$grp=$_POST['grp'];
if($grp!="")
		$sqlgrp="and grp='$grp'";
		
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
		$sqlsort="order by $sort $order, id desc";
	

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function update_parent(){
	var act=""
	for (var i=0;i<document.myform.elements.length;i++){
    	var e=document.myform.elements[i];
        if (e.type=='checkbox'){
        	if(e.checked==true){
            	if(act!="")
					act=act + ", ";
				act+=e.value;
        	}
      	}
	}
	self.opener.document.myform.actlist.value = act;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Kasus Kedisiplinan</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>
<body style="background-image:url(img/bg_white.jpg) ">
<form name="myform" action="" method="post" >

<div id="content">
<div id="mypanel">
<div id="mymenu">
</div><!-- end mymenu -->
	
</div><!-- end mypanel -->
<div id="story">
	<table width="100%"  border="0" id="mytitle">
      <tr >
	    <td id="mytabletitle" width="2%" align="center"></td>
	  	<td id="mytabletitle" width="98%">Daftar Tindakan </td>
		<!-- 
		<td id="mytabletitle" width="2%" align="center">Mata</td>
		 -->
      </tr>
<?php 
$q=0;
$sql="select * from type where grp='dis_act' order by etc asc , prm asc";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
	$s=$row['prm'];
	$c=$row['code'];
	$t=$row['val'];
	if(($q++%2)==0)
		$bg="bgcolor=#FAFAFA";
	else
		$bg="bgcolor=#FFFFFF";
	
?>
      <tr <?php echo "$bg";?>>
	  	<td><input type=checkbox name=act value="<?php echo "$s";?>" onClick="update_parent()"></td>
        <td><?php echo "$s";?></td>
		<!-- 
		<td align="center"><?php echo "$t";?></td>
		 -->
      </tr>
<?php } ?>
    </table>


</form>


</div></div>
</body>
</html>

