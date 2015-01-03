<?php

include("include/session.php");

class AdminProcess
{
   /* Class constructor */
   function AdminProcess(){
      global $session;
      /* Make sure administrator is accessing page */
      if(!$session->isAdmin()){
         header("Location: ../index.php");
         return;
      }
      /* Admin submitted update user level form */
      if(isset($_POST['subupdlevel'])){
         $this->procUpdateLevel();
      }
      /* Admin submitted delete user form */
      else if(isset($_GET['subdeluser'])){
         $this->procDeleteUser();
      }
      /* Admin submitted delete inactive users form */
      else if(isset($_POST['subdelinact'])){
         $this->procDeleteInactive();
      }
      /* Admin submitted ban user form */
      else if(isset($_POST['subbanuser'])){
         $this->procBanUser();
      }
      /* Admin submitted IP ban form */
      else if(isset($_POST['subbanip'])){
         $this->procBanIP();
      }
      /* Admin submitted delete banned user form */
      else if(isset($_POST['subdelbanned'])){
         $this->procDeleteBannedUser();
      }
      /* Admin submitted configuration changes */
      else if(isset($_POST['configedit'])){
         $this->procConfigEdit();
      }
      /* Admin submitted user activation form */
      else if(isset($_POST['activateusers'])){
         $this->procActivateUsers();
      }
      /* Admin cleared flag from comment */
      else if(isset($_POST['cmt_status'])){
         $this->procReportComment();
      }
      /* Admin deleted comment */
      else if(isset($_POST['cmt_delid'])){
         $this->procDeleteComment();
      }
      /* Admin sent message */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 1)){
         $this->procAdminMessage();
      }
      /* Admin deleted message */
      else if(isset($_POST['submessage']) && ($_POST['submessage'] == 2)){
         $this->procAdminDelete();
      }
      /* Admin add category */
      else if(isset($_POST['catadd']) && ($_POST['catadd'] == 1)){
         $this->procCatAdd();
      }
      /* Admin add product */
      else if(isset($_POST['prodadd']) && ($_POST['prodadd'] == 1)){
         $this->procProdAdd();
      }
      /* Admin add KB */
      else if(isset($_POST['kbadd']) && ($_POST['kbadd'] == 1)){
         $this->procKBAdd();
      }
      /* Admin delete KB */
      else if(isset($_POST['kbdel']) && ($_POST['kbdel'] == 1)){
         $this->procDeleteKB();
      }
      /* Admin delete category */
      else if(isset($_POST['catdel']) && ($_POST['catdel'] == 1)){
         $this->procDeleteCat();
      }
      /* Admin delete product */
      else if(isset($_POST['proddel']) && ($_POST['proddel'] == 1)){
         $this->procDeleteProd();
      }
      /* Admin submitted edit user account form */
      else if(isset($_POST['subedit']) && ($_POST['button'] == "⊚")){
         $this->procEditAccount();
      }
      else if(isset($_POST['subedit']) && ($_POST['button'] == "⊘")){
         $this->procDeleteUser();
      }
      /* Admin edit Catagory */
      else if(isset($_POST['catedit']) && ($_POST['catedit'] == 1)){
         $this->procEditCat();
      }
      /* Admin edit Product */
      else if(isset($_POST['prodedit']) && ($_POST['prodedit'] == 1)){
         $this->procEditProd();
      }
      /* Admin edit Product */
      else if(isset($_POST['kbedit']) && ($_POST['kbedit'] == 1)){
         $this->procEditKB();
      }
      /* Should not get here, redirect to home page */
      else{
         header("Location: ../index.php");
      }
   }

   /**
    * procUpdateLevel - If the submitted username is correct,
    * their user level is updated according to the admin's
    * request.
    */
   function procUpdateLevel(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("upduser");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Update user level */
      else{
         $database->updateUserField($subuser, "userlevel", (int)$_POST['updlevel']);
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procDeleteUser - If the submitted username is correct,
    * the user is deleted from the database.
    */
   function procDeleteUser(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("username");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Delete user from database */
      else{
         $sql = $database->connection->prepare("DELETE FROM ".TBL_USERS." WHERE username = '$subuser'");
         $sql->execute();
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procDeleteInactive - All inactive users are deleted from
    * the database, not including administrators. Inactivity
    * is defined by the number of days specified that have
    * gone by that the user has not logged in.
    */
   function procDeleteInactive(){
      global $session, $database;
      include_once ('/language.php');
      $inact_time = $session->time - $_POST['inactdays']*24*60*60;
      $sql = $database->connection->prepare("DELETE FROM ".TBL_USERS." WHERE timestamp < $inact_time AND userlevel != ".ADMIN_LEVEL);
      $sql->execute();
      $_SESSION['banusererror'] =  3;
      header("Location: index.php?id=11");
   }
   
   /**
    * procBanUser - If the submitted username is correct,
    * the user is banned from the member system, which entails
    * removing the username from the users table and adding
    * it to the banned users table.
    */
   function procBanUser(){
      global $session, $database, $form;
      include_once ('language.php');
      /* Username error checking */
      $subuser = $this->checkUsername("banuser");

      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['banusererror'] = 1;
         header("Location: index.php?id=11");
      }
      /* Ban user from member system */
      else{
         $sql = $database->connection->prepare("DELETE FROM ".TBL_USERS." WHERE username = '$subuser'");
         $sql->execute();

         $sql = $database->connection->prepare("INSERT INTO ".TBL_BANNED_USERS." VALUES ('$subuser', $session->time)");
         $sql->execute();
         $_SESSION['banusererror'] =  2;
         header("Location: index.php?id=11");
      }
   }
   /**
    * procBanIP - The IP is banned from the member system
    */
   function procBanIP(){
      global $session, $database, $form;
      include_once ('language.php');
      /* IP error checking */

      if(!preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_POST['banip']))
            {
             $_SESSION['baniperror'] =  1;
             header("Location: index.php?id=11");
            }
      else {
         $banthis = $_POST['banip'];
         $ckips = $database->connection->prepare("SELECT * FROM ".TBL_BANNED_IP." WHERE ip like '%$banthis%'");
         $ckips->execute();
         $result = $ckips->fetch(\PDO::FETCH_ASSOC);

         if($result['ip'] == $banthis) {
            $_SESSION['baniperror'] =  1;
             header("Location: index.php?id=11");
         }

      /* Ban IP from member system */
         $_SESSION['baniperror'] =  2;
         $banthis = $_POST['banip'];
         $sql = $database->connection->prepare("INSERT INTO ".TBL_BANNED_IP." VALUES ('$banthis', $session->time)");
         $sql->execute();
         header("Location: index.php?id=11");
     }

   }

   /**
    * procDeleteBannedUser - If the submitted username is correct,
    * the user is deleted from the banned users table, which
    * enables someone to register with that username again.
    */
   function procDeleteBannedUser(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("delbanuser", true);

      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Delete user from database */
      else{
      	 $sql = $database->connection->prepare("DELETE FROM ".TBL_BANNED_USERS." WHERE username = '$subuser'");
         $sql->execute();

         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * checkUsername - Helper function for the above processing,
    * it makes sure the submitted username is valid, if not,
    * it adds the appropritate error to the form.
    */
   function checkUsername($uname, $ban=false){
      global $database, $form;
      include_once ('language.php');
	  $config = $database->getConfigs();
      /* Username error checking */
      $subuser = $_POST[$uname];
      $field = $uname;  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, $lang['NO_USERNAME']);
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < $config['min_user_chars'] || strlen($subuser) > $config['max_user_chars'] ||
            !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser) ||
            (!$ban && !$database->usernameTaken($subuser))){
            $form->setError($field, $lang['NO_USER']);
         }
      }
      return $subuser;
   }
   
   /**
    * configEdit - function for updating the website configurations in the
    * configuration table in the database.
    */
   function procConfigEdit(){
   	global $session, $form;
   	/* Account edit attempt */
      $retval = $session->editConfigs($_POST['sitename'], $_POST['sitedesc'], $_POST['emailfromname'], 
      $_POST['adminemail'], $_POST['webroot'], $_POST['home_page'], $_POST['activation'], 
      $_POST['min_user_chars'], $_POST['max_user_chars'], $_POST['min_pass_chars'], 
      $_POST['max_pass_chars'], $_POST['send_welcome'], $_POST['enable_login_question'], 
      $_POST['enable_capthca'], $_POST['all_lowercase'], $_POST['user_timeout'], $_POST['guest_timeout'],
      $_POST['cookie_expiry'], $_POST['cookie_path'], $_POST['comments'], $_POST['messages'], $_POST['calls']);
	  
   /* Account edit successful */
      if($retval){
         $_SESSION['configedit'] = true;
         header("Location: ../admin/index.php?id=2");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ../admin/index.php?id=2");
      }
   }
   
   /**
    * procActivateUsers - function to activate users selected by Admin
    * on the Admin Page.
    */
    function procActivateUsers(){
   	global $session, $database, $mailer;
	$config = $database->getConfigs();
   	/* Account edit attempt */
   	foreach($_POST['user_name'] as $username) {
    $sql = $database->connection->prepare("UPDATE ".TBL_USERS." SET USERLEVEL = '3' WHERE username = '$username'");
    $sql->execute();
	$req_user_info = $database->getUserInfo($username);
	$email = $req_user_info['email'];
	$mailer->adminActivated($username,$email,$config);
   	}
	header("Location: ../admin/index.php?id=3");
   }
   
   /**
    * procEditAccount - Admin account editing function.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->adminEditAccount($_POST['username'], $_POST['newpass'], $_POST['conf_newpass'], $_POST['email'], $_POST['userlevel'], $_POST['usertoedit']);

      /* Account edit successful */
      if($retval){
         $_SESSION['adminedit'] = true;
         $_SESSION['usertoedit'] = $_POST['usertoedit'];
         header("Location: ../admin/index.php?id=6");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ../admin/index.php?id=6&usertoedit=".$_POST['usertoedit']);
      }
   }

   /**
    * procEditCat - Admin Category editing function.
    */
   function procEditCat(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->adminEditCat($_POST['catprod'], $_POST['catname'], $_POST['catdesc'], $_POST['id']);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=13&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=13&success=0");
      }
   }

   /**
    * procEditProd - Admin Product editing function.
    */
   function procEditProd(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->adminEditProd($_POST['prod'], $_POST['description'], $_POST['id']);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=15&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=15&success=0");
      }
   }

   /**
    * procEditKB - Admin KB editing function.
    */
   function procEditKB(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->adminEditKB($_POST['category'], $_POST['question'], $_POST['answer'], $_POST['id']);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=12&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=12&success=0");
      }
   }

   /**
    * procDeleteComment
    */
   function procDeleteComment(){
      global $session, $form;

      /* Account edit attempt */
      $id = $_POST['cmt_delid'];
      $retval = $session->deleteComment($id);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=10");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

    /**
    * procReportComment
    */
   function procReportComment(){
      global $session, $form;
      $reporter = $session->username;
      /* Account edit attempt */
      $id = $_POST['cmt_notid'];
      $cmt_status = $_POST['cmt_status'];
      $retval = $session->reportComment($id, $reporter, $cmt_status);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=10");
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
   function procAdminDelete(){
      global $session, $form;

      /* Account edit attempt */
      $id = $_POST['msg_delid'];
      $retval = $session->deleteMessage($id);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=8&message=3");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
    /**
    * procAdminMessage
    */
   function procAdminMessage(){
      global $database, $session, $form;

      $user = $_POST['user'];
      $ctime = date("Y-m-d")." @ ".date("h:i a");
      $msg = htmlspecialchars($_POST['msg']);
      $subuser = 'all';
      $status = '10';
      $retval = $session->sendMessage($user, $subuser, $msg ,$status, $ctime);
      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=8&message=1");
      }
      /* Error found with form */
      else{
         header("Location: ".$session->referrer."?id=8&message=5");
      }
   }

   /**
    * procCatAdd - Add new Category
    */
   function procCatAdd(){
      global $database, $session, $form;
      $config = $database->getConfigs();

      $retval = $session->CatAdd($_POST['catprod'], $_POST['catname'], $_POST['catdesc']);

      if($retval == 0){
         header("Location: ".$session->referrer."?id=13&success=1");
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=13");
      }
      /* Registration attempt failed */
      else if($retval == 2){
         header("Location: ".$session->referrer."?id=13&success=0");
      }
   }
   /**
    * procProdAdd - Add new Product
    */
   function procProdAdd(){
      global $database, $session, $form;
      $config = $database->getConfigs();

      $retval = $session->ProdAdd($_POST['prod'], $_POST['description']);

      if($retval == 0){
         header("Location: ".$session->referrer."?id=15&success=1");
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=15");
      }
      /* Registration attempt failed */
      else if($retval == 2){
         header("Location: ".$session->referrer."?id=15&success=0");
      }
   }
   /**
    * procKBAdd - Add new Article
    */
   function procKBAdd(){
      global $database, $session, $form;
      $config = $database->getConfigs();

      $retval = $session->KBAdd($_POST['category'], $_POST['question'], $_POST['answer']);

      if($retval == 0){
         header("Location: ".$session->referrer."?id=12&success=1");
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer."?id=12");
      }

      else if($retval == 2){
         header("Location: ".$session->referrer."?id=12&success=0");
      }
   }

   /**
    * procDeleteCat
    */
   function procDeleteCat(){
      global $session, $form;

      /* Account edit attempt */
      $id = $_POST['cate_delid'];
      $retval = $session->deleteCat($id);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=13&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   /**
    * procDeleteProd
    */
   function procDeleteProd(){
      global $session, $form;

      /* Account edit attempt */
      $id = $_POST['prod_delid'];
      $retval = $session->deleteProd($id);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=15&success=1");
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }

   /**
    * procDeleteProd
    */
   function procDeleteKB(){
      global $session, $form;

      /* Account edit attempt */
      $id = $_POST['kb_delid'];
      $retval = $session->deleteKB($id);

      /* Account edit successful */
      if($retval){
         header("Location: ".$session->referrer."?id=12&success=1");
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
$adminprocess = new AdminProcess;

?>
