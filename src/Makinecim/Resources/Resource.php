<?php namespace Makinecim\Resources;

use Makinecim\Client;
use Makinecim\Request;

class Resource
{

  private $requiresAuthentication = false;
  private $client;
  private $requestLibrary;
  private $response;

  public function __construct(Client $client, $requestLibrary = false)
  {
    $this->client = $client;
    $this->requestLibrary = $requestLibrary ?: new Request;

    return $this;
  }

  /**
   * @param $data
   *
   * @return mixed
   */
  public function create($data)
  {
    return $this->call('post', ['data' => $data]);
  }

  /**
   * @param       $method
   * @param bool  $body
   * @param null  $uriAppend
   * @param array $headers
   * @param bool  $requiresAuthentication
   * @param bool  $buildQueryParams
   *
   * @return mixed
   */
  public function call($method, $body = false, $uriAppend = NULL, $headers = [], $requiresAuthentication = true, $buildQueryParams = true)
  {
    $url = $requiresAuthentication ? $this->client->getApiEndpoint($this->uri) : $this->client->getAuthEndpoint();
    if ($uriAppend) {
      $url .= "/" . $uriAppend;
    }

    $request = clone $this->requestLibrary;
    $request->setUrl($url)->setMethod($method)->addHeaders($headers)->setBody($body);

    return $request->make()->getResponse();
  }

  public function all()
  {
    return $this->call('get');
  }

  public function get($id)
  {
    return $this->call("get", false, $id);
  }
}