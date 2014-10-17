<?php
define( '_JEXEC', 1 );
define( '_VALID_MOS', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$config =& JFactory::getConfig("site");

$db = JFactory::getDbo();
$query = $db->getQuery(true);

$query->select(
		$db->quoteName(array('params')))
      	->from($db->quoteName('#__modules'))
      	->where($db->quoteName('module') . ' = '. $db->quote('mod_circle_contact')
   		);

$db->setQuery($query,0,1);
$rows = $db->loadObjectList();

foreach ($rows as $row) {
   $row->params;
}

$params = new JRegistry;
$params->loadString($row->params, 'JSON');

// Configuration Settings
$address = $params->get("emailto");
$e_subject = $_REQUEST['subject'];
$fromMail = $config->get( 'mailfrom' );
	
if(!isset($_POST["nl_sub"])){

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