<?php
function xgate_send_sms($ip,$port,$user,$pass,$gw,$tel,$mtid,$moid,$mtype,$smsmsg,$bil,$pkey,$app)
{
			   //extract data from the post
				extract($_POST);
				$mtype="text";
				$key=$user.$pass.$tel;
				$hkey=sha1("$key");
				//echo "$key :::: $hkey";
				//set POST variables
				//$url='http://sms.net.my/curlrx.php';
				$url='http://www.infoblast.com.my/openapi/sendmsg.php';
				$fields = array(
					'username'=>urlencode($user),
					'password'=>urlencode(sha1($pass)),
					'msgtype'=>urlencode($mtype),
					'message'=>urlencode($smsmsg),
					'to'=>urlencode($tel),
					'hashkey'=>urlencode($hkey)
				);
				//url-ify the data for the POST
				foreach($fields as $key=>$value) { $fields_string .=
				$key.'='.$value.'&'; }
				rtrim($fields_string,'&');
				//open connection
				$ch = curl_init();				
				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_POST,count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
				//execute post
				$result = curl_exec($ch);
				//close connection
				curl_close($ch);
				//echo $result;
				//echo "\n\r";
				return 1;
}


?>
