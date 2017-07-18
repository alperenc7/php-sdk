<?php namespace Makinecim;

class Client
{

  //  Api endpoint configs
  private $version = "v1";
  private $base = "http://makinecim.com/api";
  private $authURI = "login";

  //  Authentication parameters
  private $client_name;
  private $client_secret;

  // API Configuration
  private $config;

  public function __construct(array $config = [])
  {
    $this->config = array_merge([
      "client_name"   => env("MAKINECIM_CLIENT_NAME"),
      "client_secret" => env("MAKINECIM_CLIENT_SECRET")
    ], $config);

    return $this;
  }

  public function getAuthEndpoint()
  {
    return $this->getApiEndpoint($this->getAuthURI());
  }

  public function getApiEndpoint($uri = NULL)
  {
    $endpoint = $this->getBase() . "/" . $this->getVersion() . "/";
    $endpoint .= $uri ?: "";

    return $endpoint;
  }

  /**
   * @return string
   */
  public function getBase()
  {
    return $this->base;
  }

  /**
   * @param string $base
   */
  public function setBase($base)
  {
    $this->base = $base;
  }

  /**
   * @return string
   */
  public function getVersion()
  {
    return $this->version;
  }

  /**
   * @param string $version
   */
  public function setVersion($version)
  {
    $this->version = $version;
  }

  /**
   * @return string
   */
  public function getAuthURI()
  {
    return $this->authURI;
  }

  /**
   * @param string $authURI
   */
  public function setAuthURI($authURI)
  {
    $this->authURI = $authURI;
  }

  /**
   * @return mixed
   */
  public function getClientSecret()
  {
    return $this->client_secret;
  }

  /**
   * @param mixed $client_secret
   */
  public function setClientSecret($client_secret)
  {
    $this->client_secret = $client_secret;
  }

  /**
   * @return mixed
   */
  public function getClientName()
  {
    return $this->client_name;
  }

  /**
   * @param mixed $client_name
   */
  public function setClientName($client_name)
  {
    $this->client_name = $client_name;
  }

  /**
   * @return mixed
   */
  public function getConfig()
  {
    return $this->config;
  }

  /**
   * @param mixed $config
   */
  public function setConfig($config)
  {
    $this->config = $config;
  }
}