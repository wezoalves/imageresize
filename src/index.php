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
  exit();
}

$file = new NameFile($url, $width, $height, $crop);

if(!$file->getFile(1)){

  $saved = file_put_contents(dirname(__FILE__) . "/image/{$file->getFolder()}/{$file->getOriginal()}", file_get_contents($file->url));

  if(!$saved){
    header('HTTP/1.0 500 Internal Server Error', true, 500);
    exit();
  }

}

if(!$file->getFile(2)){

  $editor = Grafika::createEditor();
  $editor->open( $image, $file->getFile(1) );
  $editor->resizeFill( $image, $width, $height, $crop );
  $editor->save( $image, "image/{$file->getFolder()}/{$file->getOtimized()}");
}

$file_out = $file->getFile(2);
$image_info = getimagesize($file_out);
$fp = fopen($file_out, 'rb');

header('Cache-Control: max-age=86400');
header('Content-Length: ' . filesize($file_out));
header('Content-Type: ' . $image_info['mime']);
header('Pragma: cache');
fpassthru($fp);