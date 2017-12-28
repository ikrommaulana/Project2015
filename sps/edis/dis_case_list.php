<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
	

$search=$_POST['search'];
if(strcasecmp($search,"- Keyword -")==0)
	$search="";
if($search!="")
	$sqlsearch="and prm like '%$search%'";

$cs=$_REQUEST['cs'];
if($cs!=""){
	$sqlcase=" and category='$cs'";
}else{
	$sqlcase="";
}
$grp=$_POST['grp'];
if($grp!=""){
	$sqlgrp="and grp='$grp'";
	$sql="select * from type where grp='dis_cat' and code='$grp'"; 	
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$grpname=$row['prm'];
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
		$sqlsort="order by $sort $order, id desc";
	

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function update_parent(){
	var dis=""
	for (var i=0;i<document.myform.elements.length;i++){
    	var e=document.myform.elements[i];
        if (e.type=='checkbox'){
        	if(e.checked==true){
            	if(dis!="")
					dis=dis + ", ";
				dis+=e.value;
        	}
      	}
	}
	self.opener.document.myform.kes.value = dis;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Kasus Disiplin</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>
<body style="background-image:url(img/bg_white.jpg) ">
<form name="myform" action="" method="post" >

<div id="content">
<div id="mypanel">
<div id="mymenu">
<a href="../adm/prm_dis.php?fr=discase&cs=<?php echo $cs;?>" id="mymenuitem"><img src="../img/new.png"><br>New</a>
</div><!-- end mymenu -->
	<div style="padding:5px 5px 5px 5px " align="right">
		<select name="grp" onChange="document.myform.submit();">
          <?php 
					if($grp=="")
						echo "<option value=\"\">- Semua Kategory -</option>";
					else
						echo "<option value=\"$grp\">$grpname</option>";
                  	$sql="select * from type where grp='dis_cat' and etc='$cs' and code!='$grp' order by prm"; 	
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['code'];
						echo "<option value=\"$v\">$s</option>";
					}
					if($grp!="")
						echo "<option value=\"\">- Semua Kategori -</option>";
		?>
        </select>
		<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- Keyword -"; else echo "$search";?>"> 
		<input type="submit" name="cari" value="Search">
	</div>
</div><!-- end mypanel -->
<div id="story">
	<table width="100%"  border="0" id="mytitle">
      <tr >
	    <td id="mytabletitle" width="2%" align="center"></td>
		<?php if($cs=="demerit"){?>
	  	<td id="mytabletitle" width="98%">Daftar Kasus </td>
		<?php }else{?>
		<td id="mytabletitle" width="98%">Daftar Prestasi </td>
		<?php }?>
		<td id="mytabletitle" width="2%" align="center">Poin</td>
      </tr>
<?php 
$sql="select * from dis_case where id>0 $sqlcase $sqlgrp $sqlsearch order by grp,val";
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
	  	<td><input type=checkbox name=dis value="<?php echo "$s";?>" onClick="update_parent()"></td>
        <td><?php echo "$s";?></td>
		<td align="center"><?php echo "$t";?></td>
      </tr>
<?php } ?>
    </table>


</form>


</div></div>
</body>
</html>

