<?php
//110610 - upgrade gui
//110610 - fixed staff view
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
ISACCESS("sarpras",1);
$adm=$_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
			
		$cat=$_REQUEST['cat'];
		if($cat=="")
		{
			$sql="select * from type where grp='asset_cate' and val='1' and idx='1'";
			$res=mysql_query($sql) or die(mysql_error());
			$row=mysql_fetch_assoc($res);
			$cat=$row['prm'];
		}
		if($cat!="")
			$sqlcategory="and category='$cat'";
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
		}
	$sesyear=$_POST['year'];
	if($sesyear==""){
		$sql="select * from type where grp='session' and prm!='$sesyear' order by val desc";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
                $sesyear=$row['prm'];
	}
		
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
				             
				<!--<a href="../asset/excel.php?sql=<?php echo $sql;?>" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/excel.png"><br>Export</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>-->
		</div> <!-- end mymenu -->

		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
                        
        <?php if(is_verify('ROOT')){?>
        Setting:<a href="#" onClick="newwindow('../adm/prm.php?grp=asset_cate',0)">Category</a>|<a href="#" onClick="newwindow('../adm/prmcolum.php?grp=asset_col',0)">Column</a>
        <?php } ?>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">
   
    <select name="cat"  onChange="document.myform.submit();">
<?php
      		/*if($cat=="")
            	echo "<option value=\"\">- Category -</option>";
			else*/
		if($cat!="")
                echo "<option value=\"$cat\">$cat</option>";
		
			$sql="select * from type where grp='asset_cate' and prm!='$cat' and val='1' order by idx";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=$row['prm'];
                
                echo "<option value=\"$x\">$x</option>";
            }
			//if($cat!="")
           		//echo "<option value=\"\">- $lg_all -</option>";
?>
		</select>
     <select name="year" id="year" onchange="document.myform.submit();">
				<?php
 			echo "<option value=$sesyear>$sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$sesyear' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						echo "<option value=\"$s\">$s</option>";
			}			  
				?>
          </select>
</div>
<div id="story">
     <?php if(is_verify('ADMIN')){?>
    <div id="mytitlebg" style="font-size: 12px;"><a href="asset_item.php?cate=<?php echo "$cat";?>&year=<?php echo $sesyear;?>" class="fbsmall" target="_blank" >+Penambahan</a></div>
    <?php }?> 
     <table width="100%" cellspacing="1" cellpadding="2">
	<tr>
	<td class='mytableheader' style='border-right:none;font-size:11px;' width='1%'  align='center'>No</td>
            <?php
               $sql="Select * from type where grp='asset_col' and val='1' and code='$cat' order by idx asc";
               $res=mysql_query($sql) or die(mysql_error());
	       $numcat=mysql_num_rows($res);
               while($row=mysql_fetch_assoc($res)){
                     $sprm=$row['prm'];
		     $etc=$row['etc'];
		     if($etc=="2")
		          $xdes="(Kebutuhan)";
                ?>
		
                <td class='mytableheader' style='border-right:none;font-size:11px;' width='2%'  align='center'><?php echo $sprm."<br>".$xdes;?></td>
        
                
        <?php }
            ?>
        </tr>
	<?php
	
	//subcategory
	$sqlsubcat="select * from type where grp='assest_sub' and code='$cat' and val='1' order by idx asc";
	$ressubcat=mysql_query($sqlsubcat) or die(mysql_error());
	$numsub=mysql_num_rows($ressubcat);
	$q=0;
	if($numsub>0){
		
	     while($rowsub=mysql_fetch_assoc($ressubcat)){
		$sub=$rowsub['prm'];
		?>
		<tr>
			<td id="myborder" colspan="<?php echo $numcat+1?>" align="left" style="font-weight: bold;background: #cccccc;"><?php echo $sub;?></td>
		</tr>
		<?
         /******************wit sub category*****************/	
	$sqllist="select * from asset_indo where year='$sesyear' and category='$cat' and subcategory='$sub' order by id asc";
	 
	    $reslist=mysql_query($sqllist) or die(mysql_error());
	    $numrow=mysql_num_rows($reslist);
	    while($rowl=mysql_fetch_assoc($reslist)){
		$xid=$rowl['id'];
	        $item=$rowl['item'];
		$q1=$rowl['q1'];
		$q2=$rowl['q2'];
		$q3=$rowl['q3'];
		$q4=$rowl['q4'];
		$q5=$rowl['q5'];
		$q6=$rowl['q6'];
		$q7=$rowl['q7'];
		$q8=$rowl['q8'];
		$q9=$rowl['q9'];
		$q10=$rowl['q10'];
		$q11=$rowl['q11'];
		$q12=$rowl['q12'];
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FFFFFF";
	?>
	<tr bgcolor=<?php echo $bg;?> style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg;?>'">
		<td id='myborder' width="1%" align="center"><?php echo $q;?></td>
		<td id='myborder'><a href="asset_item.php?cate=<?php echo "$cat";?>&year=<?php echo $sesyear;?>&xid=<?php echo $xid;?>" class="fbsmall" target="_blank" ><?php echo $item;?></a></td>
		<?php if($q1!=""){?><td id='myborder' align="center"><?php echo $q1;?></td><?php }?>
		<?php if($q2!=""){?><td id='myborder' align="center"><?php echo $q2;?></td><?php }?>
		<?php if($q3!=""){?><td id='myborder' align="center"><?php echo $q3;?></td><?php }?>
		<?php if($q4!=""){?><td id='myborder' align="center"><?php echo $q4;?></td><?php }?>
		<?php if($q5!=""){?><td id='myborder' align="center"><?php echo $q5;?></td><?php }?>
		<?php if($q6!=""){?><td id='myborder' align="center"><?php echo $q6;?></td><?php }?>
		<?php if($q7!=""){?><td id='myborder' align="center"><?php echo $q7;?></td><?php }?>
		<?php if($q8!=""){?><td id='myborder' align="center"><?php echo $q8;?></td><?php }?>
		<?php if($q9!=""){?><td id='myborder' align="center"><?php echo $q9;?></td><?php }?>
		<?php if($q10!=""){?><td id='myborder' align="center"><?php echo $q10;?></td><?php }?>
		<?php if($q11!=""){?><td id='myborder' align="center"><?php echo $q11;?></td><?php }?>
		<?php if($q12!=""){?><td id='myborder' align="center"><?php echo $q12;?></td><?php }?>
		
	</tr>
	
	<?php 
	}
	
	
     }
	
}else{
/******************without sub category*****************/
	
	    $sqllist="select * from asset_indo where year='$sesyear' and category='$cat' order by id asc";
	 
	    $reslist=mysql_query($sqllist) or die(mysql_error());
	    $numrow=mysql_num_rows($reslist);
	    while($rowl=mysql_fetch_assoc($reslist)){
		$xid=$rowl['id'];
	        $item=$rowl['item'];
		$q1=$rowl['q1'];
		$q2=$rowl['q2'];
		$q3=$rowl['q3'];
		$q4=$rowl['q4'];
		$q5=$rowl['q5'];
		$q6=$rowl['q6'];
		$q7=$rowl['q7'];
		$q8=$rowl['q8'];
		$q9=$rowl['q9'];
		$q10=$rowl['q10'];
		$q11=$rowl['q11'];
		$q12=$rowl['q12'];
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FFFFFF";
	?>
	<tr bgcolor=<?php echo $bg;?> style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg;?>'">
		<td id='myborder' width="1%" align="center"><?php echo $q;?></td>
		<td id='myborder'><a href="asset_item.php?cate=<?php echo "$cat";?>&year=<?php echo $sesyear;?>&xid=<?php echo $xid;?>" class="fbsmall" target="_blank" ><?php echo $item;?></a></td>
		<?php if($q1!=""){?><td id='myborder' align="center"><?php echo $q1;?></td><?php }?>
		<?php if($q2!=""){?><td id='myborder' align="center"><?php echo $q2;?></td><?php }?>
		<?php if($q3!=""){?><td id='myborder' align="center"><?php echo $q3;?></td><?php }?>
		<?php if($q4!=""){?><td id='myborder' align="center"><?php echo $q4;?></td><?php }?>
		<?php if($q5!=""){?><td id='myborder' align="center"><?php echo $q5;?></td><?php }?>
		<?php if($q6!=""){?><td id='myborder' align="center"><?php echo $q6;?></td><?php }?>
		<?php if($q7!=""){?><td id='myborder' align="center"><?php echo $q7;?></td><?php }?>
		<?php if($q8!=""){?><td id='myborder' align="center"><?php echo $q8;?></td><?php }?>
		<?php if($q9!=""){?><td id='myborder' align="center"><?php echo $q9;?></td><?php }?>
		<?php if($q10!=""){?><td id='myborder' align="center"><?php echo $q10;?></td><?php }?>
		<?php if($q11!=""){?><td id='myborder' align="center"><?php echo $q11;?></td><?php }?>
		<?php if($q12!=""){?><td id='myborder' align="center"><?php echo $q12;?></td><?php }?>
		
	</tr>
	
	<?php 
	} }?>
	
     </table>
</div>
   
                

</div><!--endcontent-->
</form>
</body></html>