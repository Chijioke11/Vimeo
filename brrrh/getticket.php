<?php
header("Content-type:application/json");
function GetVideoID($uri)
{
    $uri = urldecode($uri);
    $uri = explode('/', $uri);
    return end($uri);
}

//parse header value to get key and value then add to array
function parseR($header) {
	$header_array = array();
	$headerL = explode("\n", $header);
	foreach($headerL as $param) {
		if(stristr($param, ':')) {
			$key = explode(':', $param);
			$key1 = $key[0];
			$val1 = $key[1];
			$header_array[$key1] = $val1;
		}
	}
	return $header_array;
}

function parse_headers($headers) {
    $final_headers = array();
    $list          = explode("\n", trim($headers));
    $http = array_shift($list);
    foreach ($list as $header) {
        $parts                          = explode(':', $header, 2);
        $final_headers[trim($parts[0])] = isset($parts[1]) ? trim($parts[1]) : '';
    }
    return $final_headers;
}


define('ROOT_ENDPOINT', 'https://api.vimeo.com');
define('VERSION_STRING', 'application/vnd.vimeo.*+json; version=3.2');
define('USER_AGENT', 'vimeo.php 1.2.6; (http://developer.vimeo.com/api/docs)');
define('CERTIFICATE_PATH', '/api/certificates/vimeo-api.pem');
define('ACCESS_TOKEN_ENDPOINT', '/oauth/access_token');
define('CLIENT_CREDENTIALS_TOKEN_ENDPOINT', '/oauth/authorize/client');
define('ACCESS_TOKEN', 'b965572721951e4bd1a518caaa6e2693');

$client_id     = "3f0e5c18639b3d74b35d58670ea4c0a806956af5";
$client_secret = "N2h7BN0Ow50+uc6g9+y9WJvAfZX2CjmwL/3NGZ88NrASYumE94yHQJyNyAFZvp66rWWcpczTRZio/L0Z+2ADqA7/sNQgmbso7hsNtt4c5YBDMMMV9+Drs+vqz+qkJDG9";
$access_token  = "b965572721951e4bd1a518caaa6e2693"; //scope "public private purchased create edit delete interact upload"
$config        = array(
    'client_id' => "$client_id",
    'client_secret' => "$client_secret",
    'access_token' => "$access_token"
);
$json          = json_encode($config);
$credentials   = base64_encode($client_id . ':' . $client_secret);

$ch      = curl_init();
$headers = array(
    "Content-type: application/json;",
    "Accept: " . VERSION_STRING,
    "User-Agent: " . USER_AGENT,
    "Authorization: Bearer " . $access_token
);

$stream   = json_encode(array(
    'type' => 'streaming',
    'upgrade_to_1080' => true
));
$method   = "POST";
$curl_url = ROOT_ENDPOINT . "/me/videos";
curl_setopt($ch, CURLOPT_URL, $curl_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_POSTFIELDS, $stream);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//Certificate must indicate that the server is the server to which you meant to connect.
//curl_setopt($ch, CURLOPT_CAINFO, realpath(__DIR__) . CERTIFICATE_PATH);

$server_output = curl_exec($ch);
$curl_info     = curl_getinfo($ch);
$header_size   = $curl_info['header_size'];
$headers       = substr($server_output, 0, $header_size);
$body          = substr($server_output, $header_size);
$ticket        = $body;
curl_close($ch);

$dataarray = array(
    'body' => $body,
    'status' => $curl_info['http_code'],
    'headers' => parse_headers($headers)
);

$body = json_decode($body);

if (!empty($body->error)) {
$upload_error = $body->error;
$upload_error_code = $body->error_code;
$upload_developer_message = $body->developer_message;
echo "Error Message: $upload_error \n";
echo "Developer Message: $upload_developer_message \n";
echo "Error Code: $upload_error_code \n";
exit;
}
$upload_link_secure = $body->upload_link_secure;
$upload_user = $body->user;
$upload_ticket_id = $body->ticket_id;
$upload_complete_url = $body->complete_uri;

$name = strip_tags($_POST['videoname']); 
$description = strip_tags($_POST['videodescription']);
$private = $_POST['make_private'];
if (empty($private)) {
$private = "anybody";
}
if (!empty($private)) {
$private = "nobody";
}

$fileSize = $_FILES['file_data']['size'];
$fileType = $_FILES['file_data']['type'];
$fileName['file_data'] = new CurlFile($_FILES['file_data']['tmp_name'],$_FILES['file_data']['type'],"newfile".date("Ymdhis")."mp4");
$start = 0;

require_once ("upload_video.php");
require_once ("verify_upload.php");
require_once ("complete_upload.php");
require_once ("edit_upload.php");


UploadVideo($fileSize, $fileType, $fileName, $start, $upload_link_secure);
//VerifyUpload($fileSize, $fileType, $fileName, $upload_link_secure, $upload_complete_url);
//echo "Ticket ID: $upload_ticket_id";
exit;

/*
	$headers = array(
    "Accept: " . VERSION_STRING,
    "User-Agent: " . USER_AGENT,
	"Content-Length: " . $fileSize,
	"Content-Type: " . $fileType,
	"Content-Range: " "
);

		function Checkfile($filesize, $content_length, $content_type, $data, $headers, $upload_link_secure) {
		$ch      = curl_init();
		$method   = "PUT";
		$curl_url = $upload_link_secure;
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $stream);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$server_output = curl_exec($ch);
		$curl_info     = curl_getinfo($ch);
		$header_size   = $curl_info['header_size'];
		$http_code     = $curl_info['http_code'];
		$headers       = substr($server_output, 0, $header_size);
		if ($http_code == 308) {
			Checkfile($filesize, $content_length, $content_type, $data, $headers, $upload_link_secure);
		}
	}

	
		

    curl_close ($ch);
    
    $uri = GetVideoID($redirectURL);
    $video_data_uri = "/videos/$uri";
			
   $metaddata = array('name' => "$name", 'description' => "$description", 'privacy.view' => "$private");
   //$lib->request($video_data_uri, $metaddata, 'PATCH');
   
   echo "<div class='alert alert-success'>";
   echo "<strong>Upload Successful</strong>: check uploaded video @ <a href='https://vimeo.com/$uri'> https://vimeo.com/$uri </a>";
   echo "</div>";
*/
?>