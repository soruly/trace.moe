<?php
ini_set("display_errors", 0);

header('Content-Type: application/json');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://192.168.2.12:8983/solr/admin/cores?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);

$numDocs = 0;
$sizeInBytes = 0;
$lastModified = 0;
foreach ($result->status as $core) {
  $numDocs += $core->index->numDocs;
  $sizeInBytes += $core->index->sizeInBytes;
  if(strtotime($core->index->lastModified) > $lastModified){
    $lastModified = strtotime($core->index->lastModified);
  }
}

$response = array(
  'lastModified' => $lastModified,
  'sizeInBytes' => $sizeInBytes,
  'numDocs' => $numDocs
);

echo json_encode($response);


?>
