<html>
<head>
<title>Upload Form</title>
<style>
body{margin:0;padding:0;}
</style>
</head>
<body>
<div style="padding:20px;">
<?php echo $error;?>

<?php echo form_open_multipart('uploadmyfile/do_upload');?>
<input type="hidden" name="inputid" id="inputid" value="<?php echo isset($picid) ? $picid : 'picture';?>" />
<input type="file" name="userfile" size="20" style="border:1px solid #eee;" /><input type="submit" value="上传" />

</form>
</div>
</body>
</html>