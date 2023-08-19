<?php

include("../vendor/autoload.php");

use Grafika\Grafika;
use ImgOnthefly\NameFile;

$url = $_GET['url']??null;
$width = $_GET['w']??null;
$height = $_GET['h']??null;
$crop = $_GET['crop']??"smart";


if(!$url){
  header('HTTP/1.0 404 Not Found', true, 404);
  header('Content-Type: image/gif');
  echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
  exit();
}


$file = new NameFile($url, $width, $height, $crop);

// if image original not exists
if(!$file->getFile(1)){

  // storage image
  $saved = @file_put_contents(dirname(__FILE__) . "/image/{$file->getFolder()}/{$file->getOriginal()}", file_get_contents($file->url));

  // on error, return placeholder
  if(!$saved){
    header('HTTP/1.0 500 Internal Server Error', true, 500);
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
    exit();
  }

}

// if image optimized not exists
if(!$file->getFile(2)){

  // create a image optimized
  $editor = Grafika::createEditor();
  $editor->open( $image, $file->getFile(1) );
  $editor->resizeFill( $image, $width, $height, $crop );
  $editor->save( $image, "image/{$file->getFolder()}/{$file->getOptimized()}");
}

// if optimized image not created, return original image
$fileOut = $file->getFile(2) ?? $file->getFile(1);

$imageInfo = getimagesize($fileOut);
$fp = fopen($fileOut, 'rb');
header('Cache-Control: max-age=86400');
header('Content-Length: ' . filesize($fileOut));
header('Content-Type: ' . $imageInfo['mime']);
header('Pragma: cache');
fpassthru($fp);