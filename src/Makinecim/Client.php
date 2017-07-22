<?php namespace Makinecim;

use Makinecim\Exceptions\MakinecimClientException;
use Makinecim\Exceptions\MakinecimResourceNotFoundException;

class Client
{

  const USER_AGENT = "makinecim/php-sdk";
  const DEFAULT_ENV_NAME_FOR_CLIENT_NAME = "MAKINECIM_CLIENT_NAME";
  const DEFAULT_ENV_NAME_FOR_CLIENT_SECRET = "MAKINECIM_CLIENT_SECRET";
  const DEFAULT_ENV_NAME_FOR_CLIENT_DOMAIN = "MAKINECIM_CLIENT_DOMAIN";
  const CLIENT_TOKEN_HEADER_NAME = "X-Makinecim-Token";

  /**
   * @var string
   * API endpoints for
   */
  private $version = "v1";
  private $base = "http://makinecim.com/api";
  private $authURI = "login";
  private $currentVersion = "v1";

  //  Authentication parameters
  private $client_name;
  private $client_secret;
  private $client_domain;

  /**
   * @var int
   * Cookie limetime for bearer token
   * 1 Hour
   */
  private $cookieLifetime = 3600;

  /**
   * @var string
   * Cookie name for storing customer bearer token
   */
  private $cookieName = "MakinecimCustomerToken";

  /**
   * @var string
   * Language for multilingual request
   */
  private $defaultLanguage = "tr";

  public function __construct(array $config = [])
  {
    $config = array_merge([
      "client_name"   => getenv(static::DEFAULT_ENV_NAME_FOR_CLIENT_NAME),
      "client_secret" => getenv(static::DEFAULT_ENV_NAME_FOR_CLIENT_SECRET)
    ], $config);
    if (isset($config["client_name"])) {
      $this->setClientName($config["client_name"]);
    }
    if (isset($config["client_secret"])) {
      $this->setClientSecret($config["client_secret"]);
    }

    /**
     * If client doesnt give us credentials throw a new exception
     */
    if ($this->client_secret == "" || $this->client_name == "") {
      throw new MakinecimClientException("Client configuration is not set! Please set config when you initialize the client!");
    }

    if (isset($config["cookie_lifetime"])) {
      $this->setCookieLifetime(intval($config["cookie_lifetime"]));
    }

    if (isset($config["cookie_name"])) {
      $this->setCookieName(trim($config["cookie_name"]));
    }

    if (isset($config["default_language"])) {
      $this->setDefaultLanguage($config["default_language"]);
    }

    return $this;
  }

  public function __get($name)
  {
    $endPointClass = "Makinecim\Resources\\" . ucfirst($name);
    if (class_exists($endPointClass)) {
      return new $endPointClass($this);
    }
    throw new MakinecimResourceNotFoundException;
  }

  public function getAuthEndpoint()
  {
    return $this->getApiEndpoint($this->getAuthURI());
  }

  public function getApiEndpoint($uri = NULL)
  {
    $endpoint = $this->getBase() . "/";
    if ($this->currentVersion != $this->getVersion()) {
      $endpoint .= $this->getVersion() . "/";
    }
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
   *
   * @return $this
   */
  public function setBase($base)
  {
    $this->base = $base;

    return $this;
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
   *
   * @return $this
   */
  public function setVersion($version)
  {
    $this->version = $version;

    return $this;
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
   *
   * @return $this
   */
  public function setAuthURI($authURI)
  {
    $this->authURI = $authURI;

    return $this;
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
   *
   * @return $this
   */
  public function setClientSecret($client_secret)
  {
    $this->client_secret = $client_secret;

    return $this;
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
   *
   * @return $this
   */
  public function setClientName($client_name)
  {
    $this->client_name = $client_name;

    return $this;
  }

  /**
   * @return int
   */
  public function getCookieLifetime()
  {
    return $this->cookieLifetime;
  }

  /**
   * @param int $cookieLifetime
   *
   * @return $this
   */
  public function setCookieLifetime($cookieLifetime)
  {
    $this->cookieLifetime = $cookieLifetime;

    return $this;
  }

  /**
   * @return string
   */
  public function getCookieName()
  {
    return $this->cookieName;
  }

  /**
   * @param string $cookieName
   *
   * @return $this
   */
  public function setCookieName($cookieName)
  {
    $this->cookieName = $cookieName;

    return $this;
  }

  /**
   * @return string
   */
  public function getDefaultLanguage()
  {
    return $this->defaultLanguage;
  }

  /**
   * @param string $defaultLanguage
   *
   * @return $this
   */
  public function setDefaultLanguage($defaultLanguage)
  {
    $this->defaultLanguage = $defaultLanguage;

    return $this;
  }

  public function buildClientTokenHeader()
  {
    return base64_encode($this->client_name . "&" . $this->client_secret . "&" . $this->client_domain . "&makinecim");
  }

  /**
   * @return mixed
   */
  public function getClientDomain()
  {
    return $this->client_domain;
  }

  /**
   * @param mixed $client_domain
   */
  public function setClientDomain($client_domain)
  {
    $this->client_domain = $client_domain;

    return $this;
  }
}