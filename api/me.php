<?php
ini_set("display_errors", 0);
require '../config.php';
require '../vendor/autoload.php';

use Predis\Collection\Iterator;
Predis\Autoloader::register();
$redis = new Predis\Client('tcp://127.0.0.1:6379');
$redis->connect();


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('HTTP/1.1 200 OK');
  exit('');
}

if(isset($_GET['token']) && $_GET['token'] !== "") {

  $sql = mysqli_connect($sql_hostname, $sql_username, $sql_password, $sql_database);
  if (mysqli_connect_errno()) {
    header('HTTP/1.1 503 Service Unavailable');
    exit("Failed to connect to database");
  }
  else{
    mysqli_query($sql, "SET NAMES 'utf8'");

    if ($stmt = mysqli_prepare($sql, "SELECT `user_id`,`email`,`user_limit`,`user_limit_ttl`,`user_quota`,`user_quota_ttl` FROM `users` WHERE `api_key`=? LIMIT 0,1")) {

      mysqli_stmt_bind_param($stmt, "s", $_GET['token']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      mysqli_stmt_bind_result($stmt, $user_id, $user_email, $user_limit, $user_limit_ttl, $user_quota, $user_quota_ttl);
      if( mysqli_stmt_num_rows($stmt) == 0) {
        header('HTTP/1.1 403 Forbidden');
        exit("Invalid API token");
      }
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($sql);
}
else {
  $user_id = null;
  $user_email = $_SERVER['HTTP_X_FORWARDED_FOR'];
  $user_limit = 10;
  $user_limit_ttl = 60;
  $user_quota = 150;
  $user_quota_ttl = 86400;
}

$client_id = $user_id ?? $_SERVER['HTTP_X_FORWARDED_FOR'];
$limit_id = $client_id."_limit"; // reqeust per minute
$quota_id = $client_id."_quota"; // quota per day

$limit = $user_limit;
$limit_ttl = $user_limit_ttl;
if($redis->exists($limit_id)){
  $limit = intval($redis->get($limit_id));
  $limit_ttl = $redis->ttl($limit_id);
}

$quota = $user_quota;
$quota_ttl = $user_quota_ttl;
if($redis->exists($quota_id)){
  $quota = intval($redis->get($quota_id));
  $quota_ttl = $redis->ttl($quota_id);
}

header('Content-Type: application/json');

$res = array(
  'user_id' => $user_id,
  'email' => $user_email,
  'limit' => $limit,
  'limit_ttl' => $limit_ttl,
  'quota' => $quota,
  'quota_ttl' => $quota_ttl,
  'user_limit' => $user_limit,
  'user_limit_ttl' => $user_limit_ttl,
  'user_quota' => $user_quota,
  'user_quota_ttl' => $user_quota_ttl
);
echo json_encode($res);


?>
