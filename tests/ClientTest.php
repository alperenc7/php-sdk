<?php
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{

  /**
   * @var
   */
  private $underTest;
  /**
   * @var array
   */
  private $config = [
    "client_name"      => "makinecimtest",
    "client_secret"    => "awesomesecret",
    "cookie_lifetime"  => 4000,
    "cookie_name"      => "makinecim_cookie",
    "default_language" => "tr"
  ];

  /**
   *
   */
  public function setUp()
  {
    $this->underTest = new \Makinecim\Client($this->config);
  }

  /**
   * @depends setUp
   */
  public function testSetUpConfiguresName()
  {
    $this->assertEquals($this->config["client_name"], $this->underTest->getClientName());
  }

  /**
   * @depends testSetUpConfiguresName
   */
  public function testSetUpConfiguresSecret()
  {
    $this->assertEquals($this->config["client_secret"], $this->underTest->getClientSecret());
  }
}