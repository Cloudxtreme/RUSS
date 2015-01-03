<?php
include("include/session.php");

if(($_POST['hitcap'] == 1) && ($_SESSION["security"] != $_POST["security"])) {
  $_SESSION['comresponse'] = 2;
  header("Location: ".$session->referrer);
  die;
}

class Process
{
   /* Class constructor */
   function Process(){
      global $database, $session;
      /* User submitted login form */
      if(isset($_POST['sublogin'])){
         $this->procLogin();
      }
      /* User submitted registration form */
      else if(isset($_POST['subjoin'])){
         $this->procRegister();
      }
      /* User submitted forgot password form */
      else if(isset($_POST['subforgot'])){
         $this->procForgotPass();
      }
      /* User submitted edit account form */
      else if(isset($_POST['subedit'])){
         $this->procEditAccount();
      }
      /* User submitted Comment */
      else if(isset($_POST['subcomment']) && ($_POST['subcomment'] == 1)){
         $this->procSubmitComment();
      }
      /* Admin deleted Comment */
      else if(isset($_POST['subcomment']) && ($_POST['subcomment'] == 2)){
         $this->procDeleteComment();
      }
      /* User reported Comment */
      else if(isset($_POST['subcomment']) && ($_POST['subcomment'] == 3)){
         $this->procReportComment();
      }
      /* User send Message */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 1)){
         $this->procSendMessage();
      }
      /* User deleted Message */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 2)){
         $this->procDeleteMessage();
      }
      /* User reported Message */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 3)){
         $this->procReportMessage();
      }
      /* User reply */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 4)){
         $this->procReplyMessage();
      }
      /* User sent Message */
      else if(isset($_POST['submsg'])){
         $this->procSubmitMsg();
      }
      /* Search */
      else if(isset($_POST['kbsearch'])){
         $this->procSearch();
      }
      /* Submit Ticket */
      else if(isset($_POST['optic'])){
         $this->procSubmitTicket();
      }
      /* Update Ticket */
      else if(isset($_POST['optix'])){
         $this->updateTicket();
      }
      /* Delete Ticket */
      else if(isset($_POST['optid'])){
         $this->procDeleteTicket();
      }
      /**
       * The only other reason user should be directed here
       * is if he wants to logout, which means user is
       * logged in currently.
       */
      else if($session->logged_in){
         $this->procLogout();
      }
      /**
       * Should not get here, which means user is viewing this page
       * by mistake and therefore is redirected.
       */
       else{
          $config = $database->getConfigs();
          header("Location: ".$config['WEB_ROOT'].$config['home_page']);
       }
   }

   /**
    * procLogin - Processes the user submitted login form, if errors
    * are found, the user is redirected to correct the information,
    * if not, the user is effectively logged in to the system.
    */
   function procLogin(){
      global $session, $form;
      /* Login attempt */



      $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));

      /* Login successful */
      if($retval){
         header("Location: ".$session->referrer);
      }
      /* Login failed */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
   function procLogout(){
      global $database, $session;
      $config = $database->getConfigs();
	  $retval = $session->logout();
	  header("Location: ".$config['WEB_ROOT'].$config['home_page']);
   }

   /**
    * procRegister - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
   function procRegister(){
      global $database, $session, $form;
      $config = $database->getConfigs();

	  /* Checks if registration is disabled */
	  if($config['ACCOUNT_ACTIVATION'] == 4){
	  	$_SESSION['reguname'] = $_POST['user'];
        $_SESSION['regsuccess'] = 6;
		header("Location: ".$session->referrer);
	  }

      /* Convert username to all lowercase (by option) */
      if($config['ALL_LOWERCASE'] == 1){
         $_POST['user'] = strtolower($_POST['user']);
      }
      /* Hidden form field captcha designed to catch out auto-fill spambots */

      if(($_POST['killbill'] != "")) { $retval = 2; } else {

      /* Registration attempt */
      $retval = $session->register($_POST['user'], $_POST['pass'], $_POST['conf_pass'], $_POST['email'], $_POST['conf_email']);
      }
      
      /* Registration Successful */
      if($retval == 0){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 0;
         header("Location: ".$session->referrer);
      }
      /* E-mail Activation */
      else if($retval == 3){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 3;
         header("Location: ".$session->referrer);
      }
      /* Admin Activation */
      else if($retval == 4){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 4;
         header("Location: ".$session->referrer);
      }
      /* No Activation Needed but E-mail going out */
      else if($retval == 5){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 5;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Registration attempt failed */
      else if($retval == 2){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 2;
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procForgotPass - Validates the given username then if
    * everything is fine, a new password is generated and
    * emailed to the address the user gave on sign up.
    */
   function procForgotPass(){
      global $database, $session, $mailer, $form;
      $config = $database->getConfigs();
      /* Username error checking */
      $subuser = $_POST['user'];
      $subemail = $_POST['email'];
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered<br>");
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < $config['min_user_chars'] || strlen($subuser) > $config['max_user_chars'] ||
            !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser) ||
            (!$database->usernameTaken($subuser))){
            $form->setError($field, "* Username does not exist<br>");
          }
          else if ($database->checkUserEmailMatch($subuser, $subemail) == 0){
          	$form->setError($field, "* No Match<br>");
       }
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
      }
      /* Generate new password and email it to user */
      else{
         /* Generate new password */
         $newpass = $session->generateRandStr(8);
         
         /* Get email of user */
         $usrinf = $database->getUserInfo($subuser);
         $email  = $usrinf['email'];
         
         /* Attempt to send the email with new password */
         if($mailer->sendNewPass($subuser,$email,$newpass,$config)){
            /* Email sent, update database */
            $usersalt = $session->generateRandStr(8);
      	    $newpass = sha1($usersalt.$newpass);
            $database->updateUserField($subuser,"password",$newpass);
            $database->updateUserField($subuser,"usersalt",$usersalt);
            $_SESSION['forgotpass'] = true;
         }
         /* Email failure, do not change password */
         else{
            $_SESSION['forgotpass'] = false;
         }
      }
      
      header("Location: ".$session->referrer);
   }
   
   /**
    * procEditAccount - Attempts to edit the user's account
    * information, including the password, which must be verified
    * before a change is made.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['conf_newpass'], $_POST['email'], $_POST['privacy'], $_POST['showonline'], $_POST['showcomments'], $_POST['allowcalls'], $_POST['namer'], $_POST['surname'], $_POST['phone'], $_POST['twitter'], $_POST['facebook'], $_POST['google'], $_POST['linkedin'], $_POST['website'], $_POST['icq'], $_POST['skype'], $_POST['gtalk']);

      /* Account edit successful */
      if($retval){
         $_SESSION['useredit'] = true;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

   /**
    * procSubmitComment
    */
   function procSubmitComment(){
      global $session, $form;

      $user = $session->username;
      $url = $_SESSION['cururl'];
      $ctime = date("Y-m-d")." @ ".date("h:i a");
      $msg = htmlspecialchars($_POST['msg']);
      if(($_POST['killbill'] != "")) {$_SESSION['comresponse'] = 2; header("Location: ".$url);} else {
      $retval = $session->submitComment($user, $url, $msg , $_POST['status'], $ctime);
      }

      if($retval){
         $_SESSION['comresponse'] = 1;
         header("Location: ".$url);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$url);
      }
   }

   /**
    * procDeleteComment
    */
   function procDeleteComment(){
      global $session, $form;

      $id = $_POST['cmt_delid'];
      $retval = $session->deleteComment($id);

      if($retval){
         $_SESSION['comresponse'] = 3;
         header("Location: ".$_SERVER['HTTP_REFERER']);
      }
      /* Error found with form */
      else{
         $_SESSION['comresponse'] = 2;
         header("Location: ".$_SERVER['HTTP_REFERER']);
      }
   }

   /**
    * procReportComment
    */
   function procReportComment(){
      global $session, $form;
      $reporter = $session->username;

      $id = $_POST['cmt_notid'];
      $cmt_status = $_POST['cmt_status'];
      $retval = $session->reportComment($id, $reporter, $cmt_status);

      if($retval){
         $_SESSION['comresponse'] = 4;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

    /**
    * procSendMessage
    */
   function procSendMessage(){
      global $database, $session, $form;

      if(($_POST['killbill'] != "")) {header("Location: ".$session->referrer."?message=2");}
      $user = $session->username;
      $ctime = date("Y-m-d")." @ ".date("h:i a");
      $msg = htmlspecialchars($_POST['msg']);
      $subuser = htmlspecialchars($_POST['towho']);

      /* Make sure username is in database */
      if(!$database->usernameTaken($subuser)){
            header("Location: ".$session->referrer."?message=5");
            die;
          }
      else {
      $retval = $session->sendMessage($user, $subuser, $msg , $_POST['status'], $ctime);
      }

      if($retval){
         header("Location: ".$session->referrer."?message=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

   /**
    * procDeleteMessage
    */
   function procDeleteMessage(){
      global $session, $form;

      $id = $_POST['msg_delid'];
      $retval = $session->deleteMessage($id);

      if($retval){
         header("Location: ".$session->referrer."?message=3");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

   /**
    * procReportMessage
    */
   function procReportMessage(){
      global $session, $form;
      $reporter = $session->username;

      $id = $_POST['msg_notid'];
      $msg_status = $_POST['msg_status'];
      $retval = $session->reportMessage($id, $reporter, $msg_status);

      if($retval){
         header("Location: ".$session->referrer."?message=4");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

   function procReplyMessage(){
      global $session, $form;
      $_SESSION['replyto'] = $_POST['replyto'];
      header("Location: ".$session->referrer."#newmsg");
    }

   /**
    * procSubmitTicket
    */
   function procSubmitTicket(){
      global $session, $form;
      $tid = $_POST['tid'];
      $user = $session->username;
      $owner = $_POST['owner'];
      $ownermail = $_POST['ownermail'];
      $techmail = $_POST['techmail'];
      $email = $session->userinfo['email'];
      $category = htmlspecialchars($_POST['category']);
      $timedate = date("Y-m-d")." @ ".date("h:i a");
      $subject = htmlspecialchars($_POST['subject']);
      $report = strip_tags($_POST['report'],"<p><strike><h1><h2><h3><h4><u><i><br><blockquote><span><ul><li><ol><b><code><img><a>");
      $IP = $_SERVER['REMOTE_ADDR'];
      $status = $_POST['status'];
      $rating = $_POST['rating'];
      $assigned = $_POST['assigned'];
      $tech = $_POST['tech'];
      $notes = $_POST['notes'];


      if(($_POST['killbill'] != "")) {header("Location: ".$session->referrer."?error=1");} else {

        $retval = $session->submitTicket($tid, $user, $email, $category, $timedate, $subject, $report, $IP, $status, $tech, $assigned, $rating, $notes, $owner, $ownermail, $techmail);

      }

      if($retval){
         header("Location:".RURL."/ticket.php?ID=".$tid."&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?error=1");
      }
   }

   /**
    * updateTicket
    */
   function updateTicket(){
      global $session, $form;

      $tid = $_POST['tid'];
      $rating = $_POST['rating'];
      $status = $_POST['status'];
      $tech = $_POST['tech'];
      $assigned = $_POST['assigned'];
      $notes = $_POST['notes'];

      $retval = $session->updateTicket($tid, $rating, $status, $tech, $assigned, $notes);

      if($retval){
         header("Location:".RURL."/ticket.php?ID=".$tid."&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?error=1");
      }
   }
   /**
    * procDeleteTicket
    */
   function procDeleteTicket(){
      global $session, $form;

      $tid = $_POST['tid_delid'];
      $retval = $session->delTicket($tid);

      if($retval){
         header("Location: ".$session->referrer."?success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

};

/* Initialize process */
$process = new Process;

?>
