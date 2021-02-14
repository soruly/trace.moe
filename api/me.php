<?php
ini_set("display_errors", 0);

header("Content-Security-Policy: default-src 'none'; script-src 'self' www.google-analytics.com static.cloudflareinsights.com; style-src * 'self' 'unsafe-inline'; img-src * 'self' data: blob: media.trace.moe; font-src 'self'; connect-src blob: 'self' api.trace.moe media.trace.moe www.google-analytics.com stats.g.doubleclick.net; media-src blob: 'self' media.trace.moe; object-src 'none'; frame-src www.youtube.com www.google.com; worker-src 'none'; frame-ancestors 'none'; form-action 'self'; block-all-mixed-content; base-uri 'none'; manifest-src 'self'");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Referrer-Policy: no-referrer");
header("X-Content-Type-Options: nosniff");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('HTTP/1.1 200 OK');
  exit('');
}

header("Content-Type: application/json");

$res = array(
  'user_id' => null,
  'email' => $_SERVER['HTTP_X_FORWARDED_FOR'],
  'limit' => $limit,
  'limit_ttl' => $limit_ttl,
  'quota' => $user_quota,
  'quota_ttl' => $user_quota_ttl,
  'user_limit' => 10,
  'user_limit_ttl' => 60,
  'user_quota' => 1000,
  'user_quota_ttl' => 86400
);
echo json_encode($res);

?>
