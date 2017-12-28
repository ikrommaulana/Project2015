<?php
$vmod="v7.0.0";
$vdate="110701";
include_once('../etc/db.php');
include_once('../etc/session.php');

    $operation=$_POST['operation'];
	$adm = $_SESSION['username'];
        
        
        //sistem information
        $uid=$_REQUEST['uid'];
        $uidx       = $_POST['uidx'];
        $password   = md5('123456');
        $newpass    = $_POST['newpass'];
        $syslevel   = $_POST['syslevel'];
        $status     = $_POST['status'];
        $sid        = $_POST['sid'];
        $indukno    = $_POST['indukno'];
        
    //personal info
    $name       = $_POST['name'];
    $bplace     = $_POST['bplace'];
    $religion   = $_POST['religion'];
    $address    = $_POST['address'];
    $hstatus    = $_POST['hstatus'];
    $sdt        = $_POST['sdt'];
    $edt        = $_POST['edt'];
    $position   = $_POST['position'];
    $mel	= $_POST['mel'];
    $hp 	= $_POST['hp'];
    
        //pendidikan
        $qx		= $_POST['pen1'];
	$pen1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
	$qx		= $_POST['pen2'];
        $pen2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
	$qx		= $_POST['pen3'];
	$pen3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
	$qx		= $_POST['pen4'];
	$pen4		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
	$qx		= $_POST['pen5'];
	$pen5		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
	$qx		= $_POST['pen6'];
	$pen6		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
    
    //kursus
    $qx		= $_POST['k1'];
    $k1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
    $qx		= $_POST['k2'];
    $k2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
    $qx		= $_POST['k3'];
    $k3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
    $qx		= $_POST['k4'];
    $k4		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
	
    $qx		= $_POST['k5'];
    $k5		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
    
            //bidang studi
            $qx		= $_POST['b1'];
            $b1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
            $qx		= $_POST['b2'];
            $b2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
            $qx		= $_POST['b3'];
            $b3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
            
    //jabatan non formal
    $qx		= $_POST['p1'];
    $p1		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
    $qx		= $_POST['p2'];
    $p2		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
    $qx		= $_POST['p3'];
    $p3		= addslashes("$qx[0]|$qx[1]|$qx[2]");
    
    
            //jabatan formal
            
            $qx		= $_POST['f1'];
            $f1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f2'];
            $f2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f3'];
            $f3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f4'];
            $f4		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f5'];
            $f5		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f6'];
            $f6		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f7'];
            $f7		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f8'];
            $f8		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f9'];
            $f9		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
            $qx		= $_POST['f10'];
            $f10	= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]|$qx[5]|$qx[6]|$qx[7]");
            
    $stajabatan = $_POST['stajabatan'];
    $nip        = $_POST['nip'];
    $karpeg     = $_POST['karpeg'];
    $place1     = $_POST['place1'];
    $place2     = $_POST['place2'];
	$nuptk=$_POST['nuptk'];
    
            //keterangan fisik
            $height     = $_POST['height'];
            $weight     = $_POST['weight'];
            $face       = $_POST['face'];
            $hair       = $_POST['hair'];
            $bloodtype  = $_POST['bloodtype'];
            $sick1      = $_POST['sick1'];
            $sick2      = $_POST['sick2'];
            $differ     = $_POST['differ'];
        
        $sibtotal   = $_POST['sibtotal'];
        $sibboy     = $_POST['sibboy'];
        $sibgirl    = $_POST['sibgirl'];
        $sibno      = $_POST['sibno'];
        $staffstatus= $_POST['staffstatus'];
        
        
                //susunan keluarga
                $qx		= $_POST['s1'];
                $s1		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s2'];
                $s2		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s3'];
                $s3		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s4'];
                $s4		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s5'];
                $s5		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s6'];
                $s6		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s7'];
                $s7		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s8'];
                $s8		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
                $qx		= $_POST['s9'];
                $s9		= addslashes("$qx[0]|$qx[1]|$qx[2]");
                
        //organisasi
        $qx		= $_POST['o1'];
        $o1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
        $qx		= $_POST['o2'];
        $o2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
        $qx		= $_POST['o3'];
        $o3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
        $qx		= $_POST['o4'];
        $o4		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
                
        $qx		= $_POST['o5'];
        $o5		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]");
        
                //hobby
                $qx		= $_POST['h1'];
                $h1		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]");
                        
                $qx		= $_POST['h2'];
                $h2		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]");
                        
                $qx		= $_POST['h3'];
                $h3		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]");
                        
                $qx		= $_POST['h4'];
                $h4		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]");
                        
                $qx		= $_POST['h5'];
                $h5		= addslashes("$qx[0]|$qx[1]|$qx[2]|$qx[3]|$qx[4]");
                        
    $langua     = $_POST['langua'];
    $piagam1    = $_POST['piagam1'];
    $piagam2    = $_POST['piagam2'];
    
                //kesan kepala sek
                $effect      = $_POST['effect'];
                
                $xplain1     = $_POST['xplain1'];
                $xplain2     = $_POST['xplain2'];
                $xplain3     = $_POST['xplain3'];
                $xplain4     = $_POST['xplain4'];
                $xplain5     = $_POST['xplain5'];
                
                $reason      = $_POST['reason'];
                
                
                
             
                
                
   
    
    if($operation=="insert"){
        
        
        $sql="insert into usr(cdate,adm,ts,uid,pass,syslevel,status,sch_id,
        name,bplace,religion,addr,hstatus,jobstart,jobend,job,mel,hp,
        pen1,pen2,pen3,pen4,pen5,pen6,
        k1,k2,k3,k4,k5,
        b1,b2,b3,
        p1,p2,p3,
        f1,f2,f3,f4,f5,f6,f7,f8,f9,f10,
        s1,s2,s3,s4,s5,s6,s7,s8,s9,
        o1,o2,o3,o4,o5,
        h1,h2,h3,h4,h5,
        langua,piagam1,piagam2,
        effect,xplain1,xplain2,xplain3,xplain4,xplain5,reason,
        stajabatan,ic,karpeg,place1,place2,height,weight,face,hair,bloodtype,sick1,sick2,differ,
        sibtotal,sibboy,sibgirl,sibno,staffstatus,indukno,nuptk
        )
        values(now(),'$adm',now(),'$uidx','$password','$syslevel','$status','$sid',
        '$name','$bplace','$religion','$address','$hstatus','$sdt','$edt','$position','$mel','$hp',
        '$pen1','$pen2','$pen3','$pen4','$pen5','$pen6',
        '$k1','$k2','$k3','$k4','$k5',
        '$b1','$b2','$b3',
        '$p1','$p2','$p3',
        '$f1','$f2','$f3','$f4','$f5','$f6','$f7','$f8','$f9','$f10',
        '$s1','$s2','$s3','$s4','$s5','$s6','$s7','$s8','$s9',
        '$o1','$o2','$o3','$o4','$o5',
        '$h1','$h2','$h3','$h4','$h5',
        '$langua','$piagam1','$piagam2',
        '$effect','$xplain1','$xplain2','$xplain3','$xplain4','$xplain5','$reason',
        '$stajabatan','$nip','$karpeg','$place1','$place2','$height','$weight','$face','$hair','$bloodtype','$sick1','$sick2','$differ',
        '$sibtotal','$sibboy','$sibgirl','$sibno','$staffstatus','$indukno','$nuptk'
        
        )";
        mysql_query($sql)or die("$sql failed:".mysql_error());

            $f="<font color=blue>&lt; SUCCESSFULLY REGISTERED $strpas &gt;</font>";
            $checkupload=1;
        
    }else if($operation=="update"){
        
        $sql="update usr set syslevel='$syslevel',status='$status',sch_id='$sid',
        name='$name',bplace='$bplace',religion='$religion',addr='$address',hstatus='$hstatus',jobstart='$sdt',jobend='$edt',job='$position',mel='$mel',hp='$hp',
        pen1='$pen1',pen2='$pen2',pen3='$pen3',pen4='$pen4',pen5='$pen5',pen6='$pen6',
        k1='$k1',k2='$k2',k3='$k3',k4='$k4',k5='$k5',
        b1='$b1',b2='$b2',b3='$b3',
        p1='$p1',p2='$p2',p3='$p3',
        f1='$f1',f2='$f2',f3='$f3',f4='$f4',f5='$f5',f6='$f6',f7='$f7',f8='$f8',f9='$f9',f10='$f10',
        s1='$s1',s2='$s2',s3='$s3',s4='$s4',s5='$s5',s6='$s6',s7='$s7',s8='$s8',s9='$s9',
        o1='$o1',o2='$o2',o3='$o3',o4='$o4',o5='$o5',
        h1='$h1',h2='$h2',h3='$h3',h4='$h4',h5='$h5',
        langua='$langua',piagam1='$piagam1',piagam2='$piagam2',
        effect='$effect',xplain1='$xplain1',xplain2='$xplain2',xplain3='$xplain3',xplain4='$xplain4',xplain5='$xplain5',reason='$reason',
        stajabatan='$stajabatan',ic='$nip',karpeg='$karpeg',place1='$place1',place2='$place2',height='$height',weight='$weight',face='$face',hair='$hair',bloodtype='$bloodtype',sick1='$sick1',sick2='$sick2',differ='$differ',
        sibtotal='$sibtotal',sibboy='$sibboy',sibgirl='$sibgirl',sibno='$sibno',staffstatus='$staffstatus',indukno='$indukno',nuptk='$nuptk' where uid='$uid'";
    mysql_query($sql)or die("$sql failed:".mysql_error());
	    
            if($newpass!=""){
                $new=md5($newpass);
             $sqlp="update usr set pass='$new' where uid='$uid'";
            mysql_query($sqlp)or die("$sqlp failed:".mysql_error());
            
            }
            $f="<font color=blue>&lt; SUCCESSFULLY UPDATED $strpas &gt;</font>";
            $checkupload=1;
    
    }
     
    
	if($checkupload){
		$fn=basename( $_FILES['file']['name']);
		if($fn!=""){
			$sql="select file from usr where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$fname=$row['file'];
			if($fname!=""){
				$tmp=$dir_image_user.$fname;
				unlink($tmp);
			}
     		$ext=substr($fn,-4,4);
			$fn=sprintf("%s%s",$uid,$ext);
			$target_path =$dir_image_user.$fn;
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		    	$sql="update usr set file='$fn' where uid='$uid'";
	    	  	mysql_query($sql)or die("query failed:".mysql_error());
			}
			else{
				echo "Sorry. Problem uploading image file. Make sure file less then 100kb. TQ";
				exit;
			}
		}
	}           
                
            
    
            
?>