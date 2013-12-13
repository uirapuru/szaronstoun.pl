<?php

$sFilename = $_FILES["upload"]["name"];
$sFiletype = $_FILES["upload"]["type"];
$sFiletmp = $_FILES["upload"]["tmp_name"];
$iError = $_FILES["upload"]["error"];

$aAllowedTypes = array(
    "iamge/jpeg",
    "iamge/png",
    "iamge/gif",
);


$sUploadUrl = "/gfx/_upload";
$sUploadDir = realpath(dirname(__FILE__) . "/../.." . $sUploadUrl);

if (!$sUploadDir) {
    throw new Exception("Upload dir not found");
}

if ($iError == 0) {
    if (in_array($sFiletype, $aAllowedTypes)) {
        if (move_uploaded_file($sFiletmp, $sUploadDir . "/" . $sFilename)) {
            echo $sUploadUrl . $sFilename;
        }
        else {
            throw new Exception("Move error!");
        }
    }
    else {
        throw new Exception("Incorrect type!");
    }
}
else {
    throw new Exception("Upload error!");
}