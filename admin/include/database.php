<?php
/**
 * Database.php
 * 
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 */
include("constants.php");
      
class MySQLDB
{
   public $connection;         //The MySQL database connection
   public $num_active_users;   //Number of active users viewing site
   public $num_active_guests;  //Number of active guests viewing site
   public $num_members;        //Number of signed-up users
   /* Note: call getNumMembers() to access $num_members! */
   
   /* Class constructor */
   function MySQLDB(){
      /* Make connection to database */
   	try {
   		# MySQL with PDO_MYSQL
		$this->connection = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASS);
   		$this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
   	}
   	catch(PDOException $e) {  
    echo "Error connecting to database.";   
	}  
   	     
      /**
       * Only query database to find out number of members
       * when getNumMembers() is called for the first time,
       * until then, default value set.
       */
      $this->num_members = -1;
      $config = $this->getConfigs();
      if($config['TRACK_VISITORS']){
         /* Calculate number of users at site */
         $this->calcNumActiveUsers();      
         /* Calculate number of guests at site */
         $this->calcNumActiveGuests();
      }
	} // MySQLDB function
   
    /**
    * Gather together the configs from the database configuration table.
    */ 
   function getConfigs(){
   $config = array();  
   $sql = $this->connection->query("SELECT * FROM ".TBL_CONFIGURATION);
   while($row = $sql->fetch()) {
   	  	$config[$row['config_name']] = $row['config_value'];
   	  }
   	  return $config;
   }
   
    /**
    * Update Configs - updates the configuration table in the database
    * 
    */ 
   function updateConfigs($value,$configname){
   $query = "UPDATE ".TBL_CONFIGURATION." SET config_value = :value WHERE config_name = :configname";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':value' => $value, ':configname' => $configname));
   }
   
    /**
    * confirmUserPass - Checks whether or not the given username is in the database, 
    * if so it checks if the given password is the same password in the database
    * for that user. If the user doesn't exist or if the passwords don't match up, 
    * it returns an error code (1 or 2). On success it returns 0.
    */
   function confirmUserPass($username, $password){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
	  $query = "SELECT password, userlevel, usersalt FROM ".TBL_USERS." WHERE username = :username";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username));
      $count = $stmt->rowCount();
    
	  if(!$stmt || $count < 1){
        return 1; //Indicates username failure
      }

      /* Retrieve password and userlevel from result, strip slashes */
	  $dbarray = $stmt->fetch();
	   
	  $dbarray['userlevel'] = stripslashes($dbarray['userlevel']);
	  $dbarray['usersalt'] = stripslashes($dbarray['usersalt']);
	  $password = stripslashes($password);
	  
	  $sqlpass = sha1($dbarray['usersalt'].$password);

	  /* Validate that password matches and check if userlevel is equal to 1 */
	  if(($dbarray['password'] == $sqlpass)&&($dbarray['userlevel'] == 1)){
  	  return 3; //Indicates account has not been activated
	  }
	  
	  /* Validate that password matches and check if userlevel is equal to 2 */
      if(($dbarray['password'] == $sqlpass)&&($dbarray['userlevel'] == 2)){
  	  return 4; //Indicates admin has not activated account
	  }

      /* Validate that password is correct */
	  if($dbarray['password'] == $sqlpass){
      return 0; //Success! Username and password confirmed
      }
      else{
         return 2; //Indicates password failure
      }
   }
   
   /**
    * confirmUserID - Checks whether or not the given username is in the database, 
    * if so it checks if the given userid is the same userid in the database
    * for that user. If the user doesn't exist or if the userids don't match up, 
    * it returns an error code (1 or 2). On success it returns 0.
    */
   function confirmUserID($username, $userid){
      /* Add slashes if necessary (for query) */
      if(!get_magic_quotes_gpc()) {
	      $username = addslashes($username);
      }

      /* Verify that user is in database */
	$query = "SELECT userid FROM ".TBL_USERS." WHERE username = :username";
	$stmt = $this->connection->prepare($query);
	$stmt->execute(array(':username' => $username));
	$count = $stmt->rowCount();
      
      if(!$stmt || $count < 1){
         return 1; //Indicates username failure
      }
      
	  $dbarray = $stmt->fetch(); 

      /* Retrieve userid from result, strip slashes */
      $dbarray['userid'] = stripslashes($dbarray['userid']);
      $userid = stripslashes($userid);

      /* Validate that userid is correct */
      if($userid == $dbarray['userid']){
         return 0; //Success! Username and userid confirmed
      }
      else{
         return 2; //Indicates userid invalid
      }
   }
   
   /**
    * usernameTaken - Returns true if the username has been taken by another user, false otherwise.
    */
   function usernameTaken($username){
   	  if(!get_magic_quotes_gpc()){ $username = addslashes($username); }
	  $query = "SELECT username FROM ".TBL_USERS." WHERE username = :username";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username));
	  $count = $stmt->rowCount();    
      return ($count > 0);
   }
   
   /**
    * usernameBanned - Returns true if the username has been banned by the administrator.
    */
   function usernameBanned($username){
      if(!get_magic_quotes_gpc()){ $username = addslashes($username); }
      $query = "SELECT username FROM ".TBL_BANNED_USERS." WHERE username = :username";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username));
	  $count = $stmt->rowCount();    
      return ($count > 0);
   }


   /**
    * addNewUser - Inserts the given (username, password, email) info into the database.
    * Appropriate user level is set. Returns true on success, false otherwise.
    */
   function addNewUser($username, $password, $email, $token, $usersalt){
      $time = time();
      $config = $this->getConfigs();
      /* If admin sign up, give admin user level */
      if(strcasecmp($username, ADMIN_NAME) == 0){
         $ulevel = ADMIN_LEVEL;
      /* Which validation is on? */
      }else if ($config['ACCOUNT_ACTIVATION'] == 1) {
      	 $ulevel = REGUSER_LEVEL; /* No activation required */
      }else if ($config['ACCOUNT_ACTIVATION'] == 2) {
         $ulevel = ACT_EMAIL; /* Activation e-mail will be sent */
      }else if ($config['ACCOUNT_ACTIVATION'] == 3) {
         $ulevel = ADMIN_ACT; /* Admin will activate account */
   	  }

	 $password = sha1($usersalt.$password);
	 $userip = $_SERVER['REMOTE_ADDR'];

     $query = "INSERT INTO ".TBL_USERS." SET username = :username, password = :password, usersalt = :usersalt, userid = 0, userlevel = $ulevel, email = :email, timestamp = $time, actkey = :token, ip = '$userip', regdate = $time";
     $stmt = $this->connection->prepare($query);
     return $stmt->execute(array(':username' => $username, ':password' => $password, ':usersalt' => $usersalt, ':email' => $email, ':token' => $token));
   }

   /**
    * addNewCat - Add new category into the database.
    */
   function addNewCat($catprod, $catname, $catdesc){
      $config = $this->getConfigs();

     $query = "INSERT INTO ".TBL_CATEGORY." SET catprod = :catprod, catname = :catname, catdesc = :catdesc";
     $stmt = $this->connection->prepare($query);
     return $stmt->execute(array(':catprod' => $catprod, ':catname' => $catname, ':catdesc' => $catdesc));
   }

   /**
    * addNewProd - Add new product into the database.
    */
   function addNewProd($prod, $description){
      $config = $this->getConfigs();

     $query = "INSERT INTO ".TBL_PRODUCTS." SET prod = :prod, description = :description";
     $stmt = $this->connection->prepare($query);
     return $stmt->execute(array(':prod' => $prod, ':description' => $description));
   }

   /**
    * addNewKB - Add new product into the database.
    */
   function addNewKB($category, $question, $answer){
      $config = $this->getConfigs();

     $query = "INSERT INTO ".TBL_KDB." SET category = :category, question = :question, answer = :answer";
     $stmt = $this->connection->prepare($query);
     return $stmt->execute(array(':category' => $category, ':question' => $question, ':answer' => $answer));
   }
   
   /**
    * updateUserField - Updates a field, specified by the field
    * parameter, in the user's row of the database.
    */
   function updateUserField($username, $field, $value){
   $query = "UPDATE ".TBL_USERS." SET ".$field." = :value WHERE username = :username";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':username' => $username, ':value' => $value));
   }

   /**
    * updateCatField - Updates a field, specified by the field
    * parameter, in the Cat's row of the database.
    */
   function updateCatField($id, $field, $value){
      $query = "UPDATE ".TBL_CATEGORY." SET ".$field." = :value WHERE id = :id";
       $stmt = $this->connection->prepare($query);
       return $stmt->execute(array(':id' => $id, ':value' => $value));
   }

   /**
    * updateProdField - Updates a field, specified by the field
    * parameter, in the Product row of the database.
    */
   function updateProdField($id, $field, $value){
      $query = "UPDATE ".TBL_PRODUCTS." SET ".$field." = :value WHERE id = :id";
       $stmt = $this->connection->prepare($query);
       return $stmt->execute(array(':id' => $id, ':value' => $value));
   }

   /**
    * updateKBField - Updates a field, specified by the field
    * parameter, in the KB row of the database.
    */
   function updateKBField($id, $field, $value){
      $query = "UPDATE ".TBL_KDB." SET ".$field." = :value WHERE id = :id";
       $stmt = $this->connection->prepare($query);
       return $stmt->execute(array(':id' => $id, ':value' => $value));
   }

   /**
    * newTicket
    */
   function newTicket($tid, $user, $email, $category, $timedate, $subject, $report, $IP, $ownermail, $techmail){
   $query = "INSERT INTO ".TBL_TICKETS." SET tid = :tid, user = :user, email = :email, category = :category, timedate = :timedate, subject = :subject, report = :report, IP = :IP, ownermail = :ownermail, techmail = :techmail";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':tid' => $tid, ':user' => $user, ':email' => $email, ':category' => $category, ':timedate' => $timedate, ':subject' => $subject, ':report' => $report, ':IP' => $IP, ':ownermail' => $ownermail, ':techmail' => $techmail));
   }

   /**
    * metaUpdate
    */
   function metaUpdate($tid, $status, $tech, $assigned, $rating, $notes, $owner){
   $query = "SELECT * FROM ".TBL_META." WHERE tid = :tid";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':tid' => $tid));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){

   $query = "INSERT INTO ".TBL_META." SET tid = :tid, status = :status, tech = :tech, assigned = :assigned, rating = :rating, notes = :notes, owner = :owner";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':tid' => $tid, ':status' => $status, ':tech' => $tech, ':assigned' => $assigned, ':rating' => $rating, ':notes' => $notes, ':owner' => $owner));

      }

    $query = "UPDATE ".TBL_META." SET status = :status, tech = :tech, assigned = :assigned, rating = :rating, notes = :notes WHERE tid = :tid";
    $stmt = $this->connection->prepare($query);
    return $stmt->execute(array(':tid' => $tid, ':status' => $status, ':tech' => $tech, ':assigned' => $assigned, ':rating' => $rating, ':notes' => $notes));
   }
   /**
    * showMeta
    */
   function showMeta($tid){
    if ($tid == '*') {
       $query = "SELECT * FROM ".TBL_META." ORDER BY id DESC";
       $stmt = $this->connection->prepare($query);
       $stmt->execute(array(':tid' => $tid));
       $dbarray = $stmt->fetchAll();
    }
    else {
       $query = "SELECT * FROM ".TBL_META." WHERE tid = :tid";
       $stmt = $this->connection->prepare($query);
       $stmt->execute(array(':tid' => $tid));
       $dbarray = $stmt->fetchAll();
    }

   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }
   /**
    * ticketUpdate
    */
   function ticketUpdate($tid, $rating, $status, $tech, $assigned, $notes){
   $query = "UPDATE ".TBL_META." SET tid = :tid, rating = :rating, status = :status, tech = :tech, assigned = :assigned, notes = :notes WHERE tid = :tid";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':tid' => $tid, ':rating' => $rating,':status' => $status,':tech' => $tech,':assigned' => $assigned, ':notes' => $notes));
   }

   /**
    * showTickets
    */
   function showTickets($user, $category){
       if (($user == '*') && ($category == '*')) {
           $query = "SELECT * FROM ".TBL_TICKETS." ORDER BY id DESC";
           $stmt = $this->connection->prepare($query);
           $stmt->execute(array());
           $dbarray = $stmt->fetchAll();
       }
       else if ($category == '*') {
           $query = "SELECT * FROM ".TBL_TICKETS." WHERE user = :user ORDER BY id DESC";
           $stmt = $this->connection->prepare($query);
           $stmt->execute(array(':user' => $user));
           $dbarray = $stmt->fetchAll();
       }
       else if ($user == '*') {
           $query = "SELECT * FROM ".TBL_TICKETS." WHERE category = :category ORDER BY id DESC";
           $stmt = $this->connection->prepare($query);
           $stmt->execute(array(':category' => $category));
           $dbarray = $stmt->fetchAll();
       }
       else {
           $query = "SELECT * FROM ".TBL_TICKETS." WHERE user = :user AND category = :category ORDER BY id DESC";
           $stmt = $this->connection->prepare($query);
           $stmt->execute(array(':user' => $user, ':category' => $category));
           $dbarray = $stmt->fetchAll();
       }
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * show one Ticket
    */
   function showTicket($tid){
   $query = "SELECT * FROM ".TBL_TICKETS." WHERE tid = :tid ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':tid' => $tid));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }
   /**
    * deleteTicket
    */
   function deleteTicket($tid){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_TICKETS." WHERE tid = '$tid'");
   $sql->execute();
   $sql = $this->connection->prepare("DELETE FROM ".TBL_META." WHERE tid = '$tid'");
   $sql->execute();
   }

// COMMENTS START

   /**
    * newComment
    */
   function newComment($user, $url, $msg, $status, $ctime){
   $query = "INSERT INTO ".TBL_COMMENTS." SET user = :user, url = :url, msg = :msg, status = :status, ctime = :ctime";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':user' => $user, ':url' => $url, ':msg' => $msg, ':status' => $status, ':ctime' => $ctime));
   }

   /**
    * showComment
    */
   function showComment($url){
   $query = "SELECT * FROM ".TBL_COMMENTS." WHERE url = :url ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':url' => $url));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * showCat
    */
   function showCat($catprod){
   if ($catprod != '*') {
   $query = "SELECT * FROM ".TBL_CATEGORY." WHERE catprod = :catprod ORDER BY catprod ASC";
   }
   else {
   $query = "SELECT * FROM ".TBL_CATEGORY." ORDER BY catprod ASC";
   }
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':catprod' => $catprod));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * showProd
    */
   function showProd(){
   $query = "SELECT * FROM ".TBL_PRODUCTS." ORDER BY id ASC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array());
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * showKB
    */
   function showKB($category){
   if ($category != '*') {
   $query = "SELECT * FROM ".TBL_KDB." WHERE category = :category ORDER BY category DESC";
   }
   else {
   $query = "SELECT * FROM ".TBL_KDB." ORDER BY category DESC";
   }
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':category' => $category));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * showCommentUser
    */
   function showCommentUser($user){
   $query = "SELECT * FROM ".TBL_COMMENTS." WHERE user = :user ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':user' => $user));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      return $dbarray;
   }

   /**
    * deleteComment
    */
   function deleteComment($id){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_COMMENTS." WHERE id = '$id'");
   $sql->execute();
   }

   /**
    * deleteCat
    */
   function deleteCat($id){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_CATEGORY." WHERE id = '$id'");
   $sql->execute();
   }

   /**
    * deleteProd
    */
   function deleteProd($id){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_PRODUCTS." WHERE id = '$id'");
   $sql->execute();
   }

   /**
    * deleteKB
    */
   function deleteKB($id){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_KDB." WHERE id = '$id'");
   $sql->execute();
   }

   /**
    * reportComment
    */
   function reportComment($id, $reporter, $cmt_status){
    $query = "UPDATE ".TBL_COMMENTS." SET status = '$cmt_status', reporter = '$reporter' WHERE id = '$id'";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':status' => $cmt_status, ':reporter' => $reporter));
   }

    /**
    * adminComment
    */
   function adminComment($cmt_status){
   $query = "SELECT * FROM ".TBL_COMMENTS." WHERE status = '$cmt_status' ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':status' => $cmt_status));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      $_SESSION['cmt_count'] = 0;
      return NULL;
      }
      /* Return result array */
      $_SESSION['cmt_count'] = $result;
      return $dbarray;
   }

// COMMENTS END

// MESSAGES START

   /**
    * newMessage
    */
   function newMessage($user, $towho, $msg, $status, $ctime){
   $query = "INSERT INTO ".TBL_MESSAGES." SET user = :user, towho = :to, msg = :msg, status = :status, ctime = :ctime";
   $stmt = $this->connection->prepare($query);
   return $stmt->execute(array(':user' => $user, ':to' => $towho, ':msg' => $msg, ':status' => $status, ':ctime' => $ctime));
   }

   /**
    * showMessage (user sent)
    */
   function showMessage($user){
   $query = "SELECT * FROM ".TBL_MESSAGES." WHERE user = :user ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':user' => $user));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      $_SESSION['mym_count'] = $result;
      return $dbarray;
   }
   /**
    * showMessage (user received)
    */
   function showMessageTM($towho){
   $query = "SELECT * FROM ".TBL_MESSAGES." WHERE towho = :towho OR towho = 'all' ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':towho' => $towho));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      return NULL;
      }
      /* Return result array */
      $_SESSION['mtm_count'] = $result;
      $_SESSION['unread'] = 0;
      foreach ($dbarray as $unred) {
      //  if ((($unred['status']) === '0') || (($unred['status']) === '10')) { $_SESSION['unread']++; }
      if ((($unred['status']) === '0')) { $_SESSION['unread']++; }
      }
      return $dbarray;
   }
   /**
    * showReported (user reported messages)
    */
   function showReported($status){
   $query = "SELECT * FROM ".TBL_MESSAGES." WHERE status = :status ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':status' => $status));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      $_SESSION['rep_count'] = 0;
      return NULL;
      }
      /* Return result array */
      $_SESSION['rep_count'] = $result;
      return $dbarray;
   }

   /**
    * deleteMessage
    */
   function deleteMessage($id){
   $sql = $this->connection->prepare("DELETE FROM ".TBL_MESSAGES." WHERE id = '$id'");
   $sql->execute();
   }

   /**
    * reportMessage
    */
   function reportMessage($id, $reporter, $msg_status){
    $query = "UPDATE ".TBL_MESSAGES." SET status = '$msg_status', reporter = '$reporter' WHERE id = '$id'";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':status' => $msg_status, ':reporter' => $reporter));
   }

   /**
    * SEARCH
    */
   function searcher($search){
   $query = "SELECT * FROM ".TBL_KDB." WHERE question LIKE '%$search%' OR answer LIKE '%$search%' ORDER BY id DESC";
   $stmt = $this->connection->prepare($query);
   $stmt->execute(array(':question' => $search, ':answer' => $search));
   $dbarray = $stmt->fetchAll();
   $result = count($dbarray);
      if(!$dbarray || $result < 1){
      $_SESSION['search'] = 0;
      return NULL;
      }
      /* Return result array */
      $_SESSION['search'] = $result;
      return $dbarray;
   }

// MESSAGES END




   /**
    * getUserInfo - Returns the result array from a mysql
    * query asking for all information stored regarding
    * the given username. If query fails, NULL is returned.
    */
    function getUserInfo($username){
	$query = "SELECT * FROM ".TBL_USERS." WHERE username = :username";
	$stmt = $this->connection->prepare($query);
	$stmt->execute(array(':username' => $username));
	$dbarray = $stmt->fetch();
      /* Error occurred, return given name by default */
    $result = count($dbarray);
      if(!$dbarray || $result < 1){
         return NULL;
      }
      /* Return result array */
      return $dbarray;
   }
   
   /**
    * checkUserEmailMatch - Checks whether username
    * and email match in forget password form.
    */
   function checkUserEmailMatch($username, $email){
   	
	$query = "SELECT username FROM ".TBL_USERS." WHERE username = :username AND email = :email";
	$stmt = $this->connection->prepare($query);
	$stmt->execute(array(':username' => $username, ':email' => $email));
	$number_of_rows = $stmt->rowCount();
	    
      if(!$stmt || $number_of_rows < 1){
         return 0;
      } else {
      return 1;
    }
   }
   
   /**
    * getNumMembers - Returns the number of signed-up users
    * of the website, banned members not included. The first
    * time the function is called on page load, the database
    * is queried, on subsequent calls, the stored result
    * is returned. This is to improve efficiency, effectively
    * not querying the database when no call is made.
    */
   function getNumMembers(){
      if($this->num_members < 0){
        $result =  $this->connection->query("SELECT username FROM ".TBL_USERS);
        $this->num_members = $result->rowCount(); 
      }
      return $this->num_members;
   }
   
   /**
    * getLastUserRegistered - Returns the username of the last
    * member to sign up and the date.
    */
   function getLastUserRegisteredName() {
        $result = $this->connection->query("SELECT username, regdate FROM ".TBL_USERS." ORDER BY regdate DESC LIMIT 0,1");
        $this->lastuser_reg = $result->fetchColumn();
      return $this->lastuser_reg;
   }
   
   /**
    * getLastUserRegistered - Returns the date of the last
    * member to sign up and the date.
    */
   function getLastUserRegisteredDate() {
         $result = $this->connection->query("SELECT username, regdate FROM ".TBL_USERS." ORDER BY regdate DESC LIMIT 0,1"); 
         $this->lastuser_reg = $result->fetchColumn(1);
      return $this->lastuser_reg;
   }
   
   /**
    * calcNumActiveUsers - Finds out how many active users
    * are viewing site and sets class variable accordingly.
    */
   function calcNumActiveUsers(){
	/* Calculate number of USERS at site */
    $sql = $this->connection->query("SELECT * FROM ".TBL_ACTIVE_USERS);
    $this->num_active_users = $sql->rowCount();
   }
   
   /**
    * calcNumActiveGuests - Finds out how many active guests
    * are viewing site and sets class variable accordingly.
    */
   function calcNumActiveGuests(){
    /* Calculate number of GUESTS at site */
   	$sql = $this->connection->query("SELECT * FROM ".TBL_ACTIVE_GUESTS);
	$this->num_active_guests = $sql->rowCount();       
	}
   
   /**
    * addActiveUser - Updates username's last active timestamp
    * in the database, and also adds him to the table of
    * active users, or updates timestamp if already there.
    */
   function addActiveUser($username, $time){
   	  $config = $this->getConfigs();
	  
	  // new - this checks how long someone has been inactive and logs them off if neccessary unless
	  // they have cookies (remember me) set.
	  
      $query = "SELECT * FROM ".TBL_USERS." WHERE username = :username";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username));
	  
	  $dbarray = $stmt->fetch();
      $db_timestamp = $dbarray['timestamp'];
      $timeout = time()-$config['USER_TIMEOUT']*60;
      if($db_timestamp < $timeout && !isset($_COOKIE['cookname']) && !isset($_COOKIE['cookid'])) header("Location:".$config['WEB_ROOT']."admin/process.php");
	    
   	  $query = "UPDATE ".TBL_USERS." SET timestamp = :time WHERE username = :username";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username, ':time' => $time));
	  
      if(!$config['TRACK_VISITORS']) return;
      $query = "REPLACE INTO ".TBL_ACTIVE_USERS." VALUES (:username, :time)";
	  $stmt = $this->connection->prepare($query);
	  $stmt->execute(array(':username' => $username, ':time' => $time));
	  
      $this->calcNumActiveUsers();
   }
   
   /* addActiveGuest - Adds guest to active guests table */
   function addActiveGuest($ip, $time){
   	  $config = $this->getConfigs();
      if(!$config['TRACK_VISITORS']) return;
      $sql =  $this->connection->prepare("REPLACE INTO ".TBL_ACTIVE_GUESTS." VALUES ('$ip', '$time')");
      $sql->execute();
      $this->calcNumActiveGuests();
   }
   
   /* These functions are self explanatory, no need for comments */
   
   /* removeActiveUser */
   function removeActiveUser($username){
   	  $config = $this->getConfigs();
      if(!$config['TRACK_VISITORS']) return;
      $sql = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_USERS." WHERE username = '$username'");
      $sql->execute();
      $this->calcNumActiveUsers();
   }
   
   /* removeActiveGuest */
   function removeActiveGuest($ip){
   	  $config = $this->getConfigs();
      if(!$config['TRACK_VISITORS']) return;
      $sql = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE ip = '$ip'");
      $sql->execute();
      $this->calcNumActiveGuests();
   }
   
   /* removeInactiveUsers */
   function removeInactiveUsers(){
   	  $config = $this->getConfigs();
      if(!$config['TRACK_VISITORS']) return;
      $timeout = time()-$config['USER_TIMEOUT']*60;
      $stmt = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_USERS." WHERE timestamp < $timeout");
      $stmt->execute();
      $this->calcNumActiveUsers();
   }

   /* removeInactiveGuests */
   function removeInactiveGuests(){
   	  $config = $this->getConfigs();
      if(!$config['TRACK_VISITORS']) return;
      $timeout = time()-$config['GUEST_TIMEOUT']*60;
      $stmt = $this->connection->prepare("DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE timestamp < $timeout");
      $stmt->execute();
      $this->calcNumActiveGuests();
   }
   
};

/* Create database connection */
$database = new MySQLDB;

?>
