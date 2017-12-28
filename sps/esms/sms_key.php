<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
include_once('../inc/xgate.php');
verify('ADMIN');
$adm=$_SESSION['username'];
$sid=$_SESSION['sid'];

$search=$_REQUEST['search'];
if(strcasecmp($search,"- Key1, Key2, Key3 -")==0)
	$search="";
if($search!="")
	$sqlsearch="and (key1='$search' or key2='$search' or key3='$search')";
	
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
		$sqlsort="order by $sort $order";
		
		
$id=$_REQUEST['id'];
$op=$_REQUEST['op'];
if($id!=""){
			$sql01="select * from sms_key where id=$id ";
			$res01=mysql_query($sql01)or die("query failed:".mysql_error());
			$num=mysql_num_rows($res01);
			while($row=mysql_fetch_assoc($res01)){
				$key1=$row['key1'];
				$key2=$row['key2'];
				$key3=$row['key3'];
				$typ=$row['typ'];
				$act=$row['act'];
				$msg=$row['msg'];
				$ress=$row['res'];
			}
			mysql_free_result($res01);
}
if($op=="delete"){
	$del=$_POST['del'];
	if (count($del)>0) {
			for ($i=0; $i<count($del); $i++) {
	      		$sql2="select * from sms_key where id='$del[$i]'";
                $res=mysql_query($sql2)or die("query failed:".mysql_error());
                if($row=mysql_fetch_assoc($res)){
                        $fileo=$row['file'];
                }
                mysql_free_result($res);
				if($fileo!=""){
                        $target_path = "../content/".$fileo;
                        unlink($target_path);
                }
				$sql="delete from sms_key where id='$del[$i]'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$log="keyword key1=\"$key1\", key2=\"$key2\", key3=\"$key3\"";
			}
	}
	/**echo "<script language=\"javascript\">location.href='p.php?p=sms_key'</script>";**/
}
else if($op=="save"){
	$typ=$_POST['typ'];	$typ=0;
	$key1=$_POST['key1'];
	$key2=$_POST['key2'];
	$key3=$_POST['key3'];
	$act=$_POST['act'];
	$msg=$_POST['msg'];
	$ress=$_POST['res'];


	$msg=addslashes($msg);
	$ress=addslashes($ress);
	$fn=basename( $_FILES['file']['name']);
	$operation=$_POST['operation'];
	$del=$_POST['del'];

	if(($id=="")||($id==0)){
		$sql="insert into sms_key(dt,sid,act,typ,msg,res,key1,key2,key3,adm,ts) values (now(),$sid,$act,0,'$msg','$ress','$key1','$key2','$key3','$adm',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
        //ulog($link,$cli,"client","add",$log,"");
	}
	else{
		$sql="update sms_key set msg='$msg',res='$ress',act=$act,key1='$key1',key2='$key2',key3='$key3',adm='$adm',ts=now() where id=$id";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());


	}	
	$id="";
}
	/**
	else{	
		echo "<script language=\"javascript\">location.href='p.php?p=sps_key'</script>";
	}
	**/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">

function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='save'){
		ret = confirm("Save the information??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
			return true;
		}
	}
	else if(action=='new'){
		document.myform.key2.value="";
		document.myform.key3.value="";
		document.myform.msg.value="";
		document.myform.res.value="";
		document.myform.id.value="";
	}
	else if(action=='delete'){
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
			alert('Please checked the item to delete');
			return;
		}
		ret = confirm("Are you sure want to DELETE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}
function kira(field,countfield,maxlimit){
        var y=field.value.length;
        if(y>=maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("140 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx+" HURUF";
        }
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SSS</title>
</head>
<body>
<form name=myform action="" method=post enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input name="p" type="hidden" id="p" value="sms_key">
	<input name="op" type="hidden" value="">
	
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav--> 
<div id="content2">


	
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('new');show('divform');" id="mymenuitem"><img src="../img/new.png"><br>New</a>
	<a href="#" onClick="show('divform');process_form('save')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="javascript:href='p.php?p=sms_key'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="hide('divform');process_form('delete');" id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
</div> <!-- end mymenu -->
	
	<div id="viewcontrol" style="padding:5px 5px 5px 5px " align="right">
			<input name="search" type="text" id="search" onMouseDown="document.myform.search.value='';document.myform.search.focus();" value="<?php if($search=="") echo "- Key1, Key2, Key3 -"; else echo "$search";?>"> 
			<input name="go" type="submit" id="go" value="View" >
	</div>
</div> <!-- end mypanel -->


<div id="story">
<div id="divform" style="display:<?php if($id=="") echo "none";else echo "block";?>" >

<div id="mysubtitle">
	<a href="#" onClick="hide('divform');">
	<div style="float:left;display:block; padding:0px 2px 0px 2px;" id="iconmax"><img src="../img/icon_minimize.gif"></div>
	<div style="float:none">SMS CONFIGURATION</strong></div>
	</a>
</div><!-- end mysubtitle -->



	<table width="100%" id="mytable">
    	    <tr >
        		<td width="5%">Keyword</td>
				<td width="1%">:</td>
	          <td>
				<input name="key1" type="text" maxlength=32 size="12" value="<?php echo $xgatekey;?>" readonly=""> 
				&nbsp;&nbsp;Key2:<input name="key2" type="text" id="key2"  maxlength=32 size="12" value="<?php echo $key2;?>" > 
				&nbsp;&nbsp;Key3:<input name="key3" type="text" id="key3"  maxlength=32 size="12" value="<?php echo $key3;?>" ></td>
			</tr>
			<tr>
        		<td>Operation</td>
				<td width="1%">:</td>
		        <td>
        			<select name="act"">
                      <?php
				if($act!=""){
                   	$sql="select prm,val from sys_prm where grp='SMS_ACTION' and val='$act' order by val";
	                $res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_assoc($res);
					$p=$row['prm'];
					$v=$row['val'];
   	                echo "<option value=\"$v\">$p</option>";
            	    mysql_free_result($res);
				}
                $sql="select prm,val from sys_prm where grp='SMS_ACTION' and val!='$act' order by val";
                $res=mysql_query($sql)or die("query failed:".mysql_error());
                while($row=mysql_fetch_assoc($res)){
					$p=$row['prm'];
					$v=$row['val'];
                    echo "<option value=\"$v\">$p</option>";
                }
                mysql_free_result($res);
?>
                    </select>
       		  </td>
	        </tr>

	        <tr >
    		    <td valign="top">Message</td>
				<td width="1%" valign="top">:</td>
			  <td >       	        
			  		<textarea name="msg" cols="50" rows="5" id="msg" onkeypress="kira(this,this.form.jum,140);"><?php echo "$msg";?></textarea>
        	        <br>
        	        <input type="text" name="jum" value="<?php echo strlen($msg);?> HURUF" size="8" disabled>
   	          </td>
		    </tr>
			<tr >
    		    <td valign="top">ETC:</td>
				<td width="1%" valign="top">:</td>
			  <td >
			  		<textarea name="res" cols="50" rows="3" id="res" onkeypress="kira(this,this.form.jum2,140);"><?php echo $ress;?></textarea>
        	        <br>
        	        <input type="text" name="jum2" value="<?php echo strlen($ress);?> HURUF" size="8" disabled>
   	          </td>
		    </tr>
			
      </table>



</div><!-- end of divform -->


<div id="mytitle">SMS KEYWORD</div>

	<table width="100%" id="mytable">
        <tr id="mytabletitle">
		<td width="5%"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
		<td width="10%" >No</td>
        <td width="15%" ><a href="#" onClick="formsort('key1','<?php echo "$nextdirection";?>')" title="Sort">Key1</a></td>
        <td width="15%" ><a href="#" onClick="formsort('key2','<?php echo "$nextdirection";?>')" title="Sort">Key2</a></td>
        <td width="15%" ><a href="#" onClick="formsort('key3','<?php echo "$nextdirection";?>')" title="Sort">Key2</a></td>
		<td width="10%" align="center"><a href="#" onClick="formsort('typ','<?php echo "$nextdirection";?>')" title="Sort">Type</a></td>
		<td width="10%" align="center">Action</td>
		<td width="10%" align="center">Ref#</td>
        </tr>

	<?php
	if($total==""){
		$sql="select count(*) from sms_key where id>0 $sqlsearch";
        $res=mysql_query($sql,$link)or die("query failed:".mysql_error());
        $row=mysql_fetch_row($res);
        $total=$row[0];
        if($total=="")
			$total=0;
    }
	if(($curr+$MAXLINE)<=$total)
		$last=$curr+$MAXLINE;
	else
		$last=$total;

	$sql02="select * from sms_key where id>0 $sqlsearch $sqlsort limit $curr,$MAXLINE";
	$res02=mysql_query($sql02)or die("query failed:".mysql_error());
	$q=$curr;
	while($row=mysql_fetch_assoc($res02)){
			$id=$row['id'];
			$key1=$row['key1'];
			$key2=$row['key2'];
			$key3=$row['key3'];
			$typ=$row['typ'];
			$act=$row['act'];

		$sql="select prm,val from sys_prm where grp='SMS_ACTION' and val='$act' order by val";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$xact=$row['prm'];
					
		if($q++%2==0)
			$bg="bgcolor=\"#FAFAFA\"";
		else
			$bg="";
		
?>

        <tr <?php echo "$bg";?>>
		<td width="5%"><input type=checkbox name=del[] value="<?php echo "$id";?>" onClick="check(0)"></td>
		<td width="10%"  ><?php echo "$q";?></td>
        <td width="15%"><?php echo "<a href=p.php?p=sms_key&id=$id>$key1</a>";?></td>
        <td width="15%" ><?php echo "$key2";?></td>
        <td width="15%" ><?php echo "$key3";?></td>
		<td width="10%" align="center"><?php echo "$typ";?></td>
		<td width="10%" align="center"><?php echo "$xact";?></td>
		<td width="10%" align="center"><?php echo "$id";?></td>
        </tr>

<?php } ?>
 	</table>  

<?php include_once('../inc/paging.php');?>


</div> <!-- end of story -->
</div><!--end of content-->
 </form>
</body>
</html>
