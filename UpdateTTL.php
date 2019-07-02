<?php

function updateRecord($idRecord, $domain, $newrecord, $updateType){
        
	$token = Configuration::AcquireToken();   
        
        if($updateType == 'TTL'){
            $resourcePath = '/api/domains/dns/ttl';
            $data = array("Domain" => (string)$domain , "Ttl" => (string)$newrecord);
        }      
        $data_json = json_encode($data);
        
	$url= Configuration::GetUrl($resourcePath, $params=NULL);
	$headr = Configuration::GetHeader($token, $qdput = $data_json);

	$crl = curl_init();
	curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
	curl_setopt($crl, CURLOPT_URL, $url);        
  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($crl, CURLOPT_CUSTOMREQUEST, "PUT"); 
  curl_setopt($crl, CURLOPT_POSTFIELDS,$data_json);

	$rest = curl_exec($crl);     
	curl_close($crl);

	var_dump($rest); 
}
