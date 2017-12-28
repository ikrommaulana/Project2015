<?php
//160910 - update gui
//120305 - update cls base on ses_cls. Not base on cls
$vmod="v6.0.0";
$vdate="120305";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN');

		$username = $_SESSION['username'];
		$id=$_REQUEST['id'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];



$tahun=$_POST['tahun'];
if($tahun==""){
		$tahun=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$tahun=$tahun-1;
		$xx=$tahun+1;
		$sestahun="$tahun/$xx";	
	
}else{
		$sestahun="$tahun";
}

$tahun=$sestahun;

$year=$_POST['year'];
if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
		$xx=$year+1;
		$sesyear="$year/$xx";
		
	
}else{
		$sesyear="$year";
}
$year=$sesyear;
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- ID, IC, Name -")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '$search%')";
			$sqlsearch2 = "and (stu_uid='$search' or stu_ic='$search' or stu_name like '$search%')";
			$search=stripslashes($search);
		}
			

		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and cls_code='$clscode'";
			//$sql="select * from cls where sch_id='$sid' and code='$clscode'";
			$sql="select * from ses_cls where sch_id='$sid' and cls_code='$clscode' and year='$tahun'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clsname=stripslashes($row['cls_name']);
		}
		$setallcode=$_REQUEST['setallcode'];
		if($setallcode!=""){
			$sql="select * from cls where sch_id=$sid and code='$setallcode' and year='$year'";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$setallname=stripslashes($row2['name']);
			if($setallcode==-1)
				$setallname="- $lg_end $lg_school -";
			if($setallcode==-2)
				$setallname="- Change School -";
			if($setallcode==-3)
				$setallname="- Terminate School -";
			
		}
						
		if($id!=""){
			$sql="select * from usr where id='$id'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sid=$row['sch_id'];
            mysql_free_result($res);					  
		}
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$issemester=$row['issemester'];
			$startsemester=$row['startsemester'];
            mysql_free_result($res);					  
		}
		
		$del=$_POST['del'];
		$op=$_POST['op'];
	
		if($op=="delete"){
			if (count($del)>0) {
				for ($i=0; $i<count($del); $i++) {
					$sql="delete from ses_stu where id=$del[$i]";
					mysql_query($sql)or die("$sql query failed:".mysql_error());
				}
			}
			$f="<font color=blue>&lt;Successfully update&gt;</font>";
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
	if($sort=="")
		$sqlsort="order by stu.id $order";
	else
		$sqlsort="order by $sort $order, name asc";

		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function process_form(op){	
	var ret="";
	var cflag=false;
	if(op=='update'){	
			ret = confirm("Save the configuration??");
			if (ret == true){
				document.myform.p.value='../ses/ses_stu_change_cls_save';
				document.myform.submit();
			}
	}
	else if(op=='delete'){
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
				alert('Please checked delete the student class');
				return;
			}
			ret = confirm("Are you sure want to delete this student class ??");
			if (ret == true){
				document.myform.op.value=op;
				document.myform.submit();
			}
			return;
	}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $syscode;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>


<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
<input name="op" type="hidden">


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" id="mymenuitem" onClick="process_form('update')" title="Save this setting"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" id="mymenuitem" onClick="process_form('delete')" title="Remove this student from this class"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" id="mymenuitem" onClick="document.myform.submit()" ><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right">
		 <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
    </div>
</div><!-- end mypanel -->

<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">
			 
			    <select name="sid" id="sid" onchange="document.myform.setallcode[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' and id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}					    
			
?>
              </select>
			<select name="tahun" id="tahun" onchange="document.myform.submit();">
<?php
			$cyear=date('Y');
			$lastyear=$cyear-1;
			$nextyear=$cyear+1;
            echo "<option value=$tahun>$lg_year $sestahun</option>";
			$sql="select * from type where grp='session' and prm!='$tahun' order by val desc";
            $res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
 
						echo "<option value=\"$s\">$lg_year $s</option>";
            }
            mysql_free_result($res);					  

			?>
                </select>
			     <select name="clscode" id="clscode" onchange="document.myform.setallcode[0].value='';document.myform.submit();" >
                   <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_all $lg_student -</option>";
						echo "<option value=\"0\">- $lg_no_registered -</option>";
					}
					elseif($clscode=="0"){
						echo "<option value=\"0\">- $lg_none $lg_class -</option>";
						echo "<option value=\"\">- $lg_all $lg_student -</option>";
					}
					else{
						echo "<option value=\"$clscode\">$clsname</option>";
						echo "<option value=\"0\">- $lg_none $lg_class -</option>";
						echo "<option value=\"\">- $lg_all $lg_student -</option>";
					}
					//$sql="select * from cls where code!='$clscode' and sch_id=$sid order by level";
					$sql="select * from ses_cls where cls_code!='$clscode' and sch_id=$sid and year='$tahun' order by cls_level";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$a=stripslashes($row['cls_name']);
						$b=$row['cls_code'];
						echo "<option value=\"$b\">$a</option>";
					}			
			?>
                 </select>
			    <input name="search" type="text" id="search"  onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- ID, IC, Name -"; else echo "$search";?>">                
                <input type="submit" name="Button" value="<?php echo $lg_view; ?>">
          
            	<select name="year" id="year" onchange="document.myform.setallcode[0].value='';document.myform.submit();" style="background-color:#FFFF99; font-weight:bold">
                <?php
            echo "<option value=$sesyear>$lg_move_year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$s</option>";
            }
            mysql_free_result($res);					  

			?>
              </select>
         </div>
		 
<div id="story">
<div id="mytitle2"><?php echo $lg_student_class_placement;?> :  <?php echo $clsname;?> <?php echo $f;?></div>

<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
			  <td class="mytableheader" style="border-right:none;" width="1%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
              <td class="mytableheader" style="border-right:none;" align="center" width="1%"><?php echo strtoupper($lg_no);?></td>
              <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></td>
	      <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('nisn','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric2);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="1%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
              <td class="mytableheader" style="border-right:none;" width="30%" align="center"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="8%"><?php echo strtoupper("$lg_birth_no / Passport $lg_student");?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="10%"><a href="#" onClick="formsort('status','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_status);?> SISWA</a></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="8%"><?php echo strtoupper($lg_date_register);?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="8%"><?php echo strtoupper($lg_date_end);?></td>
			  <td class="mytableheader" style="border-right:none;" width="15%" align="center">&nbsp;<?php echo strtoupper($lg_class);?> <?php echo "$sestahun"?></td>
			  <td class="mytableheader" style="border-right:none;" align="center" width="20%">
					<select name="setallcode" onchange="document.myform.submit();" style="width:100% ">
					  <?php 
						if($setallcode=="")
							echo "<option value=\"\">- $lg_move $lg_all Ke -</option>";
						else
							echo "<option value=\"$setallcode\">$setallname</option>";
						$sql="select * from cls where code!='$setallcode' and sch_id=$sid and year='$year' order by level";
						$res2=mysql_query($sql)or die("query failed:".mysql_error());
						while($row2=mysql_fetch_assoc($res2)){
									$a=stripslashes($row2['name']);
									$b=$row2['code'];
									echo "<option value=\"$b\">$a</option>";
						}
						//echo "<option value=\"-1|$uid\">- $lg_end $lg_school -</option>";
						//echo "<option value=\"-2|$uid\">- Change School -</option>";
						//echo "<option value=\"-3|$uid\">- Terminate School -</option>";
						echo "<option value=\"\">- $lg_cancel -</option>";
					  ?>
					  </select>
			  </td>	
            </tr>


	<?php
	if($clscode==""){
		$sqlstatus=" and stu.status=6";
		//$sql="select count(*) from stu where sch_id=$sid $sqlstatus $sqlsearch $sqlsort $sqlmaxline";
		$sql="select count(*) from stu where sch_id=$sid $sqlstatus $sqlsearch $sqlsort";
	}elseif($clscode=="0"){
		$sqlstatus=" and stu.status=6";
		//$sql="select count(*) from stu where uid NOT IN (select stu_uid from ses_stu where year='$tahun') and sch_id=$sid $sqlstatus $sqlsearch $sqlsort $sqlmaxline";
		$sql="select count(*) from stu where uid NOT IN (select stu_uid from ses_stu where year='$tahun') and sch_id=$sid $sqlstatus $sqlsearch $sqlsort";

	}else{	
		$sqlstatus=" and stu.status=6";
		//$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlstatus and year='$tahun' $sqlclscode $sqlsearch $sqlsort $sqlmaxline";
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlstatus and year='$tahun' $sqlclscode $sqlsearch $sqlsort";
	}
	
	//echo "$sql<br>";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;	
			
  	if($clscode==""){
		$sql="select * from stu where sch_id=$sid $sqlstatus $sqlsearch $sqlsort  $sqlmaxline";
	}elseif($clscode=="0"){
		$sqlstatus=" and stu.status=6";
		$sql="select * from stu where uid NOT IN (select stu_uid from ses_stu where year='$tahun') and sch_id=$sid $sqlstatus $sqlsearch $sqlsort $sqlmaxline";
	}else{
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlstatus and year='$tahun' $sqlclscode $sqlsearch $sqlsort $sqlmaxline";
	}//echo $sql;
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$nisn=$row['nisn'];
		$ic=$row['ic'];
		$sex=$row['sex'];
		$sta=$row['status'];
		$tamat=$row['edate'];
		$daftar=$row['rdate'];
		$name=strtoupper(stripslashes($row['name']));
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
			
		$sql="select * from type where grp='stusta' and val='$sta'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
					$status=$row2['prm'];
		}
		
		$cname=$lg_no_registered;$xid=0;
		$kk=0;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$tahun' and sch_id=$sid";
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

?>

            <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			  <td class="myborder" style="border-right:none; border-top:none;"><input type=checkbox name=del[] value="<?php echo "$xid";?>" onClick="check(0)"></td>
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
              <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
	      <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$nisn";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $lg_sexmf[$sex]; ?></td>
              <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$ic";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$status";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$daftar";?></td>
			  <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$tamat";?></td>
 			  <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$cname";?></td>				
			   <td class="myborder" style="border-right:none; border-top:none;">
				  <select name="usr[]" id="usr[]" style="width:100% ">
				  <?php 
				  
					if($setallcode=="")
						echo "<option value=\"\">- $lg_move_student -</option>";
					else{
						echo "<option value=\"$setallcode|$uid\">$setallname</option>";
					}
					$sql="select * from cls where code!='$setallcode' and sch_id=$sid and year='$year' order by level";
					$res2=mysql_query($sql)or die("query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
						$a=stripslashes($row2['name']);
						$b=$row2['code'];
						echo "<option value=\"$b|$uid\">$a</option>";
					}
					//echo "<option value=\"-1|$uid\">- $lg_end $lg_school -</option>";
					//echo "<option value=\"-2|$uid\">- Change School -</option>";
					//echo "<option value=\"-3|$uid\">- Terminate School -</option>";
					echo "<option value=\"\">- $lg_cancel -</option>";

				  ?>
				  </select>
			  </td>
            </tr>



  <?php }   ?>
  	<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><input type="button" name="Button" value="<?php echo $lg_update;?>" onClick="return process_form('update')" style="width:100% "></td>
	</tr>
        </table>  

	<?php include("../inc/paging.php");?>
</div>
</div>
		  </form>
</body>
</html>
<!-- 090114 --> 