<?php
$vmod="v5.0.0";
$vdate="05/01/11";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include("$MYOBJ/fckeditor/fckeditor.php");
$adm = $_SESSION['username'];
verify("");

		$isajax=$_REQUEST['isajax'];
		$cat=$_REQUEST['cat'];
		if($cat!="")
				$sqlcat="and cat='$cat'";
				
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_title -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (tit like '%$search%')";

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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>
<!-- SETTING FANCYBOX -->
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!-- My SETTING -->
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>	
<script type="text/javascript" src="<?php echo $MYLIB;?>/inc/myfancybox.js" type="text/javascript"></script>

<script language="JavaScript">
function process_search(op){
		var xmlhttp;
		var msg;
		
		msg=document.myform.search.value;	
		
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
						document.getElementById("story").innerHTML=xmlhttp.responseText;
			}
		}
			
		xmlhttp.open("GET","../tutorsps/tutorial.php?isajax=1&search="+msg,true);
		xmlhttp.send();
		
}
</script>

</head>

<body >
<?php if(!$isajax){?>
 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
 <input type="hidden" name="p" value="<?php echo $p;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php if(is_verify("ROOT")){?>        
<a href="edit.php" class="fbbig" target="_blank" id="mymenuitem"><img src="../img/new.png"><br><?php echo $lg_new;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<?php } ?>        
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>


</div> <!-- end mymenu -->

<div id="viewpanel" align="right" >
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		
			
</div><!-- end viewpanel -->


</div> 
<!-- end mypanel -->

<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">
					<select name="cat" onChange="document.myform.submit();">
                             <?php 
													if($cat!=""){
														 echo "<option value=\"$cat\">$cat</option>";
														 echo "<option value=\"\">- $lg_all -</option>";
													}
													else
														 echo "<option value=\"\">- $lg_topic -</option>";
                                                    $sql="select distinct(cat) from tutorial_sys order by idx;";
                                                    $res=mysql_query($sql)or die("query failed:".mysql_error());
                                                    while($row=mysql_fetch_assoc($res)){
                                                                $s=$row['cat'];
                                                                $v=stripslashes($row['name']);
                                                                echo "<option value=\"$s\">$s</option>";
                                                    }
													
														
                              ?>
                     </select>
                     <input name="search" style="font-size:14px" type="text"  id="search" size="30" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
				 onKeyUp="process_search();" value="<?php if($search=="") echo "- $lg_title -"; else echo "$search";?>">                
				
				<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>&nbsp;
				<input type="submit" name="Submit" value="View..">

</div>
<div id="story">
<?php } //isajax ?>
<div id="mytitle2">Tutorial</div>

<table width="100%" cellspacing="0" cellpadding="3px" style="font-size:11px;">
	<tr>
         	<td id="mytabletitle" width="3%"align="center"><?php echo $lg_no;?></td>
			<td id="mytabletitle" width="15%"><?php echo $lg_topic;?></td>
            <?php if(is_verify("ROOT")){?>
             <td id="mytabletitle" width="3%" align="center"><?php echo $lg_id;?></td>
            <?php }?>
            <td id="mytabletitle" width="55%"><?php echo $lg_title;?></td>
			<td id="mytabletitle" width="10%" align="center"><?php echo $lg_view;?></td>
			<td id="mytabletitle" width="10%" align="center"><?php echo $lg_comment;?></td>
            <?php if(is_verify("ROOT")){?>
             <td id="mytabletitle" width="3%" align="center">Group ID</td>
             <td id="mytabletitle" width="3%" align="center">Index</td>
            <?php }?>

	</tr>
	<?php	
	$sql="select count(*) from tutorial_sys where app='TUTORSPS' $sqlcat $sqlsearch and sta=1 order by id desc";			
		$res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$total=$row[0];
		$last=$total;
		$q=$curr;
		if(($curr+$MAXLINE)<=$total)
			$last=$curr+$MAXLINE;
		
		$sql="select tutorial_sys.* from tutorial_sys where app='TUTORSPS' $sqlcat $sqlsearch and sta=1 order by gid,idx $sqlmaxline";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$xid=$row['id'];
				$dt=$row['dt'];
				$ts=$row['ts'];
				$f1=$row['file1'];
				$f2=$row['file2'];
				$f3=$row['file3'];
				$idx=$row['idx'];
				$gid=$row['gid'];
				$hit=$row['hit'];
				$topic=$row['cat'];
				$tit=ucwords(strtolower(stripslashes($row['tit'])));
				$name=ucwords(strtolower(stripslashes($row['author'])));
				
				$sql="select count(*) from tutorial_sys where app='TUTORSPS_COMMENT' and pid='$xid'";
        		$res2=mysql_query($sql)or die("query failed:".mysql_error());
        		$row2=mysql_fetch_row($res2);
				$comment=$row2[0];
				
				if(($q++%2)==0)
					$bg="#FAFAFA";
				else
					$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>"  onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
              	<td id=myborder align="center"><?php echo "$q";?></td>
                <td id=myborder><?php echo "$topic";?></td>
                <?php if(is_verify("ROOT")){?>
                <td id=myborder align="center">
                	<a href="edit.php?id=<?php echo $xid;?>" class="fbmedium" target="_blank"><?php echo "$xid";?></a>
                 </td>
                <?php }?>
              	<td id=myborder><a href="view.php?id=<?php echo $xid;?>" class="fbmedium" target="_blank"><?php echo "$tit";?></a></td>
			  	<td id=myborder align="center"><?php echo "$hit";?></td>
				<td id=myborder align="center"><?php echo $comment;?></td>
                <?php if(is_verify("ROOT")){?>
                <td id=myborder align="center"><?php echo $gid;?></td>
                <td id=myborder align="center"><?php echo $idx;?></td>
                <?php }?>

            </tr>
<?php }  ?>
 </table>
 
<?php include("../inc/paging.php");?>
 <?php if(!$isajax){?>
</div><!-- story -->
</div><!-- content -->
</form>	
<?php } //if!ajax?>
</body>
</html>