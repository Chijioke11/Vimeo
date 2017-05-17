<?php

function VerifyUpload($fileSize, $fileType, $fileName, $upload_link_secure, $upload_complete_url) {
	$method = "PUT";
	$headers = array(
	"Content-Length: 0",
	"Content-Range: " . "bytes */*"
);

	$ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch3, CURLOPT_URL, $upload_link_secure);
    curl_setopt($ch3, CURLOPT_POST, true);
	curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch3, CURLOPT_HEADER, true);
	curl_setopt($ch3, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
	
	$server_output = curl_exec($ch3);
	$curl_info     = curl_getinfo($ch3);
	curl_close($ch3);
	
	$header_size   = $curl_info['header_size'];
	$http_code     = $curl_info['http_code'];
	$headers       = substr($server_output, 0, $header_size);
	$byte_range    = parseR($headers);
	$byte_range_data = $byte_range['Range'];
	if (!empty($byte_range_data)) {
		$val1 = explode('-', $byte_range_data);
		$byte_range_data = (int)trim($val1[1]);
	}
	
	echo $server_output." Byte range: $byte_range_data";
	$ff = fopen('verifyupload.txt', 'a');
	fwrite($ff, "Http_code: ".$http_code." Byte Range: ". $byte_range_data);
	if ($byte_range_data >= $fileSize) {
		CompleteUpload($upload_complete_url);
		//echo "File have been completely uploaded";//its time to call the completeupload function
	}
	else {
		$start = $fileSize - $byte_range_data;
		//UploadVideo(FILE_SIZE, FILE_TYPE, FILE_NAME, $start, UPLOAD_LINK);
		UploadVideo($fileSize, $fileType, $fileName, $start, $upload_link_secure);
	}
	if ($http_code == 308) {
		//echo "Eme chabegim";
		//UploadVideo($fileSize, $fileType, $fileName, $start, $upload_link_secure);
		echo "Eme chalam Upload file size: $fileSize Uploaded to server size: $byte_range_data";
	}

}