<?php
if(isset($_SERVER['REMOTE_ADDR'])) exit();
//$histogram_file = '/var/solr/data/anime_cl/histogram.csv';
$histogram_file = '/var/www/whatanime/scripts/histogram.csv';
$csv_data = '';
for($i=0;$i<4096;$i++){
	$hash = str_pad(dechex($i),3,"0",STR_PAD_LEFT);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:8983/solr/anime_cl/select?q=cl_ha%3A".$hash."&wt=json&rows=0");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($curl);
	$result = json_decode($res);
	curl_close($curl);
	$csv_data .= $i.",".intval($result->response->numFound)."\n";
	fwrite(STDOUT, $hash.",".intval($result->response->numFound)."\n");
}
file_put_contents($histogram_file,$csv_data);
?>
