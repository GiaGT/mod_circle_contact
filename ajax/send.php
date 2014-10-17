<?php
// Configuration Settings
$address = $_REQUEST['emailto1'] . '@' . $_REQUEST['emailto2'];
$e_subject = $_REQUEST['subject'];
$fromMail = $_REQUEST['EMail'];
	
if(!isset($_POST["nl_sub"])){
//WELCOME AJAX CONTACT FORM MAILER

	//Stripping tags so if even user writes tags like <div></div> or <a href=""></a>, these are just getting cleared.
	//Making ready the content for e-mail by the way
	$mailContent = '';
	foreach($_POST as $key => $val){

		$_POST[$key] = strip_tags($val);
		$mailContent .= $key.' : '.$_POST[$key].'<br>';
	}

	//Getting rid of backslashes
	$mailContent = $mailContent != "" ? stripslashes($mailContent) : '';

	$headers  = "MIME-Version: 1.0 \n";
	$headers .= "Content-type: text/html; charset=utf-8 \n";
	$headers .= "From: "     . $fromMail. " \n".
		"Reply-To: " . $fromMail. " \n".
		"X-Mailer: PHP/" . phpversion();


	if(mail($address, $e_subject, $mailContent, $headers)){
		echo '1';
	}else{
		echo '0';
	}
}

?>