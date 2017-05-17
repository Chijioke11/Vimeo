<?php
function CompleteUpload($upload_complete_url) {
	$method = "DELETE";
	$headers = array(
    "Content-type: application/json;",
    "Accept: " . VERSION_STRING,
    "User-Agent: " . USER_AGENT,
    "Authorization: Bearer " . ACCESS_TOKEN
);
	$ch4 = curl_init();
	$upload_complete_url = ROOT_ENDPOINT.$upload_complete_url;//
	curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch4, CURLOPT_URL, $upload_complete_url);
    curl_setopt($ch4, CURLOPT_POST, true);
	curl_setopt($ch4, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch4, CURLOPT_HEADER, true);
	curl_setopt($ch4, CURLOPT_HTTPHEADER, $headers);
	
	$server_output = curl_exec($ch4);
	$curl_info     = curl_getinfo($ch4);
	curl_close($ch4);
	
	$ff = fopen('completeupload.txt', 'a');
	fwrite($ff, $server_output);
	
	$header_size   = $curl_info['header_size'];
	$http_code     = $curl_info['http_code'];
	$headers       = substr($server_output, 0, $header_size);
	$Location = parseR($headers);
	if ($http_code < 400) {
	if (isset($Location['Location'])) {
		$Location = $Location['Location'];
		$Location = GetVideoID($Location);
		$Location = ROOT_ENDPOINT."/".$Location;
		$metaddata = json_encode(array('name' => NAME, 'description' => DESC, 'privacy.view' => PRIVACY));
		//EditVideo($metaddata, $Location);
	}
	else {
		//echo $server_output;
		echo "Location not found!";
	}
	}
	//echo "Ticket ID: $upload_ticket_id";
	echo "Complete Video: $upload_complete_url $server_output";//For debugging purpose
}





    
	

	

    	
?>