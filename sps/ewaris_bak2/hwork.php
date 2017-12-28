<?php 
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

	$sql="select * from type where grp='openexam' and prm='EHOMEWORK'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	//if($sta!="1")
		//echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		

	$id=$_REQUEST['id'];
	$schid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];
	$year=date("Y");

			
	$sql="select count(*) from hwork_stu where uid='$uid' and dt like '$year%'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$jumsalah=$row[0];
	
	$op=$_POST['op'];
	$checker=$_POST['checker'];
	//print_r($checker);
	if($op=="save"){
		for ($i=0; $i<count($checker); $i++) {
			echo $sql="update hwork_stu set submitdt=now() where id='$checker[$i]'";
			mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	
<script language="javascript">
function clearwin(){
	document.myform.action="";
	document.myform.target="";
}
function process_form(action){
	var ret="";
	var cflag=false;
	
		if(action=='save'){
			for (var i=0;i<document.myform.elements.length;i++){
					var e=document.myform.elements[i];
					if ((e.id=='checker')&&(e.name!='checkall')){
							if(e.checked==true)
								cflag=true;
							else
								allflag=false;
					}
			}
			if(!cflag){
				alert('Please checked homework to submit');
				return;
			}
			ret = confirm("Are you sure want to submit??");
			if (ret == true){
				document.myform.op.value=action;
				document.myform.submit();
			}
			return;
		}
		
		return;
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="hwork">
		<input type="hidden" name="op">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>

<div id="story">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="clearwin();process_form('save');" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="clearwin();document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
</div>
</div>

<table width="100%" id="mytable" >
  <tr>
	
	<td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_date);?></td>
	<td id="mytabletitle" width="4%" align="center"><?php echo strtoupper($lg_code);?></td>
	<td id="mytabletitle" width="20%" align="center"><?php echo strtoupper($lg_subject);?></td>
	<!--<td id="mytabletitle" width="30%"><?php echo strtoupper($lg_homework);?></td>-->
	<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_book);?></td>
	<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper("halaman");?></td>
	<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_mark);?></td>
	<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_status);?></td>
	<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_date_submit);?></td>
	<!--<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_done);?></td>-->
  </tr>


<?php 
	$q=0;
	$sql="select * from hwork_stu where uid='$uid' and dt like '%$year%' order by id desc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xid=$row['id'];
		$ref_id=$row['xid'];
		$dt=$row['dt'];
		$sta=$row['sta'];
		if($sta==0){
			$staview="$lg_waiting";
		}else{
			$staview="$lg_check";
		}
		$mark=$row['mark'];
		$submitdt=$row['submitdt'];
		
		$sqlhwork="select * from hwork where sid='$schid'  and id='$ref_id'";
		$reshwork=mysql_query($sqlhwork)or die("$sqlhwork query failed:".mysql_error());
		$rowhwork=mysql_fetch_assoc($reshwork);
		$sub_hwork=$rowhwork['sub'];
		$sname_hwork=$rowhwork['subname'];
		$des_hwork=$rowhwork['des'];
		$ms_hwork=$rowhwork['ms'];
		$book_hwork=$rowhwork['book'];
		$tea_hwork=$rowhwork['adm'];
    	
		if(($q++%2)==0)
			$bgc="bgcolor=#FAFAFA";
		else
			$bgc="";
?>

  <tr <?php echo "$bgc";?>>
	
	<td id="myborder" align="center"><?php echo "$q";?></td>
	<td id="myborder" align="center">&nbsp;<?php echo "$dt";?></td>
	<!--<td id="myborder"><a href="#" onClick="process_form('update',<?php echo "$xid";?>)"><?php echo "$des_hwork";?></a></td>-->
	<td id="myborder" align="center"><?php echo "$sub_hwork";?></td>
	<td id="myborder">&nbsp;<?php echo "$sname_hwork";?></td>
	<!--<td id="myborder">&nbsp;<?php echo "$des_hwork";?></td>-->
	<td id="myborder">&nbsp;<?php echo "$book_hwork";?></td>
	<td id="myborder" align="center"><?php echo "$ms_hwork";?></td>
	<td id="myborder" align="center"><?php echo number_format($mark,2);?></td>
    <td id="myborder" align="center"><?php echo "$staview";?></td>
    <td id="myborder"><?php echo "$submitdt";?></td>
    <!--<td id="myborder"><?php echo "$submitdt";?></td>-->
  </tr>


<?php } ?>
 </table> 
 

    </table>
	
	</td>

  </tr>
</table>


</div></div>
</form>


</body>
</html>
