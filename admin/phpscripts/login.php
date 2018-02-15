<?php
  function logIn($username, $password, $ip) {
    require_once('connect.php');
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);
    $loginstring = "SELECT * FROM tbl_user WHERE user_name = '{$username}' AND user_pass = '{$password}'";

    $user_set = mysqli_query($link, $loginstring);
    if(mysqli_num_rows($user_set)){
      $found_user = mysqli_fetch_array($user_set, MYSQLI_ASSOC);
      $id = $found_user['user_id'];
      $_SESSION['user_id'] = $id;
      $_SESSION['user_name'] = $found_user['user_fname'];
      $_SESSION['user_date'] = $found_user['user_date'];
      //$_SESSION['user_attempt'] = $found_user['user_attempt'];


      //update user_date when user login. I tried to use this code and a new database to create a new login page, it works but the user_date
      //didn't change, so I choose to use this folder.
      if(mysqli_query($link, $loginstring)){
        $updatestring = "UPDATE tbl_user SET user_date = NOW() WHERE user_id = {$id}";
        $updatequery = mysqli_query($link, $updatestring);
      }
      redirect_to("admin_index.php");

      if(mysqli_query($link, $loginstring)){
        $updatestring = "UPDATE tbl_user SET user_ip = '$ip' WHERE user_id = {$id}";
        $updatequery = mysqli_query($link, $updatestring);
      }
      redirect_to("admin_index.php");

    }else{
      $message = "username or password is incorrect.<br>please make sure your cap lock key is turning off.";
      return $message;


      //I am tring to get the attempt data in the database and update it when user attempt tp login. everytime the attempt data add 1 and when
      // the attempt data =3, it cannot echo locked so it doesn't work.
      $attemptdata = "SELECT user_attempt FROM tbl_user WHERE user_ip = '$ip'";
      $user_attempt = mysqli_query($link, $attemptdata);
      $attemptwrong = mysqli_fetch_array($user_attempt, MYSQLI_ASSOC);
      $attempts = $attemptwrong['user_attempt'];

      if($attempts<3){
        $updatestring = "UPDATE tbl_user SET user_attempt = '$attempts'+1 WHERE user_id = {$id}";
        $updatequery = mysqli_query($link, $updatestring);
      }else{
        $lock = "locked";
        echo $lock;
      }
      redirect_to("admin_login.php");
    }
    mysqli_close($link);
  }



?>
