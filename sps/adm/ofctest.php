<?php
include_once('../etc/db.php');

?>
<html>
<head>

<script type="text/javascript">

function onrollout()
{
  tmp = findSWF("ofc");
  x = tmp.rollout();
}

function onrollout2()
{
  tmp = findSWF("ofc");
  x = tmp.rollout();
}

function findSWF(movieName) {
  if (navigator.appName.indexOf("Microsoft")!= -1) {
    return window[movieName];
  } else {
    return document[movieName];
  }
}

</script>

</head>
<body>
<script type="text/javascript" src="<?php echo "$MYOBJ/ofc197";?>/js/swfobject.js"></script>

<?php for($jj=0;$jj<=55;$jj++){?>
<div style="float:left"><?php echo $jj;?></div>
<div id="my_chart<?php echo $jj;?>" style="padding: 0px; margin:10px; border: 1px solid lightblue; width: 250px; height: 200px;" onMouseOut="__onrollout();"></div>
<script type="text/javascript">
var so = new SWFObject("<?php echo "$MYOBJ/ofc197";?>/actionscript/open-flash-chart.swf", "ofc", "250", "200", "9", "#FFFFFF");
so.addVariable("data", "<?php echo "$MYOBJ/ofc197";?>/data-files/data-<?php echo $jj;?>.txt");
/*
so.addVariable("variables","true");
so.addVariable("title","Test,{font-size: 20;}");
so.addVariable("y_legendx","Open Flash Chart,12,0x736AFF");
so.addVariable("y_label_size","15");
so.addVariable("y_ticks","5,10,4");
so.addVariable("bar","50,0x9933CC,Page views,10");
so.addVariable("values","9,6,7,9,5,7,6,9,9");
so.addVariable("x_labels","January,,March,,May,,June,,August");
so.addVariable("x_axis_steps","2");
so.addVariable("y_max","20");
*/

so.addParam("allowScriptAccess", "always" );//"sameDomain");
so.addParam("onmouseout", "onrollout2();" );
so.write("my_chart<?php echo $jj;?>");
</script>

<?php }?>

</body>
</html>