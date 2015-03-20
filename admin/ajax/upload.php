<?php

require_once('../../EZ.php');
if (EZ::isLoggedIn()) {
  $ds = DIRECTORY_SEPARATOR;
  $targetPath = dirname(dirname(dirname(__FILE__))) . $ds . "banners" . $ds;  //4
  if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];
    $targetFile = $targetPath . $_FILES['file']['name'];
    if (!@move_uploaded_file($tempFile, $targetFile)) {
      http_response_code(400);
      die("File move error: {$_FILES['file']['name']} to $targetFile");
    }
  }
}
else {
  http_response_code(400);
  die("Please login before modifying $table!");
}
exit();
