<link rel="stylesheet" href="<?php echo $DIR_SRC;?>/inc/my.css" type="text/css">

	<?php include("$PATH_OBJ/calender/calender.htm")?>
    <?php include("$PATH_OBJ/datepicker/dp.php")?>

<script language="JavaScript1.2" src="<?php echo $DIR_SRC;?>/inc/my.js" type="text/javascript"></script>
 <link rel="stylesheet" href="inc/jquerytab/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="inc/jquerytab/scripts/gettheme.js"></script>
    <script type="text/javascript" src="inc/jquerytab/scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="inc/jquerytab/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="inc/jquerytab/jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="inc/jquerytab/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var theme = getDemoTheme();
            // Create jqxTabs.
            $('#jqxTabs').jqxTabs({ width: '100%', height:1000, position: 'top' });
            $('#jqxTabs').jqxTabs({ selectionTracker: true});
            $('#jqxTabs').jqxTabs({ animationType: 'fade' });
               
        });
    </script>
    
    
<!-- no need apply gettheme method (theme: theme) when wana passing data --nurul -->