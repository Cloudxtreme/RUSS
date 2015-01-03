<?php
/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 *
 */

class Mailer
{
   /*
    * sendActivation - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */

  function sendActivation($user, $email, $pass, $token, $config){
    include ('language.php');
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - ".$lang['WELCOME'];
  	$body = $user.",\n\n"
      .$lang['WELCOME']."! ".$lang['EML_MSG_1_1']." ".$config['SITE_NAME']." ".$lang['EML_MSG_1_2'].":\n\n".$lang['USERNAME'].": ".$user."\n\n".$lang['EML_MSG_1_3'].": "
      .$config['WEB_ROOT']."registration.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }

   /*
    * New Ticket Notify
    */

  function openTicket($tid, $user, $email, $timedate, $subject, $ownermail, $techmail, $config){
    include ('language.php');
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME'].": #".$tid." ".$lang['SUPPORT_TICKET'];
  	$body = $lang['HELLO'].",\n\n"
      .$lang['NEW_TICKET_OPENED']." ".$tid.". ".$lang['TICKET_PAGE'].": \n\n".$config['WEB_ROOT']."ticket.php?ID=".$tid."\n\n"
      .$lang['THANK_YOU']." \n\n"
      .$config['SITE_NAME']."\n".$config['SITE_DESC'];

    $email = $ownermail.",".$techmail;

    return mail($email,$subject,$body,$from);
   }

      
   /**
    * adminActivation - Sends an activation e-mail to the newly
    * registered user explaining that admin will activate the account.
    */
   
  function adminActivation($user, $email, $pass, $config){
    include ('language.php');
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - ".$lang['WELCOME'];
  	$body = $user.",\n\n"
      .$lang['WELCOME']."! ".$lang['EML_MSG_1_1']." ".$config['SITE_NAME']." ".$lang['EML_MSG_1_2'].":\n\n".$lang['USERNAME'].": ".$user."\n\n".$lang['EML_MSG_2_1']."\n\n".$lang['EML_MSG_2_2']."\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
      
   /**
    * activateByAdmin - Sends an activation e-mail to the admin
    * to allow him or her to activate the account. E-mail will appear
    * to come FROM the user using the e-mail address he or she registered
    * with.
    */
   
  function activateByAdmin($user, $email, $pass, $token, $config){
    include ('language.php');
  	$from = "From: ".$user." <".$email.">";
  	$subject = $config['SITE_NAME']." - User Account Activation!";
  	$body = $lang['EML_MSG_3_1'].",\n\n".$user." ".$lang['EML_MSG_3_2']." ".$config['SITE_NAME']." ".$lang['EML_MSG_3_5'].":\n\n".$lang['USERNAME'].": ".$user."\n".$lang['EMAIL'].": ".$email."\n\n".$lang['EML_MSG_3_3']." \n\n".$lang['EML_MSG_3_4'].".\n\n"
      .$config['WEB_ROOT']."registration.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      ."\n\n"
      .$config['SITE_NAME'];
	
    $adminemail = $config['EMAIL_FROM_ADDR'];
    return mail($adminemail,$subject,$body,$from);
   }
    
	/**
    * adminActivated - Sends an e-mail to the user once
    * admin has activated the account.
    */
   
  function adminActivated($user, $email, $config){
    include ('language.php');
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      .$lang['WELCOME']."! ".$lang['EML_MSG_1_1']." ".$config['SITE_NAME']." ".$lang['EML_MSG_1_2'].":\n\n".$lang['USERNAME'].": ".$user."\n\n".$lang['EML_MSG_4_1']
      .$lang['EML_MSG_4_1']." - "
      .$config['WEB_ROOT']."\n\n".$lang['EML_MSG_2_2']."\n\n"
      .$config['SITE_NAME'];
	
    return mail($email,$subject,$body,$from);
   }
    
   /**
    * sendWelcome - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */
   
  function sendWelcome($user, $email, $pass, $config){
    include ('language.php');
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      .$lang['WELCOME']."! ".$lang['EML_MSG_1_1']." ".$config['SITE_NAME']." ".$lang['EML_MSG_1_2'].":\n\n".$lang['USERNAME'].": ".$user."\n\n".$lang['EML_MSG_5_1']
      .$lang['EML_MSG_2_2']."\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   
   function sendNewPass($user, $email, $pass, $config){
     include ('language.php');
      $from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
      $subject = $config['SITE_NAME']." - ".$lang['EML_MSG_6_1'];
      $body = $user.",\n\n"
        .$lang['EML_MSG_6_1']." ".$config['SITE_NAME']."\n\n"
        .$lang['USERNAME'].": ".$user."\n"
        .$lang['NEW_PASSWORD'].": ".$pass."\n\n"
        .$lang['EML_MSG_6_3']."\n\n"
        .$config['SITE_NAME'];
             
      return mail($email,$subject,$body,$from);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;
 
?>