<?php
function EditVideo($metaddata, $video_data_uri) {
$method = "PATCH";//request method {POST, PUT, GET, DELETE, PATCH}
//Set request headers, specify file type and content-length
$headers = array(
    "Content-type: application/json;",
    "Accept: " . VERSION_STRING,
    "User-Agent: " . USER_AGENT,
    "Authorization: Bearer " . ACCESS_TOKEN
);
	$ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);// return data
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);// don't verify peer SSL cert.
	curl_setopt($ch2, CURLOPT_URL, $video_data_uri);// upload url link
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $metaddata);
	curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch2, CURLOPT_HEADER, true);
    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
	$server_output = curl_exec($ch2);
	$curl_info     = curl_getinfo($ch2);
	curl_close($ch2);

	$header_size   = $curl_info['header_size'];
	$http_code     = $curl_info['http_code'];
	$headers       = substr($server_output, 0, $header_size);
	
	$ff = fopen('editupload.txt', 'a');
	fwrite($ff, $server_output."Http_code: ".$http_code."Header size: ".$header_size.$video_data_uri);
	$uri = GetVideoID($uri);
	echo "<div class='alert alert-success'>";
    echo "<strong>Upload Successful</strong>: check uploaded video @ <a href='https://vimeo.com/$uri'> https://vimeo.com/$uri </a>";
    echo "</div>";
}    
?>