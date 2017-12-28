<?php

//160910 5.0.0 - update gui.. 

$vmod="v6.0.1";

$vdate="110718";

include_once('../etc/db.php');

include_once('../etc/session.php');

include_once("$MYLIB/inc/language_$LG.php");

verify('AKADEMIK|GURU|ADMIN');

$username = $_SESSION['username'];

$searchall=$_REQUEST['searchall'];



		$sid=$_REQUEST['sid'];
		

		if($sid=="")

			$sid=$_SESSION['sid'];
if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$ssname=stripslashes($row['sname']);
		$slevel=$row['level'];
		$slvl=$row['lvl'];
		$simg=$row['img'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
		            $issemester=$row['issemester'];	
			$startsemester=$row['startsemester'];
     	mysql_free_result($res);					  
	}

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
		
			
		$sub=$_REQUEST['sub'];
		$subj=$sub;
		
		if($sub!='')
		{
		$sqlsub="and sub='$sub'";
		}	

		$clslevel=$_REQUEST['clslevel'];
		

		if($clslevel!=""){

			$sqlclslevel="and cls_level='$clslevel'";
			$sqllessonclslevel=" and elesson.level='$clslevel'";
			$sqlsortcls=",cls_name asc";

		}

		$clscode=$_REQUEST['clscode'];
		

		if($clscode!=""){
			$sqlcode = "$clscode";
			
			$sqllessonclscode=" and elesson.cls='$clscode'";

			$sqlclscode="and ses_stu.cls_code='$clscode'";

			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year='$year'";

			$res=mysql_query($sql)or die("query failed:".mysql_error());

            $row=mysql_fetch_assoc($res);

            $clsname=stripslashes($row['cls_name']);

		}

		

		$search1=$_REQUEST['dtfrom'];
	    $search2=$_REQUEST['dtto'];
		if($search1!="" && $search2!="")
		$sqlsearch="and dt between '$search1' and '$search2'";

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

		$sqlsort="order by stu.id $order";

	else

		$sqlsort="order by $sort $order, name asc";





?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>SPS</title>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<!--datetime picker-->
<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>



<!-- SETTING GRAY BOX -->

<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>

<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>

<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>

<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>

<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />



<SCRIPT LANGUAGE="JavaScript">
function processform(operation){
				
				/*if(document.myform.clslevel.value==""){
					alert("Please select the level");
					document.myform.clslevel.focus();
					return;
				}
				if(document.myform.clscode.value==""){
					alert("Please select the class");
					document.myform.clscode.focus();
					return;
				}
				
				if(document.myform.dtfrom.value==""){
					alert("Please select the date and make sure the level and class not empty");
					document.myform.dtfrom.focus();
					return;
				}
				if(document.myform.dtto.value==""){
					alert("Please select the date make sure the level and class not empty");
					document.myform.dtto.focus();
					return;
				}
				*/
				document.myform.submit();
				
}
</script>

</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

	<input type="hidden" name="p" value="<?php echo $p;?>">
    <input type="hidden" name="clscode" value="<?php echo $clscode;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
    <input type="hidden" name="clslevel" value="<?php echo $clslevel; ?>">
    <input type="hidden" name="sub" value="<?php echo $sub;?>">

<div id="content">

<div id="mypanel">

	<div id="mymenu" align="center">

		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>

					<div id="mymenu_space">&nbsp;&nbsp;</div>

					<div id="mymenu_seperator"></div>

					<div id="mymenu_space">&nbsp;&nbsp;</div>

		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>

	</div>

	<div align="right">

		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>

	</div> <!-- right -->

</div><!-- end mypanel-->

<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">


		  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.clslevel[0].value='';document.myform.sub[0].value='';document.myform.submit();">

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

?>

              </select>

			<select name="year" id="year" onChange="document.myform.submit();">
				<?php
					echo "<option value=$year>$year</option>";
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
              
	<select name="clslevel" onChange="document.myform.clscode[0].value=''; document.myform.submit();">

<?php    

		if($clslevel=="")

            echo "<option value=\"\">- $lg_level -</option>";

		else

			echo "<option value=$clslevel>$lg_level $clslevel</option>";

			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";

            $res=mysql_query($sql)or die("query failed:".mysql_error());

            while($row=mysql_fetch_assoc($res)){

                        $s=$row['prm'];

                        echo "<option value=$s>$lg_level $s</option>";

            }

		if($clslevel!="")

            echo "<option value=\"\">- $lg_all -</option>";



?>

      </select>


			 <select name="clscode" id="clscode" onchange="document.myform.submit();">

                  <?php	

      				if($clscode=="")

						echo "<option value=\"\">- $lg_class -</option>";

					else

						echo "<option value=\"$clscode\">$clsname</option>";

					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year='$year' $sqlclslevel order by cls_level";

            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());

           		 	while($row=mysql_fetch_assoc($res)){

                        $b=stripslashes($row['cls_name']);

						$a=$row['cls_code'];

                        echo "<option value=\"$a\">$b</option>";

            		}

					if($clscode!="")

            			echo "<option value=\"\">- $lg_all -</option>";

			?>
         

                </select>
                <select name="sub" id="sub" onchange="document.myform.submit();">
				<?php	
							if($sub=="")
								echo "<option value=\"\">- Pilih Subjek -</option>";
							else
								
								 echo "<option value=\"$sub\">$subname</option>";
								 
								 if($clslevel!=''){
								 $sqlsublist="select * from sub where level='$clslevel'";
									$ressublist=mysql_query($sqlsublist)or die("query failed:".mysql_error());
									while($row=mysql_fetch_assoc($ressublist)){
									$subname=$row['name'];
									$sub=$row['code'];
									
									echo "<option value=\"$sub\">$subname</option>";
									}}
								
							mysql_free_result($res);
					?>
			  </select>
             
             <input  type="text" name="dtfrom" size="15" readonly onClick="displayDatePicker('dtfrom');" value="<?php echo $search1;?>">
             <div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.dtfrom.value='';document.myform.dtfrom.focus();" 

					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">

					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">

					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">

				</div>
                
             <input type="text" name="dtto" size="15" readonly onClick="displayDatePicker('dtto');" value="<?php echo $search2; ?>">
             <div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.dtto.value='';document.myform.dtto.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
				
              <!--<input type="submit" name="Submit" value="<?php echo $lg_view;?> .." style="font-weight:bold;color:#0066CC;">-->
              <input type="button" name="Submit" value="<?php echo $lg_view;?> .." style="font-weight:bold;color:#0066CC;" onClick="processform()" >
						

			<?php } ?>
			

</div> <!-- right -->

<div id="story">



<?php /*if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo $lg_search_for;?>... <?php echo $search;?></div><?php }*/?>



<div id="mytitle2">

<?php echo "Lesson Plan Report"." / ".$sname;?>&nbsp;

<?php 

	if($clscode!="")

		echo  " - $clsname";

	elseif($clslevel!="")

		echo " - $namatahap $clslevel";

?>
<br>
</div>
<div id="mytitle">
Subject : <?php echo $subj;?><br>

<?php echo "Date From : ".$search1." "."Until : ".$search2; ?>


</div>
<table width="100%" cellpadding="2" cellspacing="0" >
  <tr>
    <td id="mytabletitle" width="2%" align="center">NO</td>
    <td id="mytabletitle" width="6%" align="center">TANGGAL</td>
	<td id="mytabletitle" width="6%" align="center">KELAS</td>
	<td id="mytabletitle" width="6%" align="center">SUBJECT</td>
    <td id="mytabletitle" width="6%" align="center">STAFF ID</td>	
    <td id="mytabletitle" width="16%" align="center" >NAMA</td>
	<td id="mytabletitle" width="13%" align="center">JUDUL</td>
	<td id="mytabletitle" width="18%" align="center">OBJEKTIF</td>
    <td id="mytabletitle" width="15%" align="center">KEMAHIRAN</td>
    <td id="mytabletitle" width="5%" align="center">NILAI</td>
    <td id="mytabletitle" width="13%" align="center">BAHAN BANTU MENGAJAR</td>

  </tr>
<?php
if($clscode=="" && $clslevel==""){
$sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid where elesson.year='$year' and elesson.sid=$sid order by dt desc";
}else{ 
	$sql="select elesson.*,usr.name from elesson INNER JOIN usr ON elesson.uid=usr.uid where elesson.year='$year' and elesson.sid=$sid $sqllessonclscode $sqllessonclslevel $sqlsub $sqlsearch order by dt desc ";
}
//echo "$sql<br>";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$subject=$row['subname'];
		$cls=$row['cls'];
		
		$sql2="select * from ses_cls where sch_id=$sid and cls_code='$cls' and year='$year'";
		$res2=mysql_query($sql2)or die("$sql2 query failed:".mysql_error());
        $row2=mysql_fetch_assoc($res2);
        $clsname=stripslashes($row2['cls_name']);

		$title=$row['title'];
		$obj=$row['obj'];
		$skill=$row['skill'];
		$nilai=$row['nilai'];
		$bahanbantu=$row['bahanbantu'];
		$date=$row['dt'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		
		if(($q++%2)==0)

			$bg="#FAFAFA";

		else

			$bg="#FFFFFF";

?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"><?php echo $q ; ?></td>
    <td  align="center" id="myborder"><?php echo $date ; ?></td>
	<td  id="myborder"><?php echo $clsname ; ?></td>
    <td  id="myborder"><?php echo $subject ; ?></td>
    <td  align="center" id="myborder"><?php echo $uid ; ?></td>
	<td  id="myborder"><?php echo $name ; ?></td>
  	<td  id="myborder"><?php echo $title ; ?></td>
    <td  id="myborder"><?php echo $obj ;?></td>
    <td  id="myborder"><?php echo $skill ;?></td>
    <td  id="myborder"><?php echo $nilai; ?></td>
    <td  id="myborder"><?php echo $bahanbantu ;?></td>

  </tr>
<?php  }?>
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