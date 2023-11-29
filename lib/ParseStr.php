<?php
namespace ImgOnthefly;

final class ParseStr
{
  
  public function __construct(public string $string){}

  public function getExtension() : string
  {
    return (string) substr($this->string, strrpos($this->string, '.'));
  }

}
