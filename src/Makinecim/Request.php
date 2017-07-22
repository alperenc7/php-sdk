<?php namespace Makinecim;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\MultipartStream;
use Makinecim\Exceptions\InvalidHttpMethodException;

class Request
{

  /**
   * @var HttpClient
   * Guzzle Http client for execute API endpoints
   */
  private $httpClient;

  /**
   * @var
   * The request method
   */
  private $method;

  /**
   * @var
   * Request url for API
   */
  private $url;

  /**
   * @var
   * Request headers
   */
  private $headers;

  /**
   * @var null
   * request body
   */
  private $body = NULL;

  /**
   * @var
   * request response
   */
  private $response;

  /**
   * @var array
   * If exists request params will be stored here
   */
  private $params = [];

  public function __construct($client = NULL)
  {
    $this->httpClient = $client ?: new HttpClient();

    return $this;
  }

  /**
   * @return mixed
   */
  public function getHttpClient()
  {
    return $this->httpClient;
  }

  /**
   * @param mixed $httpClient
   *
   * @return $this
   */
  public function setHttpClient($httpClient)
  {
    $this->httpClient = $httpClient;

    return $this;
  }

  /**
   * @param $headers
   *
   * @return $this
   */
  public function addHeaders($headers)
  {
    foreach ($headers as $name => $value) {
      $this->addHeader($name, $value);
    }

    return $this;
  }

  /**
   * @param $name
   * @param $value
   *
   * @return $this
   */
  public function addHeader($name, $value)
  {
    $this->headers[$name] = $value;

    return $this;
  }

  public function make()
  {
    $this->setDefaultHeaders();
    $result = $this->httpClient->request($this->getMethod(), $this->getUrl(), $this->getPayload());
    $this->response = new Response();
    $this->response->setStatusCode($result->getStatusCode());
    $body = json_decode($result->getBody());
    $this->response->setRaw($body)->parse();

    return $this;
  }

  /**
   * @return $this
   */
  public function setDefaultHeaders()
  {
    $defaultHeaders = [
      'Content-Type'             => 'application/json',
      'Accept'                   => 'application/json',
      'User-Agent'               => Client::USER_AGENT,
      'X-MAKINECIM-SDK-LANGUAGE' => 'php',
      'X-MAKINECIM-SDK-VERSION'  => 'v1'
    ];

    foreach ($defaultHeaders as $name => $value) {
      if (!$this->getHeader($name)) {
        $this->addHeader($name, $value);
      }
    }

    return $this;
  }

  /**
   * @param null $name
   *
   * @return mixed|null
   */
  public function getHeader($name = NULL)
  {
    if ($name == NULL) {
      return $this->getHeaders();
    }
    if (isset($this->headers[$name])) {
      return $this->headers[$name];
    }

    return NULL;
  }

  /**
   * @return mixed
   */
  public function getHeaders()
  {
    return $this->headers;
  }

  /**
   * @param mixed $headers
   *
   * @return $this
   */
  public function setHeaders($headers)
  {
    $this->headers = $headers;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * @param mixed $method
   *
   * @return $this
   */
  public function setMethod($method)
  {
    $method = strtoupper(trim($method));
    if (!in_array($method, ["GET", "POST"])) {
      throw new InvalidHttpMethodException("Only GET and POST methods are available!");
    }
    $this->method = $method;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param mixed $url
   *
   * @return $this
   */
  public function setUrl($url)
  {
    $this->url = mb_strtolower(trim($url));

    return $this;
  }

  /**
   * @return array|mixed
   */
  public function getPayload()
  {
    $payload = [];
    $body = $this->getBody();
    if (!empty($body)) {
      $payload[$this->getBodyKey()] = $body;
    }
    $payload['headers'] = $this->getHeaders();
    $params = $this->getParams();
    if (!empty($params)) {
      $payload['query'] = $params;
    }
    // dd($payload);
    // when sending multipart, specify our boundary and stream the data
    if ($this->getHeader('Content-Type') === 'multipart/form-data') {
      $payload = $this->prepareMultipartPayload($payload);
    }

    return $payload;
  }

  /**
   * @return null
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * @param null $body
   *
   * @return $this
   */
  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  /**
   * @return string
   * @throws Exceptions\InvalidContentType
   */
  public function getBodyKey()
  {
    switch ($this->getHeader('Content-Type')) {
      case 'application/json':
        return 'json';
        break;
      case 'application/x-www-form-urlencoded':
        return 'form_params';
        break;
      case 'multipart/form-data':
        return 'body';
        break;
      default:
        throw new Exceptions\InvalidContentType;
    }
  }

  /**
   * @return array
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * @param array $params
   *
   * @return $this
   */
  public function setParams($params)
  {
    $this->params = $params;

    return $this;
  }

  /**
   * @param $payload
   *
   * @return mixed
   */
  public function prepareMultipartPayload($payload)
  {
    // specify the boundary in the content type header
    $contentType = 'multipart/form-data;';
    $this->addHeader('Content-Type', $contentType);
    $payload['headers']['Content-Type'] = $contentType;

    // remove the multipart
    $payload['body'] = new MultipartStream($payload['body']);

    return $payload;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function setQueryStringParams($params)
  {
    $this->params = $params;

    return $this;
  }
}