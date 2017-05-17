<?php


/*
$fileSize (string), size of the uploaded file
$fileType (string), type of the uploaded file
$fileName (array),  array data from CurlFile object of the uploaded file 
$start  (int),     indicate the last byte sent to the server
$upload_link_secure (string), URL to upload file
*/


//$fileSize = $_FILES['file_data']['size'];
//$fileType = $_FILES['file_data']['type'];
//$fileName['file_data'] = new CurlFile($_FILES['file_data']['tmp_name'],$_FILES['file_data']['type'],"newfile".date("Ymdhis")."mp4");
//$start = 0;

define('FILE_SIZE', $fileSize);
define('FILE_TYPE', $fileType);
//define('FILE_NAME', $fileName);
define('UPLOAD_LINK', $upload_link_secure);
define('COMPLETE_URI', $upload_complete_url);
define('NAME', strip_tags($_POST['videoname']));
define('DESC', strip_tags($_POST['videodescription']));
define('PRIVACY', $private);


function UploadVideo($fileSize, $fileType, $fileName, $start, $upload_link_secure) {
$method = "PUT";//request method {POST, PUT, GET, DELETE, PATCH}
//Set request headers, specify file type and content-length
if ($start > 0) {
	$headers = array(
	"Content-Length: " . $fileSize,
	"Content-Type: " . $fileType,
	"Content-Range: bytes ". ($start + 1)-($fileSize)/($fileSize)
);
}
if ($start == 0) {
$headers = array(
	"Content-Length: " . $fileSize,
	"Content-Type: " . $fileType
);
}
	//$fileName = fopen($fileName, 'r');
	$ch2 = curl_init();
	//curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);// return data
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);// don't verify peer SSL cert.
	curl_setopt($ch2, CURLOPT_URL, $upload_link_secure);// upload url link
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $fileName);
	curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch2, CURLOPT_HEADER, true);
    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
	
	$server_output = curl_exec($ch2);
	$curl_info     = curl_getinfo($ch2);
	curl_close($ch2);

	$header_size   = $curl_info['header_size'];
	$http_code     = $curl_info['http_code'];
	$headers       = substr($server_output, 0, $header_size);
	
	echo "$fileType "." $fileSize ".$server_output;
	$ff = fopen('uploadvideo.txt', 'a');
	fwrite($ff, $server_output."Http_code: ".$http_code."Header size: ".$header_size.$upload_link_secure);
	VerifyUpload($fileSize, $fileType, $fileName, $upload_link_secure, COMPLETE_URI);
}    
    	
		
		

		

