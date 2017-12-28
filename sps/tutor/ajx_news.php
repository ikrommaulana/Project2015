<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

$news=$_REQUEST['news'];
if($news!="")
	$sqlnews="and cat='$news'";
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
if($op=="save"){
	$msg=addslashes($_REQUEST['msg']);
	$uid=$_SESSION['username'];
	$sid=$_SESSION['sid'];
	$author=addslashes($_SESSION['name']);
	
	$sql="insert into content(dt,uid,sid,msg,author,adm,ts,app)values(now(),'$uid',$sid,'$msg','$author','$uid',now(),'TUTOR')";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
}
if($op=="delete"){
		$sql="delete from content where id=$id";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
}
/** paging control **/
	$curr=$_REQUEST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_REQUEST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=6;
		$sqlmaxline="limit $curr,$MAXLINE";
	}
	elseif($MAXLINE=="All")
		$sqlmaxline="";
	else
		$sqlmaxline="limit $curr,$MAXLINE";
/** sorting control **/
	$order=$_REQUEST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_REQUEST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, name asc";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>

<script type="text/javascript">
function ajx_news(news){
		var xmlhttp;
		
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("buletin_div").innerHTML=xmlhttp.responseText;
				//$('#contents').fadeOut('fast');
				//$('#contents').fadeIn('fast');
			}
		}
		m=document.myform.maxline.value;
		c=document.myform.curr.value;
		o=document.myform.order.value;
		s=document.myform.sort.value;
		xmlhttp.open("GET","../tutor/ajx_news.php?news="+news+"&maxline="+m+"&curr="+c+"&sort="+s,true);
		xmlhttp.send();
}

</script>

</head>
<body>
	
		<div id="contents" style="min-height:435px; height:435px; overflow-y:auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg);"> 
<?php
		$xsid=$_SESSION['sid'];
		if($xsid!=0)
			$sqlsid="and (sid=0 or sid=$xsid)";
		else
			$sqlsid="";
		$sql="select count(*) from content where app='TUTOR' and sta=1 $sqlnews and access like '%STAFF%' $sqlsid order by id desc";			
		$res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$total=$row[0];
		$last=$total;
		$q=$curr;
		if(($curr+$MAXLINE)<=$total)
			$last=$curr+$MAXLINE;
		
		$sql="select content.* from content where app='TUTOR' and sta=1 $sqlnews and access like '%STAFF%' $sqlsid $sqlsort $sqlmaxline";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$usr=$row['uid'];
				$dt=$row['dt'];
				$ts=$row['ts'];
				$f1=$row['file1'];
				$f2=$row['file2'];
				$f3=$row['file3'];
				$xid=$row['id'];
				$hit=$row['hit'];
				$tit=ucwords(strtolower(stripslashes($row['tit'])));
				$name=ucwords(strtolower(stripslashes($row['author'])));
				
				$sql="select count(*) from content where app='news_comment' and pid='$xid'";
        		$res2=mysql_query($sql)or die("query failed:".mysql_error());
        		$row2=mysql_fetch_row($res2);
				$comment=$row2[0];
				
				if(($q++%2)==0)
					$bg="#F1F1F1";
				else
					$bg="#FFFFFF";
					
				$bg="";
				$now=date('Y-m-d');
				$date_parts1=explode("-", $dt);
			    $date_parts2=explode("-", $now);
			
			   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
			   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
			   $jumhari= $end_date - $start_date;
			   if($jumhari<7)
			   		$new="<img src=../img/newicon.png>";
				else
					$new="";
					
				if(($f1!="")||($f2!="")||($f3!=""))
			   		$attach="<img src=../img/attach16.png>";
				else
					$attach="";
?>



			<table width="100%" style="padding:10px;" bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#F1F1F1';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			  <tr>
			  	<td colspan="2" style="font-size:14px;">
                			<a href="../tutor/news_info.php?id=<?php echo $xid;?>" onClick="return GB_showCenter('Notes',this.href,480,1000)" target="_blank" >
							<?php echo "$tit $attach $new";?></a>
				</td>
			 </tr>
			 <tr>
				<td width="50%"><?php echo $q;?>.&nbsp;<?php echo $lg_by;?>&nbsp;<?php echo $name;?></td>
                <td width="50%" align="right">Post: <?php echo "$dt";?>&nbsp;&nbsp;&nbsp;View: <?php echo "$hit";?>
                &nbsp;&nbsp;&nbsp;Comments:<?php echo "$comment";?>
                
					<?php if((is_verify('ADMIN'))||($uid==$_SESSION['username'])){?>
					&lt;<a href="../tutor/news_edit.php?id=<?php echo "$xid";?>" onClick="return GB_showCenter('Notes',this.href,480,1000)" target="_blank">Edit</a>&gt;
					<?php }?>
				</td>
			  </tr>
			</table>
           <div id="myborder"></div>
         <?php } ?>

 		</div>


</body>
</html>