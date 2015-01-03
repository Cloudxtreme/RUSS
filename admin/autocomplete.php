<?php
    include("include/session.php");
    $config = $database->getConfigs();
    $connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
    $database = mysql_select_db(DB_NAME);

    if($_POST)
    {
    $q=$_POST['search'];
    $sql_res=mysql_query("select username,email from users where username like '%$q%' or email like '%$q%' order by username LIMIT 5");
    while($row=mysql_fetch_array($sql_res))
    {
    $username=$row['username'];
    $email=$row['email'];
    $b_username=$q;
    $b_email=$q;
    $final_username = str_ireplace($q, $b_username, $username);
    $final_email = str_ireplace($q, $b_email, $email);
    ?>
    <div class="show" align="left">
    <?php echo "<a href='". $config['WEB_ROOT']."admin/index.php?id=6&usertoedit=$final_username'>".$final_username."<span>".$final_email."</span></a>";?>
    </div>
    <?php
    }
}

?>