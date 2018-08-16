<html>
<head>
<title>Upload Form</title>
</head>
<body>

<?php
	$uploadfile = "data/uploads/".$upload_data['orig_name'];
	echo "<script>window.parent.setvalue('$picid','$uploadfile');window.parent.setundisplay();</script>";
?>

</body>
</html>