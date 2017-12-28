<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("estaff",1);
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
$p=$_REQUEST['p'];

		if($_SESSION['sid']>0)
			$sid=$_SESSION['sid'];
		else
			$sid=$_REQUEST['sid'];
	
		if($sid!="")
			$sqlsid=" and sch_id=$sid";
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_id_name_ic -")==0)
			$search="";
		if($search!="")
			$sqlsearch="and (uid='$search' or ic='$search' or name like '$search%')";
	
		$jobdiv=stripslashes($_REQUEST['jobdiv']);
		if($jobdiv!="")
			$sqljobdiv="and jobdiv='".addslashes($jobdiv)."'";
		
		$job=stripslashes($_REQUEST['job']);
		if($job!="")
			$sqljob="and job='".addslashes($job)."'";
		
			
		$jobsta=stripslashes($_REQUEST['jobsta']);
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$job=$_REQUEST['job'];
		if($div!="")
			$sqljob="and job='$job'";
			
		$jobsta=$_REQUEST['jobsta'];
		if($jobsta!="")
			$sqljobsta="and jobsta='$jobsta'";
		
		$tamat=$_REQUEST['tamat'];
		if($tamat=="")
			$tamat=0;
		$sqltamat="and status=$tamat";
		
			
			
		if($sid>0){
			$sql="select * from sch where id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
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
<title><?php echo $lg_staff;?></title>
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

</head>

<body>

 <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	 <input type="hidden" name="p" value="<?php echo $p;?>">
	 <input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	 <input name="order" type="hidden" id="order" value="<?php echo $order;?>">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
				<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
				<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
		</div> <!-- end mymenu -->
		<div align="right"  >
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		</div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

					   <select name="sid" id="sid" onChange="document.myform.submit();">
                <?php
			if($sid==""){
            	echo "<option value=\"\">- $lg_all -</option>";
				echo "<option value=\"0\">- All Access -</option>";
      		}else if($sid=="0"){
            	echo "<option value=\"0\">- All Access -</option>";
				echo "<option value=\"\">- $lg_all -</option>";
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
					echo "<option value=\"\">- $lg_all -</option>";
					echo "<option value=\"0\">- All Access -</option>";
				}
			}				
			  
			
?>
              </select>
			  <select name="jobdiv" id="jobdiv" onChange="document.myform.submit();">
<?php	
      		if($jobdiv=="")
            	echo "<option value=\"\">- $lg_select -</option>";
			else
                echo "<option value=\"$jobdiv\">$jobdiv</option>";
			$xjobdiv=addslashes($jobdiv);
			$sql="select * from type where grp='jobdiv' and prm!='$xjobdiv'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
            	$x=stripslashes($row['prm']);
                echo "<option value=\"$x\">$x</option>";
            }
           echo "<option value=\"\">- $lg_all -</option>";			  
			
?>
  </select>
				<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- $lg_id_name_ic -"; else echo "$search";?>">                
				
				<div style="display:inline; margin:0px 0px 0px -20px; padding:2px 2px 1px 1px; cursor:pointer" 
                	onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				<input type="submit" name="Submit" value="<?php echo $lg_search;?> ..">
				
				<input type="checkbox" name="tamat" value="1" onClick="document.myform.submit();" <?php if($tamat) echo "checked";?>>
				<?php echo "Staff Yang Berhenti Saja";?>
</div>

<div id="story">


<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo strtoupper($lg_search_for);?> : '<?php echo $search;?>'</div><?php }?>
<div id="mytitle2"><?php echo "$lg_access_log";?></div>

<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
         	<td class="mytableheader" style="border-right:none;" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_staff_id);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="20%">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="20%">&nbsp;<a href="#" onClick="formsort('job','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_position);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="5%">&nbsp;<a href="#" onClick="formsort('syslevel','<?php echo "$nextdirection";?>')" title="Sort">
            <?php echo strtoupper($lg_system_level);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('status','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_status);?></a></td>
			<td class="mytableheader" style="border-right:none;" width="10%" align="center"><a href="#" onClick="formsort('ll','<?php echo "$nextdirection";?>')" title="Sort">
            <?php echo strtoupper($lg_last_login);?></a></td>
			<td class="mytableheader" width="5%" align="center"><?php echo strtoupper($lg_hit);?></td>
	</tr>
	<?php	
			
	$sql="select * from usr where sch_id=$sid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$sql="select * from usr where id>0 $sqlsid $sqljobdiv $sqljob $sqljobsta $sqltamat $sqlsearch $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
			$uid=$row['uid'];
			$name=strtoupper($row['name']);
			$name=stripslashes($name);
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$ll=$row['ll'];
			$ll=strtok($ll," ");
			$xjob=$row['job'];
			$xjobsta=$row['jobsta'];
			$status=$row['status'];
			
			if($status==0)
				$status="ACTIVATE";
			else
				$status="ENDED";
			$xjobdiv=$row['jobdiv'];
			$syslevel=$row['syslevel'];
			$sysaccess=$row['sysaccess'];
			
			$year=date('Y');
			$sql="select count(distinct(dt)) from sys_log where uid='$uid' and dt like '$year%'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
  			$row2=mysql_fetch_row($res2);
			$syscount=$row2[0];
			 
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
			<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;"><a href="../estaff/usr_info.php?uid=<?php echo $uid;?>" class="fbbig" target="_blank"><?php echo "$name";?></a></td>
			  <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$xjob";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$syslevel";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$status";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$ll";?></td>
			  <td class="myborder" style="border-top:none;" align="center"><?php echo "$syscount";?></td>
            </tr>

<?php }  ?>
 </table>

</div><!-- story -->
</div><!-- content -->
</form>	
</body>
</html>
