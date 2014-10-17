<?php

class ajaxMailSend {

	public function __construct() 
	{	

		if($this->checkRequest()) {
			$this->returnStatus(false);
		} else {
			$this -> getSiteConfig();
			$config 	= 	$this -> getConfig();
			$db 		= 	$this -> getDB();
			$emailfrom 	= 	$this -> getEmailFrom($config);
			$emailto 	= 	$this -> getEmailTo($db);
			$headers 	= 	$this -> getHeaders($emailfrom);
			$subject  	= 	$this -> getSubject();
			$content  	= 	$this -> getContent();
			$this-> sendEmail($emailto, $headers, $subject, $content);
		}
	}

	private function getSiteConfig() {
		define( '_JEXEC', 1 );
		define( '_VALID_MOS', 1 );
		define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));
		define( 'DS', DIRECTORY_SEPARATOR );
		require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
		require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	}

	private function getConfig() {
		return JFactory::getConfig("site");
	}

	private function getDB() {
		return JFactory::getDbo();
	}

	/* Avoid email sending with file direct access */
	private function checkRequest() 
	{
		if (empty($_REQUEST['subject']) OR  empty($_REQUEST['EMail']) OR empty($_REQUEST['Message']) ) {
			return true;
		}
	}

	/* 
	* Return the destination email address taken from the module parameter.
	* The database connection is needed instead of JModuleHelper::getModule(),
	* in fact it could not work if the module not assigned to all pages.
	*/
	private function getEmailTo($db) {
		$query = $db->getQuery(true);

		$query->select
		(
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
		return $params->get("emailto");
	}

	/*
	* Get the sender address from the site configuration
	*/
	private function getEmailFrom($config) {
		return $config->get( 'mailfrom' );
	}

	private function getHeaders($fromMail) {
		$headers  = "MIME-Version: 1.0 \n";
		$headers .= "Content-type: text/html; charset=utf-8 \n";
		$headers .= "From: "     . $fromMail. " \n".
		"Reply-To: " . $fromMail. " \n".
		"X-Mailer: PHP/" . phpversion();
		return $headers;
	}

	private function getSubject() {
		return stripslashes(strip_tags($_REQUEST['subject']));
	}

	private function getContent() {
		$mailContent = '';
		foreach($_POST as $key => $val){
				$_POST[$key] = strip_tags($val);
				$mailContent .= $key.' : '.$_POST[$key].'<br>';
		}
		$mailContent = $mailContent != "" ? stripslashes($mailContent) : '';
		return $mailContent;
	}

	private function sendEmail($to, $header, $subject, $content) {
		if(mail($to, $subject, $content, $header)){
			$this->returnStatus(true);
		}else{
			$this->returnStatus(false);
		}
	}

	private function returnStatus($value) {
		if ($value == true) { echo "1"; } else { die(0); };
	}
}

new ajaxMailSend();
?>