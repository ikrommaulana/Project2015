<?php
include_once('../etc/db.php');
include_once('etc/session_sms.php');
verify('ADMIN');
	

$search=$_POST['search'];
if(strcasecmp($search,"- Tel, Name -")==0)
	$search="";
if($search!="")
	$sqlsearch="and (tel='$search' or name like '%$search%')";

$grp=$_POST['grp'];
if($grp=="")
	$grp='STAFF';
if($grp!="")
		$sqlgrp="and grp='$grp'";
		
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


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function update_parent(){
	var tel=""
	for (var i=0;i<document.myform.elements.length;i++){
    	var e=document.myform.elements[i];
        if (e.type=='checkbox'){
        	if(e.checked==true){
            	if(tel!="")
					tel=tel + ", ";
				tel+=e.value;
        	}
      	}
	}
	self.opener.document.form1.tel.value = tel;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AddressBook</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>
<body >
<form name="myform" action="" method="post" >

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" id="mymenuitem" onClick="window.close()"><img src="../img/close.png"><br>Close</a>
	</div><!-- end mymenu -->
	<div align="right">
		<select name="grp" onChange="document.myform.submit();">
                        <?php 
							if($grp=="STAFF"){
								echo "<option value=\"STAFF\">STAFF</option>";
								//echo "<option value=\"WARIS\">WARIS</option>";
							}
							else{
								echo "<option value=\"WARIS\">WARIS</option>";
								echo "<option value=\"STAFF\">STAFF</option>";
							}
						?>
                      </select>
		<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- Tel, Name -"; else echo "$search";?>"> 
		<input type="submit" name="cari" value="Search">
	</div>
</div><!-- end mypanel -->

<div id="story">

	<table width="100%" id="mytable">
    <tr id="mytabletitle">
	<td width="2%"></td>
	<td width="3%">NO</td>
	<td width="15%"><a href="#" onClick="formsort('tel','<?php echo "$nextdirection";?>')" title="Sort">TELEFON</a></td>	
	<td width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">NAMA</a></td>
	<td width="20%"><a href="#" onClick="formsort('grp','<?php echo "$nextdirection";?>')" title="Sort">GROUP</a></td>
	</tr>

<?php
	if($grp=="STAFF"){
		$sql="select count(*) from usr where status=0 $sqlsid;";
	}else{
		$sql="select count(*) from stu where status=6 $sqlsid;";
	}		
    $res=mysql_query($sql,$link)or die("query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	if(($curr+$MAXLINE)<=$total)
		$last=$curr+$MAXLINE;
	else
		$last=$total;
	
	if($grp=="STAFF"){
		$sql="select * from usr where status=0 $sqlsid $sqlsearch $sqlsort $sqlmaxline";
	}else{
		$sql="select * from stu where status=6 $sqlsid $sqlsearch $sqlsort $sqlmaxline";
	}		
    $res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res)){
		$name=stripslashes($row['name']);
		$tel=$row['tel'];
		$hp=$row['hp'];
		if($q++%2==0)
			$bg="bgcolor=\"#FAFAFA\"";
		else
			$bg="";
			
?>

    <tr <?php echo "$bg";?>>
	<td><input type=checkbox name=del value="<?php echo "$name<$hp>";?>" onClick="return update_parent()"></td>
	<td><?php echo "$q";?></td>
	<td><?php echo "$hp";?></td>	
	<td><?php echo "$name";?></td>
	<td><?php echo "$grp";?></td>
	</tr>
	
<?php } ?>
 </table>

<?php include_once('../inc/paging.php');?>

</form>


</div></div>
</body>
</html>

