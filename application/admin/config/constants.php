<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/** cache days ***/
define('CACHE_VALID_SEC',864000);

define('NO_RIGHTS',"You don't have any rights to access this page.");
define('NO_RIGHTS_DASH',"You don't have any rights to access admin.");


/*Messages*/

/*Admin login message*/
define('INVALID_USERNAME','<strong>Username</strong> and/or <strong>Password</strong> are wrong.');
define('LOGOUT_SUCCESS','You have logged out successfully.');
define('EMAIL_NOT_FOUND','Email id not found.');
define('FORGET_SUCCESS','Please check your email for reset the password.');
define('RESET_SUCCESS','Your password has been successfully reset.');
define('PASS_RESET_FAIL','Password reset failed.');
define('EXPIRED_RESET_LINK','Reset password link has been expired.');
/*End admin login message*/


/*List admin message*/
define('ADD_NEW_RECORD','Record has been added successfully.');
define('UPDATE_RECORD','Record has been updated successfully.');
define('DELETE_RECORD','Record has been deleted successfully.');
define('NO_DELETE_RECORD','Company User can not be deleted.');
define('ACTIVE_RECORD','Record has been activated successfully.');
define('INACTIVE_RECORD','Record has been inactivated successfully.');
define('ACTIVE_STAFF_FAILED','The Staff can not be activated first activate related Service Provider.');
define('ASSIGN_RIGHTS','Record has been updated successfully.');
define('RESET_SENT','Reset password email has been sent successfully.');
/*End of list admin message*/

/*List admin message*/
define('Approval_RECORD','Record has been Approval successfully.');
define('Pending_RECORD','Record has been Pending successfully.');
define('Cancled_RECORD','Record has been Cancled successfully.');
//*End of list admin message*/

/*Country Message*/
define('ADD_NEW_COUNTRY','Record has been added successfully.');
define('UPDATE_COUNTRY','Record has been updated successfully.');
define('DELETE_COUNTRY','Record has been deleted successfully.');
define('ACTIVE_COUNTRY','Record has been activated successfully.');
define('INACTIVE_COUNTRY','Record has been inactivated successfully.');
define('EXIST_COUNTRY','There is an existing country associated with this Name.');
/*End country message*/

/*FEATURE Message*/
define('ADD_NEW_FEATURE','Record has been added successfully.');
define('UPDATE_FEATURE','Record has been updated successfully.');
define('DELETE_FEATURE','Record has been deleted successfully.');
define('ACTIVE_FEATURE','Record has been activated successfully.');
define('INACTIVE_FEATURE','Record has been inactivated successfully.');
define('EXIST_FEATURE','There is an existing country associated with this Name.');
/*End country message*/

/*Country Message*/
define('ADD_NEW_INQ','Record has been added successfully.');
define('UPDATE_INQ','Record has been updated successfully.');
define('DELETE_INQ','Record has been deleted successfully.');
define('ACTIVE_INQ','Record has been activated successfully.');
define('INACTIVE_INQ','Record has been inactivated successfully.');
define('EXIST_INQ','There is an existing country associated with this Name.');
/*End country message*/



/*Pages Message*/
define('ADD_NEW_PAGES','Record has been added successfully.');
define('UPDATE_PAGES','Record has been updated successfully.');
define('DELETE_PAGES','Record has been deleted successfully.');
define('ACTIVE_PAGES','Record has been activated successfully.');
define('INACTIVE_PAGES','Record has been inactivated successfully.');
define('EXIST_PAGES','There is an existing country associated with this Name.');
/*End country message*/

/* Giftcard Message*/
define('ADD_NEW_GIFTCARD','Record has been added successfully.');
define('UPDATE_GIFTCARD','Record has been updated successfully.');
define('DELETE_GIFTCARD','Record has been deleted successfully.');
define('ACTIVE_GIFTCARD','Record has been activated successfully.');
define('INACTIVE_GIFTCARD','Record has been inactivated successfully.');
define('EXIST_GIFTCARD','There is an existing country associated with this Name.');
/*End GIftcard message*/

/*State Message*/
define('ADD_NEW_STATE','Record has been added successfully.');
define('UPDATE_STATE','Record has been updated successfully.');
define('DELETE_STATE','Record has been deleted successfully.');
define('ACTIVE_STATE','Record has been activated successfully.');
define('INACTIVE_STATE','Record has been inactivated successfully.');
define('EXIST_STATE','There is an existing state associated with this Name.');
/*End of state message*/

/*Website Track message*/
define('UNBLOCK_IP','IP has been unblock successfully.');
define('BLOCK_IP','IP has been block successfully.');
define('DELETE_IP','Record has been deleted successfully.');
define('UPDATE_IP','Record has been updated successfully.');
/*End of website Track message*/

/*Usertrack message*/
define('DELETE_USER_IP','Record has been deleted successfully.');
/*End of user track message*/

/*Category message*/
define('EXIST_CATEGORY','There is an existing category associated with this Name');
define('ADD_NEW_CATEGORY','Record has been added successfully.');
define('UPDATE_CATEGORY','Record has been updated successfully.');
define('DELETE_CATEGORY','Record has been deleted successfully.');
define('ACTIVE_CATEGORY','Record has been activated successfully.');
define('INACTIVE_CATEGORY','Record has been inactivated successfully.');
/*End of category message*/

/*Image setting message*/
define('SITE_SETTING_UPDATE','Site settings updated successfully.');
define('SEO_SETTING_UPDATE','Seo settings updated successfully.');
define('IMAGE_SETTING_UPDATE','Image settings updated successfully.');
define('PAYMENT_SETTING_UPDATE','Payment settings updated successfully.');
/*End of image setting message*/






/*SIZE FAQ*/
define('ADD_NEW_FAQ','Record has been added successfully.');
define('UPDATE_FAQ','Record has been updated successfully.');
define('DELETE_FAQ','Record has been deleted successfully.');
define('ACTIVE_FAQ','Record has been activated successfully.');
define('INACTIVE_FAQ','Record has been inactivated successfully.');
define('EXIST_FAQ','There is an existing state associated with this Name.');
/*End of FABRIC message*/

/*SIZE COUPON*/
define('ADD_NEW_COUPON','Record has been added successfully.');
define('UPDATE_COUPON','Record has been updated successfully.');
define('DELETE_COUPON','Record has been deleted successfully.');
define('ACTIVE_COUPON','Record has been activated successfully.');
define('INACTIVE_COUPON','Record has been inactivated successfully.');
define('EXIST_COUPON','There is an existing state associated with this Name.');
/*End of FABRIC message*/

/*User Message*/
define('ADD_NEW_USER','Record has been added successfully.');
define('UPDATE_USER','Record has been updated successfully.');
define('DELETE_USER','Record has been deleted successfully.');
define('ACTIVE_USER','Record has been activated successfully.');
define('INACTIVE_USER','Record has been inactivated successfully.');
define('EXIST_USER','There is an existing state associated with this Name.');
/*End of state message*/

/*STAFF Message*/
define('ADD_NEW_STAFF','Record has been added successfully.');
define('UPDATE_STAFF','Record has been updated successfully.');
define('DELETE_STAFF','Record has been deleted successfully.');
define('ACTIVE_STAFF','Record has been activated successfully.');
define('INACTIVE_STAFF','Record has been inactivated successfully.');
define('EXIST_STAFF','There is an existing state associated with this Name.');
/*End of state message*/

/*CUSTOMER Message*/
define('ADD_NEW_CUSTOMER','Record has been added successfully.');
define('UPDATE_CUSTOMER','Record has been updated successfully.');
define('DELETE_CUSTOMER','Record has been deleted successfully.');
define('ACTIVE_CUSTOMER','Record has been activated successfully.');
define('INACTIVE_CUSTOMER','Record has been inactivated successfully.');
define('EXIST_CUSTOMER','There is an existing state associated with this Name.');
/*End of state message*/


/*STAFF Message*/
define('ADD_NEW_SPD','Record has been added successfully.');
define('UPDATE_SPD','Record has been updated successfully.');
define('DELETE_SPD','Record has been deleted successfully.');
define('ACTIVE_SPD','Record has been activated successfully.');
define('INACTIVE_SPD','Record has been inactivated successfully.');
define('EXIST_SPD','There is an existing state associated with this Name.');
/*End of state message*/

/*Extra Message*/
define('ADD_NEW_OFFER','Record has been added successfully.');
define('UPDATE_OFFER','Record has been updated successfully.');
define('DELETE_OFFER','Record has been deleted successfully.');
define('ACTIVE_OFFER','Record has been activated successfully.');
define('INACTIVE_OFFER','Record has been inactivated successfully.');
define('EXIST_OFFER','There is an existing Colour associated with this Name.');
/*End of Extra Messages*/

/*Extra Message*/
define('ADD_NEW_PAYMENT','Record has been added successfully.');
define('UPDATE_PAYMENT','Record has been updated successfully.');
define('DELETE_PAYMENT','Record has been deleted successfully.');
define('ACTIVE_PAYMENT','Record has been activated successfully.');
define('INACTIVE_PAYMENT','Record has been inactivated successfully.');
define('EXIST_PAYMENT','There is an existing Colour associated with this Name.');
/*End of Extra Messages*/



/*BANNER Message*/
define('ADD_NEW_BANNER','Record has been added successfully.');
define('UPDATE_BANNER','Record has been updated successfully.');
define('DELETE_BANNER','Record has been deleted successfully.');
define('ACTIVE_BANNER','Record has been activated successfully.');
define('INACTIVE_BANNER','Record has been inactivated successfully.');

/*End of messages*/



/*List NewsLetter message*/
define('ADD_NEW_NEWSLETTER_USER','Record has been added successfully.');
define('UPDATE_NEWSLETTER_USER','Record has been updated successfully.');
define('DELETE_NEWSLETTER_USER','Record has been deleted successfully.');
define('SUBSCRIBE_NEWSLETTER_USER','Record has been subscribed successfully.');
define('UNSUBSCRIBE_NEWSLETTER_USER','Record has been unsubscribed successfully.');
define('SEND_NEWSLETTER','Newsletter has been sent successfully.');
/*End of list admin message*/


/*BANNER Message*/
define('ADD_NEW_TICKETS','Record has been added successfully.');
define('UPDATE_TICKETS','Record has been updated successfully.');
define('DELETE_TICKETS','Record has been deleted successfully.');
define('ACTIVE_TICKETS','Record has been activated successfully.');
define('INACTIVE_TICKETS','Record has been inactivated successfully.');


/******Dashboard********/
define('PROFILE_UPDATE_SUCC','Profile successfully updated.');
define('PASS_UPDATE_SUCCESS','Password successfully updated.');

/*BRANCH Message*/
define('ADD_NEW_BRANCH','Record has been added successfully.');
define('UPDATE_BRANCH','Record has been updated successfully.');
define('DELETE_BRANCH','Record has been deleted successfully.');
define('ACTIVE_BRANCH','Record has been activated successfully.');
define('INACTIVE_BRANCH','Record has been inactivated successfully.');

/*BRANCH Message*/
define('ADD_NEW_HOMECONTENT','Record has been added successfully.');
define('UPDATE_HOMECONTENT','Record has been updated successfully.');
define('DELETE_HOMECONTENT','Record has been deleted successfully.');
define('ACTIVE_HOMECONTENT','Record has been activated successfully.');
define('INACTIVE_HOMECONTENT','Record has been inactivated successfully.');


/*List order message*/
define('Deliver_RECORD','Record has been Deliver successfully.');

//*End of list order message*/


define('IMAGE_REMOVE','Image removed successfully.');


 /*There is defined error path for showing proper error message through custom error class*/
define('VIEWPATH',FCPATH.APPPATH.'views/');

/* define version constant for js & Css file */

define("VERSION", "1.5");

define("PRIVATEKEY", "blu3@T0pcash-C0wss5X");
/* End of file constants.php */
/* Location: ./application/config/constants.php */
