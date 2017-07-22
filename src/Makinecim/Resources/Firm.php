<?php namespace Makinecim\Resources;

use Makinecim\Resource;

class Firm extends Resource
{

  public $uri = "firms";

  public function __get($name)
  {
    return $this->$name();
  }

  public function listings()
  {
    $this->uri = $this->uri . "/" . $this->id . "/listings";

    return $this;
  }
}