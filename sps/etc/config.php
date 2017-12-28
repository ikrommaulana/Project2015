<?php
//CONFIG SPS
$VERSION="7.0.0";
$DB_NAME="simas_alambogor";
$MYLIB   ="../../../mylib";
$MYINI   ="../../../mylib";/*..(local ini) or ../../../mylib(global ini) */
$MYOBJ   ="../../../myobj";
$LG="INDO";// BI or BM
$BASE_COUNTRY="Indonesia";//TRUE
$BASE_URL="http://simas.kuantum-mirkro.com/alambogor";//TRUE

$VARIANT="";//MUSLEH: set this to "blank" if note musleh school
$ON_TK=1;  //0:this will show top menu
$ON_TK_KOMENTAR=1;
$TK="Alam";
$MENU_TOP_ON=1;  //0:this will show top menu
$MENU_SEMESTER=0; //1:this will show semester management for college
$MENU_SESSION=1;  //1:this will show session management for school
$CALENDAR_GLOBAL=0;//DEFAULT 0
$ONLINE_MSG_GLOBAL=1;//TRUE

/** internal system **/
$system			="Sistem Informasi Manajemen Sekolah";
$syscode		="SIMAS";
$copyright		="&copy; PT. Kuantum Mikroteknik";

/** School Setting **/
$registered="ALAM BOGOR";
$licensee="PT. Kuantum Mikroteknik";
$organization_name="ALAM BOGOR";
$organization_short_name="ALAMBOGOR";
$organization_logo="../logo/alambogor.png";//cms,sps,isis80
$default_logo="../logo/alambogor.png";
$system_logo="../img/isis80.png";

$dir_image_student="../usr_images/student/";
$dir_image_user="../usr_images/staff/";
$web_title	="$syscode - $organization_name";
$web_footer	="$registered";

/** Student Setting **/
$si_student_global_id=0;
/** Staff Setting **/
$si_staff_auto_id=0;

/** EMAIL SETTING **/
$MEL_REPLY="noreply@simas.kuantum-mikro.com";
$MEL_REPLY_NAME="$organization_name";
$MEL_ISSMTP=0;//default 0

/** Global Header **/
$HEADER_NAME_USE_MASTER=0;
$GLO_ADD="Jl. Rawa Gede Wetan NO.7 007/02, Jatimelati, Pondok Melati, Jakarta, Indonesia";
$GLO_TEL="021-84592429";
$GLO_FAX="021-84592429";
$GLO_WEB="www.iqro.or.id";
$GLO_LOGO="http://simas.kuantum-mikro.com/iqro/sps/logo/alambogor.png";


/** Fee Setting **/
$SI_FEE_ADHOC=1;
//$FN_FEEPAY="feepay_v3iis"; 		//v3:default v26:nouse 
//$FN_FEEPAY="feepay_v1";
$FN_FEEPAY="feepay_v3";
//$FN_FEEPAY="feepay_parent";
//$FN_FEECFG="feecfg_v1"; 		//v1=by value(default),v3=by discount
$FN_FEECFG="feecfg_v3";
$FN_FEERESIT="feeresit_v3";	//v3,_parent	default v3
//$FN_FEERESIT="feeresit_parent";
$FN_FEEINV="feeinvoice_gen_iis"; 		//v3:default v26:nouse 
//$ISPARENT_ACC=1; 				//default 0
$USE_SCHOOL_RESIT_NUM=1;		//default 0
/** PAYMENT ENGINE **/
//$USE_PARENT_PAYMENT=1;      	//1=Fee base parent ..use $FN_FEEPAY="feepay_parent"; 0=Fee base on student .. $FN_FEEPAY="feepay_v26";
//$PARENT_ALL_SCHOOL=1; 	        //0=parent can pay base on child on one school only , 1=parent can pay fee for child on all school
//$USE_INVOICE=1;					//1=enable invoice, 0=disbled
/** FEE REPORT ENGINE **/
$FEE_REPORT_ALL_SCHOOL=0;		//def 0
$FEE_REPORT_USE_ACC=0;			//def 0
$ui_fee_footer="-Cetakan Komputer-";
$ui_fee_footer_bank="*Account No: -- (Bank Islam) atau  -- (Bank Muamalat)";
$si_fee_var_bangi=0;

/** STUDENT SETTING **/
$STU_ALLOW_DELETE=0;				//def 0
$STU_SEARCH_MOTHER=0;				//def 0
$SHOW_EPELAJAR_TO_ALL_TEACHER=1;	//def 0
/** EXAM SETTING **/
$SI_EXAM_INDIVIDU_MARK=1;
$SI_EXAM_REPORT_SPPM_SKOR=0;
$SI_EXAM_REPORT_SHOW_FINAL=1;
$SI_EXAM_RANK_USE_PERCENT=0;//default GP
$SI_REPORT_COMPARE_SCHOOL=1;
$si_exam_use_th=0;
$si_exam_use_gpp=1;
$ON_ELAPORAN_GURU=0;

/** REGISTRATION SETTING **/
$ON_EREGISTER=1;
$EREG_SHOW_ACADEMIC=0;
$EREG_SHOW_UPSR=0;
$EREG_SHOW_EXAMLAIN=0;
$EREG_SHOW_QURANIC=0;
$EREG_MULTI_SESSION=0;// pagi/petang	
$EREG_QUESTIONAIR=0;// pagi/petang
$ADDITIONAL_DETAIL=1;
$SHOW_ANAK_NEGERI=0;// number	
$ANAK_NEGERI="";// number	KELANTAN
$REG_SHOW_TRANSPORT=0;
$REG_SHOW_DISTRICT=0;
$REG_ADVANCE=0;
$REG_FILE="reg.php";

/** Class Setting **/
$CLASS_EDIT_ENABLE=0;
$SUBJECT_EDIT_ENABLE=1;
$SYSTEM_ALLOW_STUDENT_MULTI_CLASS=0;

/** MODULE SETTING**/
$ON_MY_CLASS=1;
$ON_DASHBOARD=1;
$ON_TUTOR=0;
$ON_JOB_PROGRESS=0;
$ON_SHARED_DOC=0;
$ON_SALARY=0;
$ON_TRAINING=0;
$ON_CLAIM=0;
$ON_STAFF_AWARD=1;
$ON_SYSTEM_ALERT=0;
$ON_LEAVE=0;
$ON_ESTAFF=1;
$ON_JOB=0;
$ON_BOOKING=0;
$ON_FRANCHISE=0;
$ON_MAINTENANCE=0;
$ON_GA=0;
$ON_ASSET=1;
$ON_CRM=0;
$ON_PROFILING=0;
$ON_FINANCE=0;
$ON_HRMS=0;

/** SCHOOL ADVANCE MODULE**/
$ON_KOQ=1;
$ON_DISCIPLINE=1;
$ON_HOSTEL=1;
$ON_HEADCOUNT=0;
$ON_HOMEWORK=1;
$ON_ALUMNI=0;
$ON_ATTENDANCE_CLASS=0;
$ON_LESSON_PLAN=1;
$ON_DAILY_READ=0;

/** HAFAZAN SETTING **/
$ON_HAFAZAN=1;
$ON_HAFAZAN_SURAH=1;
$ON_HAFAZAN_JUZUK=0;
$ON_SETTING_BALIIGHO=1;
$ON_SYAATHIR=0;
$ON_TILAWAH=0;
$ON_QIRAATI=0;
$ON_JUZUKAMMA=0;
$ON_QURAN=1;


/** EXTERNAL APPS **/
$ON_QURANEXPLORER=1;
$ON_WEBMAIL=0;
$ON_CCTV=1;
$CCTV_ENABLE=0;
$CCTV_LOCAL_PATH="192.168.1.200:8080";
$CCTV_INTERNET_PATH="awfa.dvrlink.net:8080";

/** SMS SETTING **/
//$xgateuser="675Iasas8";
//$xgatepass="89Hg767J4suH";
$xgateip="175.103.48.29";
//$xgateport="28011";
//$xgateport="28015";
$xgateport="80";
$xgategw="36600";
$xgatekey="SIMAS";
$ON_XGATE=1;
$SMS_BILL_BYSCHOOL=1;

/** Languange Setting **/
$lg_sekolah_tt1="Setiausaha HEA";
$lg_sekolah_tt2="Penolong Pengetua";
$lg_sekolah_tt3="Pengerusi Lembaga Pengarah";


/** Color Setting **/
$bghijau	="#00FF99"; //hadir
$bgkuning	="#FFFF99"; //cuti minggu // #FFFF00 terang lagi
$bgmerah	="#FF3333"; //tak hadir
$bgbiru		="#99CCFF"; //cuti sem
$bgkelabu	="#999999"; //tak taksir
$bgpink		="#FFCCFF"; //cuti umum
$bgputih	="#FFFFFF"; //belum set
$bgoren		="#FFCC99"; //belum set
$bglred		="#FFCCCC";
$bglgreen	="#66FFDD";
$bglyellow	="#FFFFCC";
$bglblue	="#66EEFF";
$bglgray	="#EEEEEE";

$color[0]="gold";
$color[1]="pink3";
$color[2]="deepskyblue4";
$color[3]="yellow";
$color[4]="gray7";
$color[5]="ivory2";
$color[6]="mediumpurpl";
$color[7]="orangered";
$color[8]="salmon";
$color[9]="coral";
$color[10]="thistle";
$color[11]="tan";

//main menu color conf
$mnu_mover="4b0082";//default:4b0082 imtiaz:69090A //hijau=3DD2A3
?>
