<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
verify('ADMIN');

$msg=$_POST['msg'];
$tel=$_POST['tel'];
$grp=$_POST['grp'];
$op=$_REQUEST['op'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
function process_send(){
	if(document.form1.tel.value==""){
		alert("Please select the recepient");
		document.form1.tel.focus();
		return;
	}
	if(document.form1.msg.value==""){
    	alert("Please enter the message");
        document.form1.msg.focus();
        return;
    }
	ret = confirm("Send this SMS??");
	if (ret == true){
			document.form1.op.value="send";
			document.form1.p.value="composersave";
			document.form1.submit();
	}
	return;
}
</script>

<script language="javascript">
	var myWind = ""
	function openchild(table,fname) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
				url= "addrbook.php?tbl=" + table
    			myWind = window.open(url,"subWindow","HEIGHT=650,WIDTH=800,scrollbars=yes,status=yes,resizable=yes,left=0,top=0")
	  	} else{
    			myWind.focus();
  		}

	} 
</script>

<script language="JavaScript">
function clearfield()
{
	document.form1.tel.value="";
}
</script>
<script language="JavaScript">

function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("150 maximum character..");
                return true;
        }else{
                countfield.value=maxlimit-y;
        }
}
</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SSS</title>
</head>
<body>
 
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="p.php?p=composer" id="mymenuitem"><img src="../img/new.png"><br>New</a>
		<a href="#" id="mymenuitem" onClick="process_send()"><img src="../img/flash.png"><br>Send</a>
	</div> <!-- end mymenu -->
</div> <!-- end mypanel -->
 
<div id="story">

<div id="mytitle">SMS COMPOSER</div>

<form name="form1" method="post" action="p.php">
	<input name="p" type="hidden" id="p" value="composer">
	<input name="op" type="hidden">
        <table width="100%" id="mytable">
			<tr >
			  <td >
			  	<strong>To: <a href="#" onClick="openchild('user','addrbook.php')">Add Address</strong></a>&nbsp;&nbsp;<font size="1">(max 20 numbers)</font><br>
				<textarea name="tel" readonly="readonly" id="tel" style="width:70%; font-size:150%; color:#0000FF"><?php echo "$tel";?></textarea><br>
			
		<br>
		
		</td>
		</tr>
	        <tr >
  				<td ><br>
				<strong>Message:</strong><br>
                <textarea name="msg" style="width:70%; font-size:200%; color:#0000FF" rows="5" id="msg" onkeypress="kira(this,this.form.jum,140);"></textarea>
				</td>
			</tr>
	        <tr>
    		    <td>
                <input type="text" name="jum" value="140" size="2" onBlur="kira(this.form.jum,this,140);" disabled>
				character
		</tr>
      </table>

    </form>



</div><!-- end of story -->
</div><!--end of content-->   

</body>
</html>
