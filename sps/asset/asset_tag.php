<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("asset",1);
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
		}
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (uid='$search' or ic='$search' or name like '%$search%')";
		
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==""){
		$MAXLINE=25;
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
		$sqlsort="order by $sort $order, id desc";
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

<script language="JavaScript">
function excel(page){ 
	document.formexcel.action=page;
    document.formexcel.submit();
}
</script>

</head>

<body >

 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
 <input type="hidden" name="p" value="<?php echo $p;?>">
 

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refesh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>				
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

<select name="sid" id="sid" onChange="document.myform.submit();">
                <?php
			if($sid==""){
            	echo "<option value=\"\">- $lg_all $lg_staff -</option>";
				echo "<option value=\"0\">- All Access -</option>";
      		}else if($sid=="0"){
            	echo "<option value=\"0\">- All Access -</option>";
				echo "<option value=\"\">- $lg_all $lg_staff -</option>";
			}else{
                echo "<option value=$sid>$ssname</option>";
			}
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				if($sid>0){
					echo "<option value=\"\">- $lg_all $lg_staff -</option>";
					echo "<option value=\"0\">- All Access -</option>";
				}
		}
?>
              </select>
			  
			  <input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>">                
				
				<div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				<input type="submit" name="Submit" value="Search">
				&nbsp;&nbsp;

				<input type="checkbox" name="tamat" value="1" onClick="document.myform.submit();" <?php if($tamat) echo "checked";?>>
				<?php echo "$lg_view $lg_end";?>
</div>
<div id="story">


<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px">SEARCH FOR : '<?php echo $search;?>'</div><?php }?>

<div id="mytitle2">USER'S ASSET TAGING</div>


<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%"  align="center"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" width="5%"  align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_staff_id);?></a></td>
            <td class="mytableheader" style="border-right:none;" width="20%">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="15%">&nbsp;<a href="#" onClick="formsort('jobdiv','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_division);?></a></td>
        	<td class="mytableheader" style="border-right:none;" width="15%">&nbsp;<a href="#" onClick="formsort('job','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_position);?></a></td>
			<td class="mytableheader" width="30%"> ASSET TAGGING</td>
	</tr>
<?php	
	$sql="select count(*) from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

		if(($curr+$MAXLINE)<=$total)
			 $last=$curr+$MAXLINE;
		else
			$last=$total;
	$q=$curr;
			
	$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort $sqlmaxline";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$xid=$row['id'];
			$uid=$row['uid'];
			$name=strtoupper(stripslashes($row['name']));
			$xjob=ucwords(strtolower(stripslashes($row['job'])));
			$xjobsta=ucwords(strtolower(stripslashes($row['jobsta'])));
			$status=$row['status'];
			$xjobdiv=ucwords(strtolower(stripslashes($row['jobdiv'])));
			$itemlist="";
			$sql="select * from asset where uid='$uid'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$jj=0;
			while($row2=mysql_fetch_assoc($res2)){
					$tag=$row2['item_tag'];
					$typ=$row2['type'];
					$cat=$row2['category'];
					$brand=$row2['item_brand'];
					if($jj++>0)
						$itemlist=$itemlist."<br>";
					$itemlist=$itemlist."$jj. $typ/$brand($tag)";
			}
					 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
					<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;
                    	<a href="../asset/asset_tagreg.php?uid=<?php echo $uid;?>"  target="_blank" class="fbbig"><?php echo "$name";?></a>
                    </td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;<?php echo "$xjobdiv";?></td>
					<td class="myborder" style="border-right:none; border-top:none;">&nbsp;<?php echo "$xjob";?></td>
					<td class="myborder" style="border-top:none;"><?php echo "$itemlist";?></td>
            </tr>

<?php }  ?>
 </table>
  <?php include("../inc/paging.php");?>
</div><!-- story -->
</div><!-- content -->
</form>	
<form name="formexcel" method="post">
 	<input type="hidden" name="sql">
</form>
</body>
</html>
