<?php
if(isset($_SERVER['REMOTE_ADDR'])) exit();

echo microtime();
echo "\n";

//$histogram_file = '/var/solr/data/anime_cl/histogram.csv';
$histogram_file = 'histogram.csv';
$csv_data = '';
file_put_contents($histogram_file,$csv_data);

for($i=256;$i<4096;$i++){
  $csv_data = '';
  for($j=$i;$j<4096;$j++){
    $hash1 = str_pad(dechex($i),3,"0",STR_PAD_LEFT);
    $hash2 = str_pad(dechex($j),3,"0",STR_PAD_LEFT);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:8983/solr/anime_cl/select?q=cl_ha%3A(%2B".$hash1."%20%2B".$hash2.")&wt=json&rows=0");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    $result = json_decode($res);
    curl_close($curl);
    $csv_data .= $i.",".$j.",".intval($result->response->numFound)."\n";
    //if($j % 100 == 0)
      fwrite(STDOUT, $hash1."+".$hash2.",".intval($result->response->numFound)."\n");
  }
  file_put_contents($histogram_file, $csv_data, FILE_APPEND);
}

echo microtime();
echo "\n";

?>