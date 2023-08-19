<?php
namespace ImgOnthefly;

final class ParseStr
{
  
  public function __construct(public string $string){}

  public function getExtension() : string
  {
    preg_match("/\b(\.jpg|\.JPG|\.png|\.PNG|\.gif|\.GIF)\b/", $this->string, $output_array);
    $el = array_values($output_array);
    return array_shift($el);
  }

}
