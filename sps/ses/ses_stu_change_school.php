<?php
//160910 5.0.0 - update gui
$vmod="v6.0.0";
$vdate="110729";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK');
$username = $_SESSION['username'];
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
		}
				
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}

		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
	    $sname=stripcslashes($sname);
			$ssname=$row['sname'];
			$issemester=$row['issemester'];
			$startsemester=$row['startsemester'];
            mysql_free_result($res);					  
		}
		$year=$_REQUEST['year'];
		if($year==""){
			$year=date('Y');
			/*if(($issemester)&&(date('n')<$startsemester))
				$year=$year-1;
				*/
		}
		/*if($issemester){
			$xx=$year+1;
			$sesyear="$year/$xx"; 
		}else{
			$sesyear="$year";
		}
		echo $sesyear;*/
		
		$allsetid=$_POST['allset'];
		if($allsetid!=""){
			if($allsetid=="-1"){
				$allsetname="- $lg_end $lg_school -";
			}else{
				$sql="select * from sch where id=$allsetid";
				$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$allsetname=$row2['sname'];
			}
		}
		
		
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
	
	//echo $curr;
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
	echo $sort;
	if($sort=="")
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, stu.name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function process_form(operation){		
		ret = confirm("<?php echo $lg_confirm_save;?>");
		if (ret == true){
			document.myform.p.value='../ses/ses_stu_change_school_save';
			document.myform.submit();
		}
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">

<div id="content">
<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="#" onClick="return process_form()"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div>
		<div id="viewpanel" align="right">
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>

			  <select name="sid" id="sid" onChange="document.myform.allset[0].value='';document.myform.clscode[0].value='';document.myform.year[0].value='';document.myform.search.value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
		//else
                //echo "<option value=$sid>$ssname</option>";
				
			//if($_SESSION['sid']==0){
				$sql="select * from sch where id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$s=stripcslashes($s);
							$t=$row['id'];
							if($sid==$t){$selected="selected";}else{$selected="";}
							echo "<option value=\"$t\" $selected>$s</option>";
				}
			//}							  
?>
              </select>
			  <select name="year" id="year" onChange="document.myform.submit();">
				<?php
					//echo "<option value=$year>$lg_year $sesyear</option>";
					//$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$sql="select * from type where grp='session' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						if($s==$year){$selected="selected";}else{$selected="";}
						echo "<option value=\"$s\" $selected>$lg_year $s</option>";
					}
					mysql_free_result($res);					  
				?>
          	 </select>
			 <select name="clscode" id="clscode" onChange="document.myform.search.value='';document.myform.submit();">
                  <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
						$sql="select * from cls where sch_id=$sid order by level";
					}
					else{
						echo "<option value=\"$clscode\">$clsname</option>";
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
						mysql_free_result($res);
						$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
					}
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['name']);
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
            		mysql_free_result($res);	

			?>
                </select>
				
				<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>"> 
				
                <input type="submit" name="Submit" value="Cari Data"  >
				
</div><!-- end viewpanel-->

</div><!-- end mypanel-->
<div id="story">

<div id="mytitle2">
<?php echo $sname;?> - <?php echo "$studentstatus $year";?> -
<?php echo "$x1 $x2 $x3 $x4 $x5";?>
</div>
<table width="100%" cellspacing="0">
  <tr>
			 
			  <td class="mytableheader" style="border-right:none;" width="5%" align=center><?php echo strtoupper($lg_no);?></td>
              <td class="mytableheader" style="border-right:none;" width="10%" align=center><?php echo strtoupper($lg_matric);?></td>
			  <td class="mytableheader" style="border-right:none;" width="2%" align="center"><?php echo strtoupper($lg_mf);?></td>
              <td class="mytableheader" style="border-right:none;" width="35%"><?php echo strtoupper($lg_name);?></td>
			  <td class="mytableheader" style="border-right:none;" width="15%">&nbsp;<?php echo strtoupper($lg_class);?> <?php echo $year;?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($lg_date_register);?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($lg_date_end);?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><?php echo strtoupper($lg_status);?></td>
			  <td class="mytableheader" style="border-right:none;" width="15%" align="center">
				 <select name="allset" onChange="document.myform.submit();" style="width:100% ">
				  <?php
					if($allsetid!="")
						echo "<option value=\"$allsetid\">$allsetname</option>";
					else
						echo "<option value=\"\">- $lg_move $lg_all -</option>";
	
					$sql="select * from sch where id!=$sid";
					$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
								$xname=$row2['sname'];
								$xname=stripcslashes($xname);
								$xid=$row2['id'];
								echo "<option value=\"$xid\">$xname</option>";
					}
					$sqlstusta="select * from type where grp='stusta' and val!=6 order by val asc";
					$resstusta=mysql_query($sqlstusta)or die("$sqlstusta query failed:".mysql_error());
						while($rowstusta=mysql_fetch_assoc($resstusta)){
								$prmsta=$rowstusta['prm'];
								$prmval=$rowstusta['val'];
						echo "<option value=\"-$prmval|$uid\">- $prmsta -</option>";
					}
					//echo "<option value=\"-1\">- $lg_end $lg_school -</option>";
					echo "<option value=\"\">- $lg_cancel -</option>";
					mysql_free_result($res2);	
				  ?>
				  </select>
			  </td> 
            </tr>
	<?php	

	if($clscode=="")
    	$sql="select count(*) from stu where sch_id=$sid $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclscode $sqlsearch and year='$year'";
	
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if($clsname=="")
		$sql="select * from stu where sch_id=$sid $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclscode $sqlsearch and year='$year' order by name asc limit $curr,$MAXLINE";
	
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper(stripslashes($row['name']));
		$status=$row['status'];
		$sex=$row['sex'];
		$rdate=$row['rdate'];
		$edate=$row['edatr'];
			
		$cname=$lg_none;
		$kk=0;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$cn=stripslashes($row2['cls_name']);
			$clslevel=$row2['cls_level'];
			$y=$row2['year'];
			$xid=$row2['id'];
			if($kk>0)
				$cname=$cname.",".$cn;
			else
				$cname=$cn;
			$kk++;
		}
			
		$sql="select * from type where grp='stusta' and val='$status'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2))
			$sta=$row2['prm'];
			
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td class="myborder" style="border-right:none; border-top:none;" align=center><?php echo "$q";?></td>
	   	<td class="myborder" style="border-right:none; border-top:none;" align=center><?php echo "$uid";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $lg_sexmf[$sex]; ?></td>
 		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
		<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$cname";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align=center><?php echo "$rdate";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align=center><?php echo "$edate";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align=center><?php echo "$sta";?></td>
		<td class="myborder" style="border-right:none; border-top:none;" align=center><select name="stu[]" id="stu[]" style="width:100%">
		<?php 
				if($allsetid!=""){
					echo "<option value=\"$allsetid|$uid\">$allsetname</option>";
					echo "<option value=\"\">- UNSET -</option>";
				}
				else
				  	echo "<option value=\"\">- Set $lg_school -</option>";

				$sql="select * from sch where id!=$sid";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
							$xname=$row2['sname'];
							$xname=stripcslashes($xname);
							$xid=$row2['id'];
							echo "<option value=\"$xid|$uid\">$xname</option>";
				}
				$sqlstusta="select * from type where grp='stusta' and val!=6 order by val asc";
				$resstusta=mysql_query($sqlstusta)or die("$sqlstusta query failed:".mysql_error());
				while($rowstusta=mysql_fetch_assoc($resstusta)){
					$prmsta=$rowstusta['prm'];
					$prmval=$rowstusta['val'];
					echo "<option value=\"-$prmval|$uid\">- $prmsta -</option>";
				}
				//echo "<option value=\"-1|$uid\">- $lg_end $lg_school -</option>";
		?>
		
	 	</select>
			</td>   
		</tr>
<?php  } ?>
     </table>  
<div align="right"><input type="button" name="Button" value="<?php echo strtoupper($lg_update);?>" onClick="return process_form()" style="width:200px "></div>        
	 
	<?php include("../inc/paging.php");?>
</div></div>

	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form> <!-- end myform -->


</body>
</html>
