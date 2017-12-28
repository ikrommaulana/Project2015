<?php
//160910 5.0.0 - update gui.. 
//110724 - update link.. 
$vmod="v6.0.0";
$vdate="110724";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR');
$username = $_SESSION['username'];
$searchall=$_REQUEST['searchall'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

	if($sid==0)
		$sql="select * from sch";
	else
		$sql="select * from sch where id=$sid";
	
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$sid=$row['id'];
    $sname=$row['name'];
	$ssname=$row['sname'];
	$simg=$row['img'];
	$namatahap=$row['clevel'];	
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
	
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
		
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqlsortcls=",cls_name asc";
		}
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year='$year'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
		}
		
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			if($STU_SEARCH_MOTHER)
				$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%' or p2name like '%$search%')";
			else
				$sqlsearch = "and (uid='$search' or ic='$search' or p1ic='$search' or p2ic='$search' or name like '%$search%')";
			
			
			$search=stripslashes($search);
		}

		
		
		$stustatus=$_REQUEST['stustatus'];
		if($stustatus==""){
			$stustatus = 6;
		}
		if($stustatus!="%"){
			$sqlstustatus="and status=$stustatus";
			$sql="select * from type where grp='stusta' and val='$stustatus'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$studentstatus=$row['prm'];
		}
		else
			$studentstatus="$lg_all $lg_student";
		
		$sponser=$_REQUEST['sponser'];
		if($sponser!=""){
				$sqlsponser="and sponser='$sponser'";
		}
		if($searchall)
			$sqlstustatus="";
			
		$isyatim=$_REQUEST['isyatim'];
		if($isyatim!=""){
			$sqlisyatim="and isyatim=1";
			$x1="- $lg_orphan";
		}
		
		$isinter=$_REQUEST['isinter'];
		if($isinter!=""){
			$sqlisinter="and isinter=1";
			$x1="- $lg_international";
		}
			
		$isstaff=$_REQUEST['isstaff'];
		if($isstaff!=""){
			$sqlisstaff="and isstaff=1";
			$x2="- $lg_staff_child";
		}
			
		$iskawasan=$_REQUEST['iskawasan'];
		if($iskawasan!=""){
			$sqliskawasan="and iskawasan=1";
			$x3="- $lg_kariah";
		}
		$ishostel=$_REQUEST['ishostel'];
		if($ishostel!=""){
			$sqlishostel="and ishostel=1";
			$x4="$lg_hostel";
		}

		$isfakir=$_REQUEST['isfakir'];
		if($isfakir!=""){
			$sqlisfakir="and isfakir=1";
			$x5="$lg_fakir";
		}
		$isblock=$_REQUEST['isblock'];
		if($isblock!=""){
			$sqlisblock="and isblock=1";
			$x5="- Blocked";
		}
		$isislah=$_REQUEST['isislah'];
		if($isislah!=""){
			$sqlisislah="and isislah=1";
			$x6="$lg_xprimary";
		}
		$view=$_POST['view'];
		
		$sql="select * from ses_cls where usr_uid='$username' and year='$year' and sch_id='$sid' and cls_code='$clscode'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$gurukelas=mysql_num_rows($res);

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

<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<SCRIPT LANGUAGE="JavaScript">
function clear_check(){
	for (var i=0;i<document.myform.elements.length;i++){
		var e=document.myform.elements[i];
        if ((e.type=='checkbox')&&(e.id=='view')){
			e.checked=false;
        }
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
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('panelform');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/listview22.png"><br><?php echo $lg_option;?></a>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
					<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
	</div> <!-- right -->
</div><!-- end mypanel-->
<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

	<?php if((is_verify("ROOT|ADMIN|AKADEMIK|KEWANGAN|HR|CEO"))||($SHOW_EPELAJAR_TO_ALL_TEACHER)){?>
		  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.submit();">
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
			<select name="year" id="year" onchange="document.myform.submit();">
			<?php
					echo "<option value=$year>$lg_year $sesyear</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								$v=$row['val'];

								echo "<option value=\"$s\">$lg_year $s</option>";
					}			  
			?>
          </select>

			 <select name="clscode" id="clscode" onchange="document.myform.submit();">
                  <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year='$year' order by cls_level";
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
				<input name="search" type="text"  id="search" size="25" <?php if($search==""){?>onClick="document.myform.search.value='';"<?php } ?>
					value="<?php if($search=="") echo "- $lg_name_matrik_ic -"; else echo "$search";?>"> 
				
				<div style="display:inline; margin:0px 0px 0px -17px; padding:2px 2px 1px 1px; cursor:pointer" onClick="document.myform.search.value='';document.myform.search.focus();" 
					onMouseOver="showhide2('img6','img5');" onMouseOut="showhide2('img5','img6');">
					<img src="<?php echo $MYLIB;?>/img/icon_remove.gif" style="margin:-2px" id="img5">
					<img src="<?php echo $MYLIB;?>/img/icon_remove_hover.gif" style="display:none;margin:-2px" id="img6">
				</div>
                <input type="submit" name="Submit" value="<?php echo $lg_view;?> .." style="font-weight:bold;color:#0066CC;">
								
				<label><input type="checkbox" name="searchall" value="1" <?php if($searchall) echo "checked";?>><?php echo $lg_all_status;?></label>
			<?php } else {?>
					<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
					<input type="hidden" name="year" value="<?php echo $year;?>">
					<input type="hidden" name="sid" value="<?php echo $sid;?>">
			<?php } ?>
			
</div> <!-- right -->
<div id="story">

<?php if($search!=""){?><div id="mytitlebg" style="color:#0066FF; font-size:12px"><?php echo $lg_search_for;?>... <?php echo $search;?></div><?php }?>

<div id="mytitle2"> DAFTAR NAMA PESERTA DIDIK - <?php echo $sname;?> </div>




<table width="100%" cellspacing="0" cellpadding="2">
	<tr >
			  <td class="mytableheader" style="border-right:none;" width="2%" align="center"><?php echo strtoupper($lg_no);?></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center">NO INDUK</td>
              <td class="mytableheader" style="border-right:none;" width="20%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort">NAMA SISWA</a></td>
			  <td class="mytableheader" style="border-right:none;" width="5%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
			  <td class="mytableheader" style="border-right:none;" width="7%" align="center">TEMPAT LAHIR</td> 
              <td class="mytableheader" style="border-right:none;" width="7%" align="center">TANGGAL LAHIR</td>
              <td class="mytableheader" style="border-right:none;" width="15%" align="center">NAMA ORANG TUA/WALI</td>
              <td class="mytableheader" style="border-right:none;" width="10%" align="center">PEKERJAAN</td>
              <td class="mytableheader" style="border-right:none;" width="25%" align="center">ALAMAT</td>
			  <td class="mytableheader" style="border-right:none;" width="7%" align="center">STATUS</td> 
              <td class="mytableheader" style="border-right:none;" width="7%" align="center">BUKU INDUK</td> 
	</tr>
<?php	
	if(($clscode=="")&&($clslevel==""))
    	$sql="select count(*) from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsponser $sqlsearch";
	else
		$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlclscode $sqlclslevel $sqlstustatus $sqlisyatim $sqlisinter $sqlisstaff $sqliskawasan $sqlisislah $sqlishostel $sqlisfakir $sqlsponser $sqlsearch and year='$year'";
    $res=mysql_query($sql,$link)or die("$sql:query failed:".mysql_error());
    $row=mysql_fetch_row($res);
    $total=$row[0];
	
	if(($curr+$MAXLINE)<=$total)
    	$last=$curr+$MAXLINE;
    else
    	$last=$total;
    
	if(($clscode=="")&&($clslevel==""))
		$sql="select * from stu where sch_id=$sid $sqlstustatus $sqlisyatim $sqlisblock $sqlisstaff $sqliskawasan $sqlisislah $sqlisinter $sqlisfakir $sqlishostel $sqlsponser $sqlsearch $sqlsort $sqlmaxline";
	else
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlclscode $sqlclslevel $sqlisyatim $sqlisinter $sqlisblock $sqlisstaff $sqliskawasan $sqlisislah $sqlisfakir $sqlishostel $sqlsponser $sqlsearch and year='$year' $sqlsort $sqlmaxline";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res4=mysql_query($sql)or die("query failed:".mysql_error());
		$row4=mysql_fetch_assoc($res4);
		$acc=$row4['acc'];
		$ic=$row4['ic'];
		$name=strtoupper(stripslashes($row4['name']));
		$sex=$lg_sexmf[$row4['sex']];
		$bday=$row4['bday'];
		$bplace=$row4['bplace'];
		$p1name=$row4['p1name'];
		$p1job=$row4['p1job'];
		$addr=$row4['addr'];
		$rdate=$row4['rdate'];
		$edate=$row4['edate'];
		$p1ic=$row4['p1ic'];
		$p1hp=$row4['p1hp'];
		$p1tel=$row4['p1tel'];
		$status=$row4['status'];
		$intake=$row4['intake'];
		$nobukuinduk=$row4['nobukuinduk'];

			$cname="- $lg_none -";
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
			$bg="#FFFFFF";
		$xxname=addslashes($name);
?>
		<tr bgcolor=<?php echo $bg;?> style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg;?>'">
		<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo $q;?></td>
<?php        
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$nobukuinduk</td>";
 		echo "<td class=myborder style=\"border-right:none; border-top:none;\">
			<a href=\"../exam/slip_legger.php?uid=$uid&year=$year&exam=$exam&sid=$sid\" title=Profile target=_blank onClick=\"return GB_showPage('Profile : $xxname ',this.href)\">$name</a></td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=\"center\">$sex</td>";		
		echo "<td class=myborder style=\"border-right:none; border-top:none;\"$bplace</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=center>$bday</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1name</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$p1job</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\">$addr $addr1 $addr2</td>";
		echo "<td class=myborder style=\"border-top:none;\" align=\"center\">$sta</td>";
		echo "<td class=myborder style=\"border-right:none; border-top:none;\" align=\"center\"><a href=\"../edaftar/bukuinduk.php?sid=$sid&ic=$ic\" title=Profile target=_blank>$lg_view</a></td>";
		echo "</tr>";
  }
  mysql_free_result($res);
  ?>
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