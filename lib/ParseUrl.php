<?php
namespace ImgOnthefly;

final class ParseUrl
{
  public function __construct(public string $url){}

  public function getHost() : string
  {
    return parse_url($this->url, PHP_URL_HOST) ?? null;
  }

  public function getName()
  {
    return  parse_url($this->url, PHP_URL_PATH) ?? null;
  }
}