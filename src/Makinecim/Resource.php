<?php namespace Makinecim;

/**
 * Class Resource
 * @package Makinecim
 */
class Resource
{

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var bool
   */
  private $requiresAuthentication = false;
  /**
   * @var Client
   */
  private $client;
  /**
   * @var Request
   */
  private $requestLibrary;
  /**
   * @var
   */
  private $response;
  /**
   * @var array
   */
  private $includes = [];

  /**
   * @var int
   */
  private $page = 1;

  /**
   * Resource constructor.
   *
   * @param Client $client
   * @param bool   $requestLibrary
   */
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
  public function call($method, $body = false, $uriAppend = NULL, $headers = [], $requiresAuthentication = true, $buildQueryParams = true, $requiresClientAuth = true)
  {
    $url = $requiresAuthentication ? $this->client->getApiEndpoint($this->uri) : $this->client->getAuthEndpoint();
    if ($uriAppend) {
      $url .= "/" . $uriAppend;
    }

    // dd($url);

    /**
     * Request library for next request
     * default request library is Makinecim\Request
     */
    $request = clone $this->requestLibrary;
    $request->setUrl($url)->setMethod($method)->addHeaders($headers)->setBody($body);

    /**
     * creates a param query string for next api call
     */
    if ($buildQueryParams) {
      $request->setQueryStringParams($this->buildQueryStringParams());
    }

    if ($requiresAuthentication) {
      $request->addHeader("Authorization", $this->getAccessToken());
    }

    if ($requiresClientAuth) {
      $request->addHeader(Client::CLIENT_TOKEN_HEADER_NAME, $this->client->buildClientTokenHeader());
    }

    /**
     * this method will return a response
     */
    return $request->make()->getResponse();;
  }

  /**
   * @return array
   */
  public function buildQueryStringParams()
  {
    $params = [];
    if ($this->page) {
      $params["page"] = intval($this->page) > 0 ? intval($this->page) : 1;
    }
    if (!empty($this->includes)) {
      $params["include"] = implode(",", $this->includes);
    }

    return $params;
  }

  /**
   * @return string
   */
  public function getAccessToken()
  {
    return "deneme";
  }

  /**
   * @param array $includes , the included resource types
   *
   * @return $this
   */
  public function with($includes = [])
  {
    foreach ($includes as $include) {
      $this->includes[] = strtolower(trim($include));
    }

    return $this;
  }

  /**
   * @param $id
   *
   * @return $this
   */
  public function select($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * @return mixed
   */
  public function all()
  {
    return $this->call('get');
  }

  /**
   * @param $id
   *
   * @return mixed
   */
  public function get($id = NULL)
  {
    return $this->call("get", false, $id);
  }

  /**
   * @return int
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @param int $page
   */
  public function setPage($page)
  {
    $this->page = $page;

    return $this;
  }
}