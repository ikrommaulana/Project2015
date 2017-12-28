<?php
$vmod="v5.0.1";
$vdate="12/09/2012";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
		

		$year=$_REQUEST['year'];
		if($year=="")
			$year=date('Y');
			
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
            mysql_free_result($res);					  
		}
		
					
			
	$cls=$_REQUEST['clscode'];
	if($cls!=""){
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$cls'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $lvl=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
	}
	
	$sub=$_REQUEST['subcode'];
	$sql="select * from ses_sub where sub_code='$sub' and cls_level='$lvl' and sch_id=$sid and year='$year' and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$subname=stripslashes($row['sub_name']);

$op=$_POST['op'];
		$del=$_POST['del'];
		
		if($op=="delete"){
		if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
				$id=$del[$i];
				$sql="select * from elesson where id='$id'";
	            $res=mysql_query($sql)or die("query failed:".mysql_error());
    	        $row=mysql_fetch_assoc($res);
        	    $sid=$row['sch_id'];
				$clslevel=$row['level'];
			
			$sql="delete from elesson where id=$id";
		      	mysql_query($sql)or die("query failed:".mysql_error());
				
				
		      	$sql="delete from elesson where id=$id";
		      	mysql_query($sql)or die("query failed:".mysql_error());
			}
		}
		$f="<font color=blue>&lt;Successfully Deleted&gt;</font>";
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
<script language="JavaScript">
<!--
function process_form(sid,uid,clscode,subcode,op){
		document.myform.sid.value=sid;
		document.myform.usr_uid.value=uid;
		document.myform.clscode.value=clscode;
		document.myform.subcode.value=subcode;
		document.myform.operation.value="4";
		if(op==0){
			document.myform.sort.value="";
			document.myform.order.value="";
			document.myform.p.value="examreg";
		}else{
			document.myform.p.value="att_cls_rep";
			document.myform.action="p.php";
		}
		document.myform.submit();
}

function process_form(op){
		

if(op=='delete'){
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
			alert("<?php echo $lg_check_the_item;?>");
			return;
		}
		ret = confirm("<?php echo "Hapus Data?";?>");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
	}
}
//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
 
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
    <input type="hidden" name="op">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" value="<?php echo $cls;?>">
	<input name="subcode" type="hidden" value="<?php echo $sub;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="../adm/my_ses_cls.php?<?php echo "year=$year";?>" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/delete.png"><br><?php echo $lg_delete;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
					<div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="<?php echo "../elesson/elesson_reg.php?year=$year&sid=$sid&clscode=$cls&subcode=$sub";?>" id="mymenuitem"><img src="../img/new.png"><br>New</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>	
		<a href="#" onClick="window.close();parent.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>         
	</div>
		<div align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div>
<div id="story">
<div style="padding:5px;" align="right">
<select name="year" onChange="document.myform.submit()" style="font-size:18px; font-weight:bold;">
                <?php 
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc limit 1";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=$s>$lg_session $s</option>";
					}
				?>
          </select>
</div>
         

 <div id="mytitle2"><?php echo $lg_teaching_lesson;?> <?php echo $lg_subject;?> : <?php echo $subname;?> <?php echo $year;?> </div>
 <table width="100%" cellspacing="0" cellpadding="5px" style="font-size:12px;">
  <tr>
	<td id="mytabletitle" style="border-left:none;" align="center" width="2%"><?php echo " ";?></td>	
    <td id="mytabletitle" style="border-left:none;" align="center" width="4%"><?php echo $lg_no;?></td>
	<td id="mytabletitle" style="border-left:none;" width="9%" align="center"><?php echo $lg_date;?></td>
    <td id="mytabletitle" style="border-left:none;" width="18%" align="center"><?php echo "judul";?></td>
    <td id="mytabletitle" style="border-left:none;" width="25%" align="center"><?php echo "objektif";?></td>
    <td id="mytabletitle" style="border-left:none;" width="19%" align="center"><?php echo $lg_skill;?></td>
    <td id="mytabletitle" style="border-left:none;" width="11%" align="center"><?php echo $lg_nilai;?></td>
    <td id="mytabletitle" style="border-left:none;" width="14%" align="center"><?php echo $lg_bahanbantu;?></td>
  </tr>


<?php 
	$q=0;
	$sql="select * from elesson where uid='$username' and cls='$cls' and sub='$sub' and year='$year' $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$id=$row['id'];
		$dt=$row['dt'];
		$title=$row['title'];
		$skill=$row['skill'];
		$nilai=$row['nilai'];
		$bahanbantu=$row['bahanbantu'];
    	$obj=stripslashes($row['obj']);
		
			
		if(($q++%2)==0)
			$bg="$bglyellow";
		else
			$bg="$bglyellow";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
	<td class="myborder" style="border-right:none; border-top:none;"><input type=checkbox name=del[] value="<?php echo "$id";?>" onClick="check(0)"></td>	
	<td id="myborder" style="border-left:none; border-top:none;" align="center"><?php echo "$q";?></td>
        <td id="myborder" style="border-left:none; border-top:none;" align="center"><?php echo "$dt";?></td>
        <td id="myborder" style="border-left:none; border-top:none;">
        	<a href="../elesson/elesson_reg.php?xid=<?php echo $id;?>"><img src="../img/edit12.png"> <?php echo "$title";?></a></td>
        <td id="myborder" style="border-left:none; border-top:none;"><?php echo "$obj";?></td>
        <td id="myborder" style="border-left:none; border-top:none;"><?php echo "$skill";?></td>
        <td id="myborder" style="border-left:none; border-top:none;"><?php echo "$nilai";?></td>
        <td id="myborder" style="border-left:none; border-top:none;"><?php echo "$bahanbantu";?></td>
		
  </tr>


<?php } ?>
 </table> 

 </div> </div>
 </form>
</body>
</html>
