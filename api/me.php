<?php
ini_set("display_errors", 0);
require '../config.php';

if(isset($_GET['token'])) {

  $sql = mysqli_connect($sql_hostname, $sql_username, $sql_password, $sql_database);
  if (mysqli_connect_errno()) {
    header('HTTP/1.1 503 Service Unavailable');
    exit("Failed to connect to database");
  }
  else{
    mysqli_query($sql, "SET NAMES 'utf8'");

    if ($stmt = mysqli_prepare($sql, "SELECT `user_id`,`email`,`quota`,`quota_ttl` FROM `users` WHERE `api_key`=? LIMIT 0,1")){

      mysqli_stmt_bind_param($stmt, "s", $_GET['token']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      mysqli_stmt_bind_result($stmt, $user_id, $user_email, $quota, $quota_ttl);
      if( mysqli_stmt_num_rows($stmt) == 0) {
        header('HTTP/1.1 403 Forbidden');
        exit("Invalid API token");
      }
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($sql);

  header('Content-Type: application/json');

  $res = array(
    'user_id' => $user_id,
    'email' => $user_email,
    'quota' => $quota,
    'quota_ttl' => $quota_ttl
  );
  echo json_encode($res);
}
else{
  header("HTTP/1.1 401 Unauthorized");
  exit("Missing API token");
}


?>
