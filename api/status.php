<?php
ini_set("display_errors", 0);

header('Content-Type: application/json');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:8983/solr/anime_cl/admin/luke?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);

$response = array(
  'lastModified' => $result->index->lastModified,
  'numDocs' => $result->index->numDocs
);

echo json_encode($response);

curl_close($curl);

?>
