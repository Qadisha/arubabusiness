<?php

class Configuration
{
	
	static public $host = 'https://api.arubabusiness.it';
	
	static public $apiKey = '***API***';		
        static public $userName = '***USER***';
	static public $password = '***PASS***';
	static public $token ='';
	
	
    static function AcquireToken()
	{
		
			$resourcePath = "/auth/token";
			$url= Configuration::$host. $resourcePath;
						
			$headr = array();
			$headr[] = 'Content-Type: application/x-www-form-urlencoded';
			$headr[] = 'Authorization-Key: '.Configuration::$apiKey;

			$post ="grant_type=password&username=".Configuration::$userName."&password=".Configuration::$password;

		
			$crl = curl_init();

			curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
			curl_setopt($crl, CURLOPT_URL, $url);
			curl_setopt($crl, CURLOPT_POST, 1);
			curl_setopt($crl, CURLOPT_POSTFIELDS,$post);
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

			$rest = curl_exec($crl);
			curl_close($crl);
			

			$obj_Token = json_decode($rest);
			
			Configuration::$token = $obj_Token->access_token;

			return Configuration::$token;
	}	
	
	
	
	static function GetUrl($resourcePath, $params)
	{
	    if(!empty($params))
		{
			foreach ($params as $key => $item) {
			   $resourcePath = str_replace($key, $item, $resourcePath);
		   }
		}
		return Configuration::$host. $resourcePath;
	}
	
    static function GetHeader($token, $qdput)
	{
		$headr = array();
                if( strlen($qdput) > 1 ){
                    $headr[] = 'Content-length: '. strlen($qdput);
                } else {
                    $headr[] = 'Content-length: 0';
                }
		$headr[] = 'Content-type: application/json';
		$headr[] = 'Authorization: Bearer '.$token;
		$headr[] = 'Authorization-Key: '.Configuration::$apiKey;
		
		return $headr;
	}

}



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
