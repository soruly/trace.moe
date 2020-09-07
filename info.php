<?php
ini_set("display_errors", 0);
require "./config.php";

header("Content-Type: application/json");

if(isset($_GET["anilist_id"])){
  $sql = mysqli_connect($sql_anime_hostname, $sql_anime_username, $sql_anime_password, $sql_anime_database);
  mysqli_query($sql, "SET NAMES 'utf8'");
  if ($stmt = mysqli_prepare($sql, "SELECT `json` FROM `anilist_view` WHERE `id`=? LIMIT 0,1")){
    $anilist_id = intval($_GET["anilist_id"]);
    mysqli_stmt_bind_param($stmt, "i", $anilist_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $json);
    mysqli_stmt_fetch($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0) {
      echo(json_encode([json_decode($json)]));
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($sql);
}
?>
