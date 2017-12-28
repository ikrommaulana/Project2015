<?php
//160910 5.0.0 - update gui.. data kementrian
$vmod="v5.0.0";
$vdate="241210";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];


		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
		}
		
		$tahap=$_POST['tahap'];
		if($tahap!=""){
			$sqltahap="and level='$tahap'";
			$sqlclslevel="and ses_stu.cls_level='$tahap'";
		}

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');

		$search=$_REQUEST['search'];
		if(strcasecmp($search,"-Matric-IC-Name-")==0)
			$search="";
		if($search!=""){
			//$search=addslashes($search);
			$sqlsearch = "and (stu.uid='$search' or stu.ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
		}
		
		$namatahap="Level";
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$namatahap=$row['clevel'];				  
		}
		
		$stustatus=$_REQUEST['stustatus'];
		if($stustatus==""){
			$stustatus = 6;
		}
		if($stustatus!="%"){
			$sqlstustatus="and status=$stustatus";
			$sql="select * from type where grp='stusta' and val='$stustatus'";
			$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$studentstatus=$row['prm'];
		}
		else
			$studentstatus="All Student";
			
		$op=$_REQUEST['op'];
		if($op=="save"){
			$des=$_REQUEST['des'];
			$stuid=$_REQUEST['uid'];
			if (count($des)>0) {
				for ($i=0; $i<count($des); $i++) {
					$data=$des[$i];
					$stu_id=$stuid[$i];
					
					//if($data=="")continue;
					
					$sql ="select * from koq_note where uid='$stu_id' and year=$year";
					$query = mysql_query($sql);
					if(mysql_num_rows($query) > 0) 
					  {
					
					   $sql2 = "UPDATE koq_note SET
					   note='$data' WHERE uid = '$stu_id' and year=$year"; 
					   mysql_query($sql2) or die(mysql_error());
					   $f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
					   }
							
					else
					  {
					   $sql3 = "INSERT INTO koq_note(uid,note,year) values('$stu_id','$data','$year')";
					   mysql_query($sql3) or die(mysql_error());
					   $f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
					  }
				}
			}
		}
		
		$id=$_POST['id'];
		if($id!=""){
        $sql4="select * from koq_note where uid='$id' and year=$year";
		$res4=mysql_query($sql4)or die("query failed:".mysql_error());     
	    $row4=mysql_fetch_assoc($res4);
		$note2=$row4['note'];
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
		$sqlsort="order by $sort $order, name asc";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
 <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="inc/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input:text").uniform();
      });
    </script>
    <link rel="stylesheet" href="inc/uniform.default.css" type="text/css" media="screen">-->

<script language="JavaScript">
function process_form(operation){		
		ret = confirm("Save the configuration??");
		if (ret == true){
			document.myform.op.value='save';
			document.myform.id.value='';
			document.myform.submit();
			
		}
		
}
</script>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
<!--
<style>
style tyle=text/css>
input.red {background-color: #cc0000; font-weight: bold; font-size: 12px; color: white;}
input.grey {background-color: #6960EC; font-size: 12px;}
input.pink {background-color: #ffcccc;}
input {font-size: 12px;}
input.violet {background-color: #ccccff;}
</style>
-->
</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="op">
     <input type="hidden" name="id" value="<?php echo $id;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
    <!--<a href="#" onClick="process_form('add')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>-->
</div>
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div align="right" class="printhidden">
      <select name="year" onChange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }				  
?>
    </select>			

	<select name="sid" id="sid" onChange="document.myform.clscode[0].value='';document.myform.tahap[0].value='';document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";			
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}							  
?>
              </select>
			  
			   <select name="tahap" id="tahap" onChange="document.myform.clscode[0].value='';document.myform.submit();">
               <?php    
		if($tahap=="")
            	echo "<option value=\"\">- $lg_all $lg_level -</option>";
		else
			echo "<option value=$tahap>$namatahap $tahap</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$tahap' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		if($tahap!="")
        	echo "<option value=\"\">- $lg_all $lg_level -</option>";
?>
		     </select>
			 <select name="clscode" id="clscode" onChange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' $sqltahap order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all $lg_class -</option>";

			?>
                </select>
      <input name="search" type="text" size="30" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "-Matric-IC-Name-"; else echo "$search";?>"> 
			<input type="submit" name="Submit" value="View">
</div>
<div id="story">


<div id="mytitlebg"> 
	<?php echo strtoupper("$lg_cocurriculum");?> - <?php echo strtoupper($sname);?> - <?php echo strtoupper("$studentstatus $year");?> <?php echo $f;?>
</div>
<table width="100%" cellspacing="0">
  <tr>
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_no");?></td>
              <td id="mytabletitle" width="3%" align="center"><?php echo "SHORTCUT";?></td>
			  <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_matric");?></a></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper("$lg_mf");?></a></td>
              <td id="mytabletitle" width="25%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper("$lg_name");?></a></td>
			  <td id="mytabletitle" width="8%"><?php echo strtoupper("$lg_class");?> <?php echo $year;?></td>
			  <td id="mytabletitle" width="3%"><?php echo strtoupper("$lg_mark");?></td>
              <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_grade");?></td>
               <td id="mytabletitle" width="40%" align="center"><?php echo strtoupper($lg_teacher_note);?></td>
            </tr>
	<?php 
	if(($clscode=="")&&($tahap==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year'";	
    $res=mysql_query($sql,$link)or die("$sql - failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];

	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	 if(($clscode=="")&&($tahap==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlisfakir $sqlsearch $sqlsort limit $curr,$MAXLINE";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlclslevel $sqlclscode $sqlstustatus $sqlsearch and ses_stu.year='$year' $sqlsort limit $curr,$MAXLINE";

	$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		$status=$row['status'];
		
		
		$cname=$lg_none;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
		}
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
				
		$sql10="select * from koq_stu where uid='$uid' and koq_type='10' and year=$year order by total_val  desc LIMIT 0,1";
				   $res10=mysql_query($sql10)or die("query failed:".mysql_error());     
					$row10=mysql_fetch_assoc($res10);
					$bonus=$row10['total_val'];
        //get the highest sukan
		$sql9="select * from koq_stu where uid='$uid' and koq_type='3' and year=$year order by total_val  desc LIMIT 0,1";
						$res9=mysql_query($sql9)or die("query failed:".mysql_error());
						$row9=mysql_fetch_assoc($res9);
						$total3=$row9['total_val'];
		//get the highest kelab
		$sql8="select * from koq_stu where uid='$uid' and koq_type='2' and year=$year order by total_val  desc LIMIT 0,1";
						$res8=mysql_query($sql8)or die("query failed:".mysql_error());
						$row8=mysql_fetch_assoc($res8);
						$total2=$row8['total_val'];
						
		//get the highest pakaian beragam
		$sql7="select * from koq_stu where uid='$uid' and koq_type='1' and year=$year order by total_val  desc LIMIT 0,1";
						$res7=mysql_query($sql7)or die("query failed:".mysql_error());
						$row7=mysql_fetch_assoc($res7);
						$total1=$row7['total_val'];


		$array = array($total1, $total2, $total3);
		natsort($array); // Sorts from highest to lowest
		$highest = $array[0];
		$second_highest = $array[1];
		$jumlah = ($highest + $second_highest)/2;
		$subtotal = $jumlah + $bonus ;
		// get grade
	if ( $subtotal<= 100 && $subtotal>= 80){$gred ='A';}
	if ( $subtotal<= 79 && $subtotal>= 60){$gred ='B';}
	if ( $subtotal<= 59 && $subtotal>= 40){$gred ='C';}
	if ( $subtotal<= 39 && $subtotal>= 20){$gred ='D';}
	if ( $subtotal<= 19){$gred ='E';}	
		
		
        $sql4="select * from koq_note where uid='$uid' and year=$year";
		$res4=mysql_query($sql4)or die("query failed:".mysql_error());     
	    $row4=mysql_fetch_assoc($res4);
		$note=$row4['note'];
		$xid=$row4['id'];
		
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id=myborder align=center><?php echo "$q";?></td>
        <td id=myborder align=center><a href="#" title="Edit" onClick="document.myform.id.value='<?php echo "$uid";?>';document.myform.submit();"><img src="../img/edit12.png"></a>
				<a href="../ekoq/slip_koku.php?<?php echo "uid=$uid&sid=$sid&y=$year";?>" target="_blank" title="Result" onClick="return GB_showPage('Ko-Q : <?php echo addslashes($name);?>',this.href)"><img src="../img/graph12.png"></a>
</td>
	 <td id=myborder align=center><?php echo "$uid";?></td>
		<td id=myborder align=center><?php echo $lg_sexmf[$sex];?></td>
		<td id=myborder><?php echo "$name";?></td>
		<td id=myborder><?php echo "$cname";?></td>
		<td id=myborder  align=center><?php echo "$subtotal";?></td>
          <td id=myborder  align=center><?php echo addslashes($gred);?></td>
        <td id=myborder>
         <div id="panelform2[]" style="display:<?php if($id=="") echo "block";if($id=="$uid") echo "none";// else echo "none";?>"><a href="#" onClick="document.myform.id.value='<?php echo "$uid";?>';document.myform.submit();"><?php echo "$note" ;?></a>
             
         </div>
         <div id="panelform[]" style="display:<?php if($id=="$uid") echo "block";if($id=="") echo "none"; else echo "none";?>">  
        <input  type="text" name="des[]" size="71" value ="<?php echo "$note";?>"> <input type="submit" name="Submit" value="Update" onClick="process_form('add')" >
         </div>
         <input type="hidden" name="uid[]" value="<?php echo "$uid";?>">
		</td>
		</tr>
<?php }  ?>
</table>          
	 
	<?php include("../inc/paging.php");?>
</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
v2.7
22/11/2008	: update sesi listing
Author		: razali212@yahoo.com
 -->