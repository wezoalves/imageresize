<?php
namespace ImgOnthefly;

use ImgOnthefly\ParseUrl;
use ImgOnthefly\ParseStr;

final class NameFile
{

  public function __construct(public string $url, public int $width = 1080, public int $height = 1920, public string $crop = "smart", private string $name = ""){  

    $this->url = $this->fixUrl($url);

    $path = (new ParseUrl($this->url))->getName();
    $hash = hash('sha256', $this->url);
    $extension = (new ParseStr($path))->getExtension();
    $this->name = "{$hash}{$extension}";

  }

  public function getOriginal() : string
  {
    $hash = hash('crc32', "original");
    return "{$hash}-{$this->name}";
  }

  public function getOptimized() : string
  {
    $hash = hash('crc32', "{$this->width}{$this->height}{$this->crop}");
    return "{$hash}-{$this->name}";
  }

  public function getFolder() : string
  {
    return (new ParseUrl($this->url))->getHost();
  }

  /**
   * 1 FOR ORIGINAL
   * 2 FOR OPTIMIZED
   */
  public function getFile(int $type) : string|bool
  {

    $imagePath = dirname(__FILE__) . "/../src/image/{$this->getFolder()}/";

    $fileCkeck = $type == 1 ? "{$imagePath}{$this->getOriginal()}" : "{$imagePath}{$this->getOptimized()}";

    if(!is_dir($imagePath)){
      mkdir($imagePath);
    }

    if(file_exists($fileCkeck)):

      return $fileCkeck;

    endif;

    return false;
  }

  public function fixUrl($url) : string
  {
    $url = strtr($url, [
      "https" => "",
      "http" => "",
      "//" => "",
      ":" => "",
    ]);

    $url = "https://{$url}";

    return $url;
  }
}
