<?php
include_once('../../etc/db_js.php');
include_once('../../etc/session_js.php');
include_once("../$MYLIB/inc/language_$LG.php");


$sid=$_SESSION['schid'];
if($sid!=0)
	$sqlsid="and sch_id=$sid";
	
$sql="select count(*) from feeonlinetrans where sta=0 $sqlsid";
$res=mysql_query($sql)or die("query failed".mysql_error());
$row=mysql_fetch_row($res);
$onlinefeesta=$row[0];


?>
/*
 Milonic DHTML Menu
 Written by Andy Woolley
 Copyright 2002 (c) Milonic Solutions. All Rights Reserved.
 Plase vist http://www.milonic.co.uk/menu or e-mail menu3@milonic.com
 You may use this menu on your web site free of charge as long as you place prominent links to http://www.milonic.co.uk/menu and
 your inform us of your intentions with your URL AND ALL copyright notices remain in place in all files including your home page
 Comercial support contracts are available on request if you cannot comply with the above rules.

 Please note that major changes to this file have been made and is not compatible with versions 3.0 or earlier.

 You no longer need to number your menus as in previous versions. 
 The new menu structure allows you to name the menu instead, this means that you can remove menus and not break the system.
 The structure should also be much easier to modify, add & remove menus and menu items.
 
 If you are having difficulty with the menu please read the FAQ at http://www.milonic.co.uk/menu/faq.php before contacting us.

 Please note that the above text CAN be erased if you wish as long as copyright notices remain in place.
*/

//The following line is critical for menu operation, and MUST APPEAR ONLY ONCE. If you have more than one menu_array.js file rem out this line in subsequent files
menunum=0;menus=new Array();_d=document;function addmenu(){menunum++;menus[menunum]=menu;}function dumpmenus(){mt="<script language=javascript>";for(a=1;a<menus.length;a++){mt+=" menu"+a+"=menus["+a+"];"}mt+="<\/script>";_d.write(mt)}
//Please leave the above line intact. The above also needs to be enabled if it not already enabled unless this file is part of a multi pack.



////////////////////////////////////
// Editable properties START here //
////////////////////////////////////

// Special effect string for IE5.5 or above please visit http://www.milonic.co.uk/menu/filters_sample.php for more filters
if(navigator.appVersion.indexOf("MSIE 6.0")>0)
{
	effect = "Fade(duration=0.2);Alpha(style=0,opacity=88);Shadow(color='#777777', Direction=135, Strength=0)"
}
else
{
	effect = "Shadow(color='#777777', Direction=135, Strength=0)" // Stop IE5.5 bug when using more than one filter
}


timegap=500				// The time delay for menus to remain visible
followspeed=5			// Follow Scrolling speed
followrate=40			// Follow Scrolling Rate
suboffset_top=0;		// Sub menu offset Top position 
suboffset_left=0;		// Sub menu offset Left position

style1=[				// style1 is an array of properties. You can have as many property arrays as you need. This means that menus can have their own style.
'9999ff',					// Mouse Off Font Color
,				// Mouse Off Background Color
'ffffff',				// Mouse On Font Color
,		        		// Mouse On Background Color (imtiaz 69090A)
"333333",				// Menu Border Color 
11,						// Font Size in pixels
"normal",				// Font Style (italic or normal)
"normal",				// Font Weight (bold or normal)
"Verdana",				// Font Name
4,						// Menu Item Padding
"<?php echo $MYOBJ;?>/hmenu/arrow.gif",	// Sub Menu Image (Leave this blank if not needed)
,						// 3D Border & Separator bar
"666666",				// 3D High Color
"000099",				// 3D Low Color
'FFFFFF',				// Current Page Item Font Color (leave this blank to disable)
,				    // Current Page Item Background Color (leave this blank to disable)
"<?php echo $MYOBJ;?>/hmenu/arrowdn.gif",// Top Bar image (Leave this blank to disable)
"ffffff",				// Menu Header Font Color (Leave blank if headers are not needed)
"000099",				// Menu Header Background Color (Leave blank if headers are not needed)
]



addmenu(menu=[		// This is the array that contains your menu properties and details
"mainmenu",			// Menu Name - This is needed in order for the menu to be called
0,					// Menu Top - The Top position of the menu in pixels //58,80
,					// Menu Left - The Left position of the menu in pixels
,					// Menu Width - Menus width in pixels
1,					// Menu Border Width 
,					// Screen Position - here you can use "center;left;right;middle;top;bottom" or a combination of "center:middle"
style1,				// Properties Array - this is set higher up, as above
1,					// Always Visible - allows the menu item to be visible at all time (1=on/0=off)
"left",				// Alignment - sets the menu elements text alignment, values valid here are: left, right or center
,					// Filter - Text variable for setting transitional effects on menu activation - see above for more info
,					// Follow Scrolling - Tells the menu item to follow the user down the screen (visible at all times) (1=on/0=off)
1, 					// Horizontal Menu - Tells the menu to become horizontal instead of top to bottom style (1=on/0=off)
0,					// Keep Alive - Keeps the menu visible until the user moves over another menu or clicks elsewhere on the page (1=on/0=off)
,					// Position of TOP sub image left:center:right
,					// Set the Overall Width of Horizontal Menu to 100% and height to the specified amount (Leave blank to disable)
,					// Right To Left - Used in Hebrew for example. (1=on/0=off)
,					// Open the Menus OnClick - leave blank for OnMouseover (1=on/0=off)
,					// ID of the div you want to hide on MouseOver (useful for hiding form elements)
,					// Reserved for future use
,					// Reserved for future use
,					// Reserved for future use

,"<?php echo $lg_home;?>","../adm/index.php",,"Back to the home page",1 // "Description Text", "URL", "Alternate URL", "Status", "Separator Bar"
<?php
if(is_verify("ADMIN|OPERATOR"))
	echo ",'$lg_program&nbsp;&nbsp;&nbsp;','show-menu=sekolah',,'',1";
if(is_verify("ADMIN|KEUANGAN|CEO"))
	echo ",'$lg_fee&nbsp;($onlinefeesta)&nbsp;&nbsp;&nbsp;','show-menu=yuran',,'',1";
if(is_verify("ADMIN|AKADEMIK")||($VARIANT=="MUSLEH"))
	echo ",'$lg_nilai&nbsp;&nbsp;&nbsp;','show-menu=exam',,'',1";
if(is_verify("ADMIN|AKADEMIK|GURU")&&($ON_HEADCOUNT==1))
	echo ",'Headcount&nbsp;&nbsp;&nbsp;','show-menu=headcount',,'',1";
if(is_verify("ADMIN|AKADEMIK")&&($ON_HAFAZAN==1))
	echo ",'Hafalan&nbsp;&nbsp;&nbsp;','show-menu=hafazan',,'',1";
if(is_verify("ADMIN|AKADEMIK|CEO"))
	echo ",'$lg_attendance&nbsp;&nbsp;','../adm/p.php?p=../eatt/att_cls_rep',,'Info Pelajar',1 ";
if(is_verify("ADMIN|AKADEMIK|KEUANGAN|CEO|HR"))
	echo ",'Orang&nbsp;Tua','../adm/p.php?p=../eparent/parent',,'Info Parent',1 ";
if(is_verify("ADMIN|AKADEMIK|KEUANGAN|CEO|HR"))
	echo ",'$lg_student&nbsp;&nbsp;','../adm/p.php?p=../stu/student',,'Info Pelajar',1 ";
if(is_verify("ADMIN|AKADEMIK|CEO|HR"))
		echo ",'$lg_report&nbsp;&nbsp;&nbsp;','show-menu=laporan',,'',1";
if(is_verify("ADMIN|AKADEMIK")&&($ON_TK==1))
	echo ",'Laporan&nbsp;$TK&nbsp;&nbsp;','show-menu=menutk',,'',1";		
if(is_verify("ADMIN"))
	echo ",'Tools&nbsp;&nbsp;&nbsp;','show-menu=tools',,'',1";
if(is_verify("ADMIN|AKADEMIK"))
	echo ",'Buku&nbsp;Siswa&nbsp;&nbsp;&nbsp;','show-menu=bukusiswa',,'',1";
	
?>
])

addmenu(menu=["bukusiswa",
	,,130,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"Buku Induk","../adm/p.php?p=bukuinduk",,,1
	,"Buku Klapper","../adm/p.php?p=bukuklapper",,,1
	,"Buku Mutasi","../adm/p.php?p=bukumutasi",,,1
	,"Buku Legger","../adm/p.php?p=bukulegger",,,1
	,"Legger Nilai Raport","../adm/p.php?p=../examrep/repexamstucls",,,1
	])

addmenu(menu=["tools",
	,,130,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"Configuration","../adm/p.php?p=prmset",,,1
	,"Pengumuman ke Parent Online","../adm/p.php?p=../news/news",,,1
	,"Lock Exam","../adm/p.php?p=prm_lockexam",,,1
	,"Sponser Record","../adm/p.php?p=prm_sponser&grp=sponser",,,1
	//,"Taqwim","../adm/p.php?p=../calendar/cal_set",,,1//
	,"System&nbsp;Log","../adm/p.php?p=sys_log",,,1
	,"SMS&nbsp;Log","../adm/p.php?p=sms_log",,,1
<?php if(is_verify("ROOT")) { ?>
	,"Staff&nbsp;Upload","../adm/p.php?p=../estaff/usr_csv_upload",,,1
	,"Student&nbsp;Upload","../adm/p.php?p=../stu/stu_csv_upload",,,1
<?php } ?>
	])

addmenu(menu=["menutk",
	,,130,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"Seting Nilai <?php echo $TK;?>","../adm/p.php?p=../sub/sub_construct_list",,,1
	,"Nilai & Rapot <?php echo $TK;?>","../adm/p.php?p=../tk/exam_stu_list",,,1
	])
addmenu(menu=["sekolah",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_program_setting;?>","../adm/p.php?p=sch",,,1
	,"<?php echo $lg_class_setting;?>","../adm/p.php?p=../cls/cls",,,1
	,"<?php echo $lg_subject_setting;?>","../adm/p.php?p=../sub/sub",,,1
<?php if($MENU_SEMESTER){?>
	,"<?php echo $lg_semester_management;?>","show-menu=semester",,,1
<?php }if($MENU_SESSION){?>
	,"<?php echo $lg_session_management;?>","show-menu=sesi",,,1
<?php } ?>
<?php if($MENU_TK){?>
	,"Seting TK","../adm/p.php?p=../sub/sub_construct_list",,,1
<?php } ?>
	])
addmenu(menu=["sesi",
	,,180,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_teacher_class;?>","../adm/p.php?p=../ses/ses_tea_cls",,,1
	,"<?php echo $lg_student_class;?>","../adm/p.php?p=../ses/ses_stu_change_cls",,,1
	,"<?php echo $lg_student_school;?>","../adm/p.php?p=../ses/ses_stu_change_school",,,1
	])
addmenu(menu=["semester",
	,,190,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_lecturer_subject;?>","../adm/p.php?p=../sem/sem_group",,,1
	,"<?php echo $lg_student_semester;?>","../adm/p.php?p=../sem/sem_student",,,1
	,"<?php echo $lg_student_subject;?>","../adm/p.php?p=../sem/sem_stusub",,,1
	,"<?php echo $lg_student_course;?>","../adm/p.php?p=../ses/ses_stu_change_school",,,1
	])
addmenu(menu=["yuran",
	,,160,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_fee_setting;?>","../adm/p.php?p=../efee/fee_rep_stu",,,1
	,"<?php echo $lg_payment_receipt ;?>","../adm/p.php?p=../efee/feetrans",,,1	
<?php if($USE_INVOICE){?>
	,"<?php echo $lg_fee_invoice;?>","../adm/p.php?p=../efee/feeinvoice",,,1
<?php } ?>
<?php if($USE_PARENT_PAYMENT){?>
	,"<?php echo $lg_payment;?> (<?php echo $lg_parent;?>)","../adm/p.php?p=../efee/feepay_parent_list",,,1
<?php }else{ ?>
	,"<?php echo $lg_payment;?> Cepat","../adm/p.php?p=../efee/feestulist",,,1
<?php } ?>
<?php 
	//echo ",'$lg_other_payment','../adm/p.php?p=../finance/receipt',,,1";
	echo ",'$lg_fee_online&nbsp;($onlinefeesta)','../adm/p.php?p=../efee/feeonline',,,1";
?>
<?php
if(is_verify("ADMIN|KEUANGAN")){
	echo ",'$lg_fee_report&nbsp;&nbsp;&nbsp;','show-menu=laporanyuran',,'',1";
	echo ",\"$lg_sale_report\",\"show-menu=laporanjualan\",,\"\",1";	
	//echo ",'$lg_invoice_report&nbsp;&nbsp;&nbsp;','show-menu=laporaninvoice',,'',1";
}?>
	])
addmenu(menu=["headcount",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_subject_analysis;?>","../adm/p.php?p=../ehc/hc_sub_stu",,,1
	,"<?php echo $lg_overall_analysis;?>","../adm/p.php?p=../ehc/hc_sub_all",,,1
	,"<?php echo $lg_student_analysis;?>","../adm/p.php?p=../ehc/hc_rank",,,1
	])
	
addmenu(menu=["exam",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_class_report;?>","../adm/p.php?p=../examrep/repexamstucls",,,1
	,"<?php echo "Raport Kurtilas";?>","show-menu=kurtilas",,"",1
<?php if($VARIANT=="MUSLEH"){?>
	,"SPPM Rekod","../adm/p.php?p=../sppm/sppmskor_markcls",,,1
<?php } ?>
	// ,"<?php echo $lg_attendance_sheet;?>","../adm/p.php?p=../exam/exam_initial",,,1
	])

addmenu(menu=["kurtilas",
	,,140,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo "Setting Nilai";?>","../adm/p.php?p=../exam/setting_kurtilas",,,1
	,"<?php echo "Nilai Raport";?>","../adm/p.php?p=../eatt/att_cls_rep",,,1
	])

addmenu(menu=["kedatangan",
	,,140,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_attendance_sheet;?>","../adm/p.php?p=../eatt/att_cls_rep",,,1
	])
	
addmenu(menu=["hafazan",
	,,130,1,"",style1,,"left",effect,,,,,,,,,,,,
<?php if($ON_HAFAZAN_SURAH){?>
	,"<?php echo "Catatan Surat";?>","../adm/p.php?p=../ehaf/hafazan_stu_surah",,,1
<?php } ?>
<?php if($ON_HAFAZAN_JUZUK){?>
	,"<?php echo $lg_juz_record;?>","../adm/p.php?p=../ehaf/hafazan_stu_list",,,1
	,"<?php echo $lg_juz_report;?>","show-menu=hafazan_rep",,"",1
<?php } ?>
<?php if($ON_SETTING_BALIIGHO){
	if($_SESSION['syslevel']=='ROOT' || $_SESSION['syslevel']=='ADMIN'){
	?>
	,"<?php echo "Setting Hafalan";?>","../adm/p.php?p=../ehaf/hafazan_list",,,1
	,"<?php echo "Nilai & Rapot Hafalan";?>","../adm/p.php?p=../ehaf/hafaz_stu_list",,,1
<?php }} ?>
	])
addmenu(menu=["hafazan_rep",
	,,130,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_monthly_report;?>","../adm/p.php?p=../ehaf/hafazan_rep_stu",,,1
	,"<?php echo $lg_juz_report;?>","../adm/p.php?p=../ehaf/hafazan_rep_juzuk",,,1
	,"<?php echo $lg_juz_analysis;?>","../adm/p.php?p=../ehaf/hafazan_rep_ana_juzuk",,,1	
	,"<?php echo $lg_grade_analysis;?>","../adm/p.php?p=../ehaf/hafazan_rep_gred",,,1
	])
	
addmenu(menu=["laporan",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_exam;?>","show-menu=laporanexam",,,1
	//,"<?php echo $lg_quranic;?>","show-menu=hafazan_rep",,"",1
<?php 
	if(is_verify("ADMIN|KEUANGAN"))
		echo ",'$lg_fee_report','show-menu=laporanyuran',,'',1";
?>
	,"<?php echo $lg_student_statistic;?>","show-menu=laporanpelajar",,"",1
<?php if($ON_LESSON_PLAN){?>	
	,"<?php echo $lg_teaching_lesson;?>","../adm/p.php?p=../elesson/elesson_rep",,,1
<?php } ?>
<?php if($ON_DAILY_READ){?>	
	,"<?php echo $lg_daily_reading;?>","../adm/p.php?p=../dailyread/attstu",,,1
<?php } ?>	
	])
	
addmenu(menu=["laporanpelajar",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_total_student;?>","../adm/p.php?p=../stu/rep_statistic_stu",,,1
	,"<?php echo $lg_statistic;?>","../adm/p.php?p=../stu/rep_stat_stu_year",,,1
	])
addmenu(menu=["laporanexam",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,
	,"<?php echo $lg_class_report;?>","../adm/p.php?p=../examrep/repexamstucls",,,1
<?php if($SI_EXAM_REPORT_SPPM_SKOR){?>
	,"SPPM Report","../adm/p.php?p=../examrep/repexamsppmskor",,,1
<?php } ?>
	,"<?php echo $lg_subject_report;?>","../adm/p.php?p=../examrep/rep_exam_sub_stu",,,1
	,"<?php echo $lg_student_report;?>","../adm/p.php?p=../exam/examrank",,,1
	,"<?php echo $lg_a_report;?>","../adm/p.php?p=../examrep/rep_skora_exam",,,1
	,"<?php echo $lg_subject_analysis;?>","../adm/p.php?p=../examrep/rep_ana_sub_all",,,1
	])
addmenu(menu=["laporanyuran",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,	
	,"<?php echo $lg_daily_report;?>","../adm/p.php?p=../efee/fee_rep_day",,,1
	,"<?php echo $lg_monthly_report;?>","../adm/p.php?p=../efee/feesum",,,1
	,"<?php echo "Laporan Tahunan";?>","../adm/p.php?p=../efee/feetarget",,,1
	,"<?php echo $lg_outstanding_report;?>","../adm/p.php?p=../efee/feeout",,,1	
	])
	
addmenu(menu=["laporanjualan",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,	
	,"<?php echo $lg_sale_record;?>","p.php?p=../efee/sale_item_rep",,,1
	,"<?php echo $lg_student_record;?>","p.php?p=../efee/sales_item_log",,,1
	])
	
addmenu(menu=["laporaninvoice",
	,,150,1,"",style1,,"left",effect,,,,,,,,,,,,	
	,"<?php echo $lg_invoice_record;?>","../adm/p.php?p=../efee/feeinvoice_trans",,,1
	,"<?php echo $lg_invoice_report;?>","../adm/p.php?p=../efee/feeinvoice_rep",,,1
	])



dumpmenus()
