<?php
/**
 * @autor       Valentín García
 * @website     www.valentingarcia.com.mx
 * @package		Joomla.Site
 * @subpackage	mod_circle_contact
 * @copyright	Copyright (C) 2012 Valentín García. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
	  
//library
jimport('joomla.application.module.helper');

//vars
$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));
$c_emailto = explode( '@', $params->get('emailto') );
//$c_emailto = $params->get('emailto');
$c_subject = $params->get('subject');
$c_telephone = $params->get('telephone');
$c_address = $params->get('address');
$c_facebook = $params->get('facebook');
$c_twitter = $params->get('twitter');
$c_pinterest = $params->get('pinterest');
$c_google = $params->get('google');
$c_rss = $params->get('rss');
$c_map_latlon = explode( ',', $params->get('map_latlon') );
$c_map_title = htmlspecialchars( $params->get('map_title') );

if( $c_emailto and $c_subject and $params->get('map_latlon') ){// START A1.

echo '<div class="row-fluid">
	<div class="span12">
		<div id="map"></div>
			<div class="met_contact_map_box met_contact_map_box_sizing met_bgcolor3 met_color2">
				<div class="met_contact_info">';
				
					if( $c_telephone or $c_address ){
						echo '<h3 class="met_bold_one met_color">' . JText::_('VG_CONTACT_INFORMATION') . '</h3>';
					}
					
					if( $c_telephone ) echo '<i class="icon-phone"></i> <span>' . JText::_('VG_CONTACT_TELEPHONE') . '</span>: ' . $c_telephone . '<br>';
					/*if( $c_emailto ) echo '<i class="icon-envelope"></i> <span>' . JText::_('VG_CONTACT_EMAIL') . '</span>: ' . $c_emailto . '<br>';*/
					if( $c_address ) echo '<i class="icon-map-marker"></i> <span>' . JText::_('VG_CONTACT_ADDRES') . '</span>: ' . $c_address;
				
				echo '</div>

				<div class="met_contact_socials clearfix">';
				
					if( $c_facebook or $c_twitter or $c_pinterest or $c_google or $c_rss ){
						echo '<h3 class="met_bold_one met_color">' . JText::_('VG_CONTACT_STAY_IN_TOUCH') . '</h3>';
					}
					
					if( $c_facebook ) echo '<a class="met_color2 met_color_transition" target="_blank" href="' . $c_facebook . '"><i class="icon-facebook"></i></a>';
					if( $c_twitter ) echo '<a class="met_color2 met_color_transition" target="_blank" href="' . $c_twitter . '"><i class="icon-twitter"></i></a>';
					if( $c_pinterest ) echo '<a class="met_color2 met_color_transition" target="_blank" href="' . $c_pinterest . '"><i class="icon-pinterest"></i></a>';
					if( $c_google ) echo '<a class="met_color2 met_color_transition" target="_blank" href="' . $c_google . '"><i class="icon-google-plus"></i></a>';
					if( $c_rss ) echo '<a class="met_color2 met_color_transition" target="_blank" href="' . $c_rss . '"><i class="icon-rss"></i></a>';
				
				echo '</div>

				<div class="met_contact_form">
					<h3 class="met_bold_one met_color">' . JText::_('VG_CONTACT_TITLE') . '</h3>
					<form method="post" action="' . JURI::base() . 'modules/mod_circle_contact/ajax/send.php" class="met_contact_form clearfix" id="met_contact_form">
						<input type="hidden" name="emailto1" value="' . $c_emailto[0] . '" />
						<input type="hidden" name="emailto2" value="' . $c_emailto[1] . '" />
						<input type="hidden" name="subject" value="' . $c_subject . '" />
						<input type="text" name="NameSurname" required="" placeholder="' . JText::_('VG_CONTACT_NAME') . '">
						<input type="email" name="EMail" required="" placeholder="' . JText::_('VG_CONTACT_EMAIL') . '">
						<textarea name="Message" required="" placeholder="' . JText::_('VG_CONTACT_MESSAGE') . '"></textarea>
						<div class="met_contact_thank_you">' . JText::_('VG_CONTACT_SUCCESS') . '</div>
						<input type="submit" value="' . JText::_('VG_CONTACT_SEND') . '" class="met_bgcolor met_color2">
					</form>
				</div>

			</div>
	</div>
</div>';	

}else{
	echo '<p>' . JText::_('VG_CONTACT_ERROR_SET_EMAIL') . '</p>';
}// END A1.
?>

<script>
jQuery(document).ready(function(){
	try{
		if(jQuery(window).width() < 800 && jQuery('.met_contact_map_box').length > 0){
			var box = jQuery('.met_contact_map_box').html();
			var metContent = jQuery('.met_contact_map_box').parents('.met_content');
			jQuery('.met_contact_map_box').remove();
			metContent.append('<div class="row-fluid"><div class="span12"><div class="met_contact_map_box met_bgcolor3 met_color2" style="position: relative; top: 0; left: 0; right: auto; max-width: inherit;">'+box+'</div></div></div>');
		}
	}catch(e){
		console.log(e);
	}
	
	jQuery('#met_contact_form').bind('submit', function(){
		var form    = jQuery(this);
		var me      = jQuery(this).children('input[type=submit]');

		me.attr('disabled','disabled');

		jQuery.ajax({
			type: "POST",
			url: "<?php echo JURI::base(); ?>modules/mod_circle_contact/ajax/send.php",
			data: form.serialize(),
			success: function(returnedInfo){

				var message = jQuery('.met_contact_thank_you');
				returnedInfo == 1 ? message.show() : message.html('<?php echo JText::_('VG_CONTACT_ERROR_SERVER'); ?>').show();
				setInterval(function(){message.fadeOut()},5000);
				me.removeAttr('disabled');

			}
		});
		return false;
	});
	
	if(jQuery('#map').length > 0){
		var map = new GMaps({
			div: '#map',
			lat: <?php echo $c_map_latlon[0]; ?>,
			lng: <?php echo $c_map_latlon[1]; ?>
		});
		map.addMarker({
			lat: <?php echo $c_map_latlon[0]; ?>,
			lng: <?php echo $c_map_latlon[1]; ?>,
			title: 'Contact Form'
		});
	}
	
});
</script>