<?php namespace Makinecim;

class Response
{

  /**
   * @var
   */
  private $statusCode;
  /**
   * @var
   */
  private $raw;
  /**
   * @var
   */
  private $data;
  /**
   * @var
   */
  private $included;
  /**
   * @var
   */
  private $meta;
  /**
   * @var array
   */
  private $links = [];
  /**
   * @var array
   */
  private $errors = [];

  /**
   * @return $this
   */
  public function parse()
  {
    if (isset($this->raw->data)) {
      $this->setData($this->raw->data);
    }

    if (isset($this->raw->included)) {
      $this->setIncluded($this->raw->included);
    }

    if (isset($this->raw->links)) {
      $this->setLinks($this->raw->links);
    }

    if (isset($this->raw->meta)) {
      $this->setMeta($this->raw->meta);
    }

    if (isset($this->raw->errors)) {
      $this->setErrors($this->raw->errors);
    }

    return $this;
  }

  public function __get($name)
  {
    if (!function_exists($name)) {
      return $this->$name;
    }
  }

  /**
   * @return mixed
   */
  public function getStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * @param mixed $statusCode
   *
   * @return $this
   */
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getRaw()
  {
    return $this->raw;
  }

  /**
   * @param mixed $raw
   *
   * @return $this
   */
  public function setRaw($raw)
  {
    $this->raw = $raw;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * @param mixed $data
   *
   * @return $this
   */
  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getIncluded()
  {
    return $this->included;
  }

  /**
   * @param mixed $included
   *
   * @return $this
   */
  public function setIncluded($included)
  {
    $this->included = $included;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getMeta()
  {
    return $this->meta;
  }

  /**
   * @param mixed $meta
   *
   * @return $this
   */
  public function setMeta($meta)
  {
    $this->meta = $meta;

    return $this;
  }

  /**
   * @return array
   */
  public function getLinks()
  {
    return $this->links;
  }

  /**
   * @param array $links
   *
   * @return $this
   */
  public function setLinks($links)
  {
    $this->links = $links;

    return $this;
  }

  /**
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * @param array $errors
   *
   * @return $this
   */
  public function setErrors($errors)
  {
    $this->errors = $errors;

    return $this;
  }
}