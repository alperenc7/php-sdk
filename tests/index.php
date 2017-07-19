<?php

require_once "../vendor/autoload.php";
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->overload();
$client = new Makinecim\Client();
dd($client);