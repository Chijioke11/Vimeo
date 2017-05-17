<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> :: Vimeo API Upload :: </title>
<?php echo '<link rel="stylesheet" href="css/bootstrap.css">'; ?>
<script type="text/javascript">

</script>
<style>
#progress-container {
-webkit-box-shadow: none;
box-shadow: inset none;
display:none;
}
.page-header {
padding-bottom: 18px;
margin: 25px 0 12px;
}
.container {
width: 50%;
margin:0px auto;
}
.lead {
font-size: 18px;
margin-bottom: 12px;
}
</style>
</head>

<body>

<!--<form method="POST" action="" enctype="multipart/form-data">
<input id="browse" type="file" name="file_data">
<input type="submit" name="submit" value="Upload Video" class="btn btn-block btn-info">
</form> -->



<div class="container">

<div class="page-header">
</div>
<div class="row">
          <div class="col-md-12">
            <div id="results"></div>
          </div>
        </div>
<div class="row">
<div class="col-md-8">
<div id="progress-container" class="progress">
<div id="progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 0%">&nbsp;0%
</div>
</div>
<form name='lenga' id='lenga' method="POST" action="getticket.php" enctype="multipart/form-data">
<div class="form-group">
<input type="text" name="name" id="videoName" class="form-control" placeholder="Video name" value=""></input>
</div>
<div class="form-group">
<input type="text" name="description" id="videoDescription" class="form-control" placeholder="Video description" value=""></input>
</div>
<div class="checkbox">
<label>
<input type="checkbox" id="upgrade_to_1080" name="upgrade_to_1080" value="upgrage1080"> Upgrade to 1080 </input>
</label>
</div>
<div class="checkbox">
<label>
<input type="checkbox" id="make_private" name="make_private" value="private"> Make Private </input>
</label>
</div>
<div class="form-group">
<label class="btn btn-block btn-info">
<input id="browse" type="file" name="file_data">
</label>
</div>
<div class="form-group">
<input type="submit" id="uploadSubmit" value="Upload Video" class="btn btn-block btn-info">
</div>
</form>

</div>
</div>
</div>
<script src="js/jquery_2_1_4.min.js" type="text/javascript"></script>
<script src="js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#uploadSubmit').click(function(event) {
$('#progress-container').css('display', 'block');
var status = $('#results');
var name = document.getElementById('videoName').value;
var description = document.getElementById('videoDescription').value;
var private = $('#make_private').fieldValue(false);
//var private = document.getElementById('make_private').checked;
// prepare Options Object 
var options = { 
	type: 'POST', 
    url: 'getticket.php', 
	clearForm: true,
	data: { videoname: name, videodescription: description, privacy: private },
    beforeSend: function() {
        status.empty();
		updateProgress(0);
    },
	uploadProgress: function(event, position, total, percentComplete) {
        updateProgress(percentComplete);
    },
	error: function(data) {
	    status.html('Error Uploading File, try again...');
	},
	success: function() {
		updateProgress(100);
    },
	complete: function(xhr) {
		status.html(xhr.responseText);
		$('input#browse').val('');
	}
};
$('#lenga').ajaxForm(options);
});
});
function updateProgress(progress) {
$('#progress').css('width', + progress + '%');
$('#progress').html('&nbsp;' + progress + '%');
}
</script>
</body>
</html>