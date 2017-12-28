<?php 
	include_once('../etc/db');
	include_once('etc/session_sms.php');
	verify_admin();
	$username = $_SESSION['username'];
	$mobileid = $_SESSION['mobileid'];
	$userid = $_SESSION['userid'];
	
	$id=$HTTP_GET_VARS['id'];
	$sql01="select * from msg where id='$id'";
	$res01=mysql_query($sql01)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res01)){
		$cdate=$row['cdate'];
		$ctype=$row['ctype'];
		$mtype=$row['mtype'];
		$tel=$row['tel'];
		$msg=$row['msg'];
		$rm=$row['rm'];
		$state=$row['state'];
		$status=$row['status'];
		$categ=$row['categ'];
		$name=$row['name'];
		$company=$row['company'];
		$tel=$row['tel'];
		$email=$row['email'];
		$fax=$row['fax'];
		$addr=$row['addr'];
		$admin=$row['admin'];
		$action=$row['action'];
		$response=$row['resp'];
		$sub=$row['subcode'];
		$officer=$row['officer'];
		$officerid=$row['officerid'];
		$epre=$row['units'];
		$moid=$row['moid'];
		$mass= htmlspecialchars($msg, ENT_QUOTES);
	}
	mysql_free_result($res01);

	$REG="(UNREGISTERED)";
	$sql="select * from user where tel='$tel'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
			$uid=$row['id'];
            $fname=$row['fname'];
			$name=$row['name'];
			$units=$row['units'];
			$email=$row['email'];
			$REG="(REGISTERED)";			
    }
    mysql_free_result($res);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>


<head>
<script language="JavaScript">

function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(maxlimit>0){
                if(y>maxlimit){
                        field.value=field.value.substring(0,maxlimit);
                        alert("160 maximum character..");
                        return true;
                }else{
                        countfield.value=maxlimit-y;
                }
        }
        else{
                countfield.value=y;
        }
}
</script>

<script language="javascript">
function del_confirm(){
        return confirm("Are you sure want to delete??")	
}

function process_form(action){
	var ret="";
	if(action=='add'){
		document.form1.operation.value=action;
		document.form1.submit();
		return;
	}
	if(action=='del'){
		ret = confirm("Are you sure want to delete??");
		if (ret == true){
			document.form1.operation.value=action;
			document.form1.submit();
		}
		return;
	}
}

</script>
<script language="javascript">
	var myWind = ""
	function openchild(table,fname) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
			url= fname + "?tbl=" + table;
    			myWind = window.open(url,"subWindow","HEIGHT=700,WIDTH=270,scrollbars=yes,status=yes,resizable=yes,top=0")
	  	} else{
    			myWind.focus();
  		}

	} 
</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
 
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
 <input type="hidden" name="p" value="sms/msgsave">
 <input name="moid" type="hidden" id="moid" value="<?php echo $moid;?>">
 
 <input name="admin" type="hidden" id="admin" value="<?php echo $username;?>">
	<input name="tel" type="hidden" id="tel" value="<?php echo $tel;?>">
	<input name="name" type="hidden" id="name" value="<?php echo $name;?>">
	<input name="id" type="hidden" id="id" value=" <?php echo $id;?>">
    <input name="date" type="hidden" id="date" value="<?php echo $cdate;?>">
    <input name="ctype" type="hidden" id="ctype" value="<?php echo $ctype;?>">
	
	<table width="56%" >
	
	<tr>
	<td >
	  <table width="99%" bgcolor="#FAFAFA">
        <tr >
          <td width="30%">
		  	Date:<?php echo $cdate?><br>		  	
			UserName: 
			<a href="p.php?p=usr/profile&uid=<?php echo $uid?>">
			<?php if ($name=="") echo 'Guest'; else echo "$name"; ?>
			</a><br>
			FullName:<?php echo "$fname"; ?><br>
			Credit:<?php printf("RM%.02f",$units/100);?> <br>
			 
			</td>
          <td width="39%" valign="top">
		  	Ref#:<?php echo $id?><br>
		  	Mobile:<a href="p.php?p=sms/msghis&tel=<?php echo $tel?>" title="msg history"> <?php echo "$tel"; ?></a><br>
			Email:<?php echo "$email"; ?><br>

		  	</font></b></td>
        </tr>
      </table></td>
	</tr>
	<tr>
	<td >
	  <strong>Question:&lt; <?php echo "via $ctype"; ?> &gt; </strong></td>
	</tr>
	<tr >
    <td >
	<textarea name="textarea" cols="65" rows="5" readonly><?php echo $mass;?></textarea>
    <input name="msg" type="hidden" id="msg" value="<?php echo $mass;?>"></td>
    </tr>
	
	<tr>
	<td  ><strong>Response:
	  <select name="categ" style="width:80;">
        <?php
			if($categ=="")
                   	echo "<option value=\"\">-category-</option>";
			else
				echo "<option value=\"$categ\">$categ</option>";
                  $sql="select name from category where mtype='msg' order by name";
                	$res=mysql_query($sql)or die("query failed:".mysql_error());
                	while($row=mysql_fetch_assoc($res)){
                        $s=$row['name'];
                        echo "<option value=\"$s\">$s</option>";
                	}
                	mysql_free_result($res)or die("mysql failed:".mysql_error());

		?>
      </select>
      <input type="button" name="setting" value="-" onClick="openchild('msg','sms/msgcat.php')">
	</strong></td>
	</tr>
	<tr >
    <td ><textarea name="resp" cols="65" rows="6" id="resp" onkeypress="kira(this,this.form.jum,0);"><?php echo $response;?></textarea>
	<br><input type="text" name="jum" value="<?php echo strlen($response);?>" size="1" disabled>characters</td>
    </tr>
	<tr>
	<td  >
	   
	  <table width="98%">
        <tr bgcolor="#FAFAFA">
          <td width="12%" valign=top>&nbsp;Action</b>:
            <br>
            <input name="sms" type=checkbox id="sms" value="yes" onClick="smscheck()" <?php if($ctype == "sms") echo "checked";?>>
            Sms<br>
            <input name="web" type=checkbox id="web" value="yes" <?php if($ctype == "web") echo "checked";?>>            
            Web<br>
            <input name="mel" type=checkbox id="mel" value="yes">
            Mel</td>
          <td width="15%"  valign=top>&nbsp;Status: <br>
            <input type="radio" name="status" value="new" <?php if ($status=='new') echo "checked";?>>
            New<br>
            <input type="radio" name="status" value="pending" <?php if ($status=='pending') echo "checked";?>>
            Pending
            <br>            
            <input type="radio" name="status" value="complete" <?php if ($status=='complete') echo "checked";?>>
            Complete<br>
            <input type="radio" name="status" value="trash" <?php if ($status=='trash') echo "checked";?>>
            Trash</td>
          <td width="21%" valign=top>Charging:<br>
            <select name="rmsms" id="rmsms"  style="width:50;" <?php if($ctype != "sms") echo "disabled";?>>
				<?php if(($rm!="0")||($rm!="")) printf("<option value=%d>$%.02f</option>",$rm,$rm/100);?>
              <option value="0">$0.00</option>
              <option value="30">$0.30</option>
              <option value="50">$0.50</option>
              <option value="100">$1.00</option>
              <option value="150">$1.50</option>
              <option value="200">$2.00</option>
              <option value="250">$2.50</option>
              <option value="300">$3.00</option>
              <option value="350">$3.50</option>
              <option value="400">$4.00</option>
              <option value="450">$4.50</option>
              <option value="500">$5.00</option>
            </select>
            SMS<br>
            <select name="rmpre" id="rmpre"  <?php if($units <= 0) echo "disabled";?>>
			<?php if(($epre!="0")||($epre!="")) printf("<option value=%d>$%.02f</option>",$epre,$epre/100);?>
              <option value="0">$0.00</option>
              <option value="30">$0.30</option>
              <option value="50">$0.50</option>
              <option value="100">$1.00</option>
              <option value="150">$1.50</option>
              <option value="200">$2.00</option>
              <option value="250">$2.50</option>
              <option value="300">$3.00</option>
              <option value="350">$3.50</option>
              <option value="400">$4.00</option>
              <option value="450">$4.50</option>
              <option value="500">$5.00</option>
              <option value="1000">$10.00</option>
              <option value="1500">$15.00</option>
              <option value="2000">$20.00</option>
            </select> 
            ePrepaid <br>
            <input type="button" name="delbutton" value="Delete"  onClick="return process_form('del')" >
            <input type="button" name="add" value="Save" onClick="return process_form('add')">
            <input name="operation" type="hidden" value="">
            </td>
          
        </tr>
      </table>
	  	</font></b></td>
	</tr>
	<tr>
	<td >
	<strong> </strong>	</td>
  	</tr>
  </table>

	
	
</form>	


</body>
</html>
