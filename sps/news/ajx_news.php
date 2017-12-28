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
	
	$sql="insert into content(dt,uid,sid,msg,author,adm,ts,app)values(now(),'$uid',$sid,'$msg','$author','$uid',now(),'COMMUNITY_INFO')";
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
		$MAXLINE=5;
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
				$(".fbmedium").fancybox({
					'width'				: '75%',
					'height'			: '85%',					
					'autoScale'			: false,					
					'type'				: 'iframe',
				});
				$(".fbbig").fancybox({
					'width'				: '90%',
					'height'			: '100%',					
					'autoScale'			: true,					
					'type'				: 'iframe',
				});
			}
		}
		m=document.myform.maxline.value;
		c=document.myform.curr.value;
		o=document.myform.order.value;
		s=document.myform.sort.value;
		xmlhttp.open("GET","../news/ajx_news.php?news="+news+"&maxline="+m+"&curr="+c+"&sort="+s,true);
		xmlhttp.send();
}
</script>

</head>
<body>
<div id="buletin_div">

<?php if(is_verify("ADMIN")){?>
<a href="../adm/prm.php?grp=newscategory" target="_blank" class="fbmedium" style="float:left">[+]</a>
<?php } ?>


    <table cellpadding="0" cellspacing="0" width="95%" style="font-size:12px; font-weight:bold;">
    	<tr>
		<?php 
			$jj=1;
			if($news=="")
					echo "<td align=\"center\" valign=\"top\" style=\"cursor:pointer;text-decoration:underline;\" onClick=\"ajx_news('')\">$lg_latest<br><img src=\"../img/collapse.gif\"></td>";
			else
					echo "<td align=\"center\" valign=\"top\"><a style=\"cursor:pointer;color:#333;\" onClick=\"ajx_news('')\">$lg_latest</a></td>";
			$sql="select * from type where grp='newscategory' order by idx, id";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
					$prm=$row['prm'];
					$code=$row['code'];
					if(($jj++%5)==0)
						echo "</tr><tr>";
						
					if($news==$code)
						echo "<td align=\"center\" valign=\"top\" style=\"cursor:pointer;text-decoration:underline;\" onClick=\"ajx_news('$code')\">$prm<br><img src=\"../img/collapse.gif\"></td>";
					else
						echo "<td align=\"center\" valign=\"top\"><a style=\"cursor:pointer;color:#333;\" onClick=\"ajx_news('$code')\">$prm</a></td>";
					
					
			}
				
		?>
</tr>
</table>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;padding:4px"></div>


		<div id="contents" style="min-height:400px; height:400px; overflow-y:auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)"> 
<?php
		$xsid=$_SESSION['sid'];
		if($xsid!=0)
			$sqlsid="and (sid=0 or sid=$xsid)";
		else
			$sqlsid="";
		$sql="select count(*) from content where app='NEWS' and cat!='ewaris' and sta=1 $sqlnews and access like '%STAFF%' $sqlsid order by id desc";			
		$res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$total=$row[0];
		$last=$total;
		$q=$curr;
		if(($curr+$MAXLINE)<=$total)
			$last=$curr+$MAXLINE;
		
		$sql="select content.* from content where app='NEWS' and cat!='ewaris' and sta=1 $sqlnews and access like '%STAFF%' $sqlsid $sqlsort $sqlmaxline";
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



			<table width="100%" cellspacing="0" style="padding:10px;" bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#F1F1F1';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			  <tr>
			  	<td style="font-size:12px; font-weight:bold;"><?php echo $q;?>.
                			<a href="../news/news_info.php?id=<?php echo $xid;?>" target="_blank" class="fbmedium">
							<?php echo "$tit $attach $new";?></a>
				</td>
			 </tr>
			 <tr>
				<td><?php echo $lg_by;?>&nbsp;<?php echo $name;?>&nbsp;</td>
				</tr>
              <tr>
					<td align="right">
						<?php echo $lg_post;?>:<?php echo "$dt";?>&nbsp;
						<?php echo $lg_view;?>:<?php echo "$hit";?>&nbsp;
                        <?php echo $lg_comment;?>:<?php echo "$comment";?>
                    <?php if((is_verify('ADMIN'))||($uid==$_SESSION['username'])){?>
						<a href="../news/news_edit.php?id=<?php echo "$xid";?>" target="_blank" class="fbmedium"><img src="../img/edit12.png" height="12px"></a>
					<?php }?></td>
			  </tr>
			</table>
            <div id="myborder" style="border-bottom:none;"></div>
<?php } ?>
		    <?php if(($curr+$MAXLINE)<$total){?>
            <div align="right" style="padding-right:20px;">
					<br>&nbsp;&nbsp;<a style="cursor:pointer; font-size:12px;" onClick="document.myform.curr.value=<?php echo $curr+$MAXLINE;?>;ajx_news('<?php echo $news;?>');"><?php echo $lg_more;?>..</a>
                    </div>
			<?php } ?>
		</div>


     
	 
    <input type="hidden" name="curr" value="<?php echo $curr;?>">
	<input type="hidden" name="sort" value="<?php echo $sort;?>">
	<input type="hidden" name="order" value="<?php echo $order;?>">
    <input type="hidden" name="news" value="<?php echo $news;?>">
	
		<div id="mytitle" style="background-color:#F1F1F1;">
          <div style="float:left ">
		  		  <?php if($total>0) $c=$curr+1; else $c=$curr; echo "$c-$q OVER $total";?>
		  </div>
		  <div align="right">
		  	
		  	&nbsp;
			<?php if($MAXLINE>0){ ?>
				<?php if($curr>=$MAXLINE){?>
					<a style="cursor:pointer" onClick="document.myform.curr.value=0;ajx_news('<?php echo $news;?>');" title="go first">FIRST</a> |
				<?php } ?>
				<?php if($curr>=$MAXLINE){?>
					<a style="cursor:pointer" onClick="document.myform.curr.value=<?php echo $curr-$MAXLINE;?>;ajx_news('<?php echo $news;?>');">PREV</a> 
				<?php } ?>
				<?php if(($curr>=$MAXLINE)&&(($curr+$MAXLINE)<$total)){?>
				|
				<?php } ?>
				<?php if(($curr+$MAXLINE)<$total){?>
					<a style="cursor:pointer" onClick="document.myform.curr.value=<?php echo $curr+$MAXLINE;?>;ajx_news('<?php echo $news;?>');">NEXT</a> |
				<?php } ?>
				<?php if(($curr+$MAXLINE)<$total){?>
					<a style="cursor:pointer" onClick="document.myform.curr.value=<?php if(($total%$MAXLINE)==0) echo $total-$MAXLINE; else echo $total-($total%$MAXLINE);?>;
                    ajx_news('<?php echo $news;?>');" >
					LAST
					</a>
				<?php } ?>
			<?php } ?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="maxline" style="font-size:9px; color:#0000FF " onChange="document.myform.curr.value=0;ajx_news('<?php echo $news;?>');">
				<?php echo "<option value=\"$MAXLINE\">$MAXLINE / $lg_page</option>";?>
				<option value="10">10 / <?php echo $lg_page;?></option>
				<option value="20">20 / <?php echo $lg_page;?></option>
				<option value="30">30 / <?php echo $lg_page;?></option>
				<option value="40">40 / <?php echo $lg_page;?></option>
				<option value="50">50 / <?php echo $lg_page;?></option>
				<option value="60">60 / <?php echo $lg_page;?></option>
				<option value="70">70 / <?php echo $lg_page;?></option>
				<option value="80">80 / <?php echo $lg_page;?></option>
				<option value="90">90 / <?php echo $lg_page;?></option>
				<option value="All"><?php echo $lg_all;?> / <?php echo $lg_page;?></option>
			</select>
			</div>
  
	</div>
 </div>



</body>
</html>