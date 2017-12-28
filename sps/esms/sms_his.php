<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
verify('ADMIN');

	
	$del=$_POST['del'];
	$op=$_POST['op'];
	$id=$_REQUEST['id'];
	if($id!=""){
		$sql="select * from sms_msg where id=$id";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$tel=$row['tel'];	
	}
	if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
		      	$sql="delete from sms_msg where id=$del[$i]";
		      	mysql_query($sql)or die("$sql query failed:".mysql_error());
			}
		}
	}		
	
	$sta=$_REQUEST['sta'];
	if($sta=="")
        $sta=0;
	if($sta==0) $status="New";
	if($sta==1) $status="Complete";
	if($sta==2) $status="Pending";
	if($sta==3) $status="Trash";
	
	$sqlstatus="sta='$sta'";

		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- Ref, Tel -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "(id='$search' or tel='$search')";
			$search=stripslashes($search);
			$sqlstatus="";
		}


	  	$sql="select count(*) from sms_msg where sta=0";//new
		$res=mysql_query($sql)or die("query failed".mysql_error());
		$row=mysql_fetch_row($res);
		$t1=$row[0];
		$sql="select count(*) from sms_msg where sta=1";//done
		$res=mysql_query($sql)or die("query failed".mysql_error());
		$row=mysql_fetch_row($res);
		$t2=$row[0];
		$sql="select count(*) from sms_msg where sta=2";//pending
		$res=mysql_query($sql)or die("query failed".mysql_error());
		$row=mysql_fetch_row($res);
		$t3=$row[0];
		$sql="select count(*) from sms_msg where sta=3";//no need action
		$res=mysql_query($sql)or die("query failed".mysql_error());
		$row=mysql_fetch_row($res);
		$t4=$row[0];

	
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
		$sqlsort="order by $sort $order";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
<!--
function process_form(op)
{
	var ret="";
	var cflag=false;
	if(op=='delete'){
		for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.type=='checkbox')&&(e.name!='checkall')){
                        if(e.checked==true)
                                cflag=true;
                        else
                                allflag=false;
                }
        }
		if(!cflag){
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}


//-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>

</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="sms_his">
	<input type="hidden" name="op">
	<input name="sta" type="hidden" value="<?php echo $sta;?>">
	<input name="id" type="hidden" value="<?php echo $id;?>">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
	<input name="curr" type="hidden">

<div id="panelleft"> 
	<?php include('../inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->

</div><!-- end of mypanel -->
<div id="story">
<div id="mytitle">HISTORY <?php echo "$tel";?></div>

<table width="100%">
	<tr >
	  	<td id="mytabletitle" width="2%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
        <td id="mytabletitle" width="5%">&nbsp;No</td>
        <td id="mytabletitle" width="15%"><a href="#" onClick="formsort('id','<?php echo "$nextdirection";?>')" style="text-decoration:underline" title="<?php echo "sort $nextdirection";?>"><strong>Date</strong></a></td>
        <td id="mytabletitle" width="10%"><a href="#" onClick="formsort('tel','<?php echo "$nextdirection";?>')" style="text-decoration:underline" title="<?php echo "sort $nextdirection";?>"><strong>Telefon</strong></a></td>
        <td id="mytabletitle" width="70%">Message (<?php echo "$status";?>)</td>
	</tr>
<?php	
 
	$sql="select count(*) from sms_msg where tel='$tel'";
    $res=mysql_query($sql,$link)or die("query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$total=$row[0];
	if(($curr+$MAXLINE)<=$total)
		$last=$curr+$MAXLINE;
	else
		$last=$total;
	$q=$curr;
	$sql="select * from sms_msg where tel='$tel' limit $curr,$MAXLINE";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$dt=$row['ts'];
		$tel=$row['tel'];
		$msg=$row['msg'];
		$typ=$row['typ'];
		$xx = htmlspecialchars($msg, ENT_QUOTES);
	    if(strlen($xx)>='75')
    		$msg=substr($xx,0,75)."..";
		if(($q++%2)==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="";
		if($typ=="MT")
			$replyicon="<img src=\"../img/reply.png\">";
		else
			$replyicon="";
		$q++;
?>
<tr <?php echo $bg;?>>
		<td id="myborder"><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)"></td>
		<td id="myborder">&nbsp;<?php echo "$q&nbsp;$replyicon";?></td>
    	<td id="myborder"><?php echo "$dt";?></td>
	   	<td id="myborder"><a href="p.php?p=sms_his&id=<?php echo "$xid";?>" title="history" ><?php echo "$tel";?></a></td>
		<td id="myborder"><a href="p.php?p=sms_msg&id=<?php echo "$xid";?>" title="reply"><?php echo "$msg";?></a></td>
  </tr>

<?php } ?>

  </table>
 

	<?php include("../inc/paging.php");?>
	
</div></div>
 
</form>
</body>
</html>
